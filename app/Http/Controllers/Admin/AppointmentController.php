<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Donation;
use App\Models\BloodStock;
use App\Models\BloodBag;
use App\Helpers\StockHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Récupérer la banque de l'admin connecté
     */
    private function getAdminBank()
    {
        $user = Auth::user();
        $bank = $user->managedBank;

        if (!$bank) {
            abort(403, 'Vous n\'êtes pas associé à une banque de sang.');
        }

        return $bank;
    }

    /**
     * Afficher la liste des rendez-vous de la banque
     */
    public function index(Request $request)
    {
        $bank = $this->getAdminBank();

        $query = Appointment::with(['donor.user', 'donation'])
            ->where('bank_id', $bank->id);

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('appointment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('appointment_date', '<=', $request->date_to);
        }

        $appointments = $query->orderBy('appointment_date', 'asc')
            ->paginate(15);

        $statuses = ['pending', 'confirmed', 'cancelled', 'completed'];

        return view('admin.appointments.index', compact('appointments', 'statuses'));
    }

    /**
     * Afficher les détails d'un rendez-vous
     */
    public function show(Appointment $appointment)
    {
        $bank = $this->getAdminBank();

        // Vérifier que le rendez-vous appartient à la banque de l'admin
        if ($appointment->bank_id !== $bank->id) {
            abort(403);
        }

        $appointment->load(['donor.user', 'donor.bloodType', 'donation', 'donation.bloodType', 'donation.bloodBags']);

        return view('admin.appointments.show', compact('appointment'));
    }

    /**
     * Confirmer un rendez-vous
     */
    public function confirm(Appointment $appointment)
    {
        $bank = $this->getAdminBank();

        // Vérifier que le rendez-vous appartient à la banque de l'admin
        if ($appointment->bank_id !== $bank->id) {
            abort(403);
        }

        if ($appointment->status !== 'pending') {
            return back()->with('error', 'Ce rendez-vous ne peut pas être confirmé.');
        }

        $appointment->update([
            'status' => 'confirmed',
            'confirmed_at' => now()
        ]);

        // TODO: Envoyer une notification au donneur

        return back()->with('success', 'Rendez-vous confirmé avec succès.');
    }

    /**
     * Rejeter un rendez-vous
     */
    public function reject(Request $request, Appointment $appointment)
    {
        $bank = $this->getAdminBank();

        // Vérifier que le rendez-vous appartient à la banque de l'admin
        if ($appointment->bank_id !== $bank->id) {
            abort(403);
        }

        if ($appointment->status !== 'pending') {
            return back()->with('error', 'Ce rendez-vous ne peut pas être rejeté.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $appointment->update([
            'status' => 'cancelled',
            'rejection_reason' => $request->rejection_reason,
            'cancelled_at' => now()
        ]);

        // TODO: Envoyer une notification au donneur

        return back()->with('success', 'Rendez-vous rejeté avec succès.');
    }

    /**
     * Marquer un rendez-vous comme terminé et créer le don avec les poches de sang
     */
    public function complete(Request $request, Appointment $appointment)
    {
        $bank = $this->getAdminBank();

        // Vérifier que le rendez-vous appartient à la banque de l'admin
        if ($appointment->bank_id !== $bank->id) {
            abort(403);
        }

        if ($appointment->status !== 'confirmed') {
            return back()->with('error', 'Ce rendez-vous doit être confirmé pour être terminé.');
        }

        $request->validate([
            'volume' => 'required|numeric|min:0.3|max:0.5',
            'notes' => 'nullable|string|max:500'
        ]);

        // Récupérer le groupe sanguin du donneur
        $blood_type_id = $appointment->donor->blood_type_id;
        if (!$blood_type_id) {
            return back()->with('error', 'Le groupe sanguin du donneur est manquant.');
        }

        try {
            // Créer le don
            $donation = Donation::create([
                'donor_id' => $appointment->donor_id,
                'bank_id' => $appointment->bank_id,
                'appointment_id' => $appointment->id,
                'blood_type_id' => $blood_type_id,
                'volume' => $request->volume,
                'quantity' => $request->volume * 1000, // Convertir litres en ml
                'donation_date' => now(),
                'status' => 'collected',
                'notes' => $request->notes
            ]);

            // Créer automatiquement les poches de sang
            $totalVolumeMl = $donation->quantity;
            $bagsToCreate = floor($totalVolumeMl / 450);
            $remainingVolume = $totalVolumeMl % 450;

            // Créer les poches de 450ml
            for ($i = 0; $i < $bagsToCreate; $i++) {
                $bagNumber = $this->generateBagNumber($bank);

                BloodBag::create([
                    'bag_number' => $bagNumber,
                    'donation_id' => $donation->id,
                    'donor_id' => $donation->donor_id,
                    'bank_id' => $donation->bank_id,
                    'blood_type_id' => $donation->blood_type_id,
                    'volume_ml' => 450,
                    'collection_date' => $donation->donation_date,
                    'expiry_date' => $donation->donation_date->addDays(42), // 42 jours pour les poches
                    'status' => 'available',
                    'notes' => "Poche créée automatiquement lors du don #{$donation->id}"
                ]);
            }

            // Créer une poche supplémentaire avec le volume restant si > 200ml
            if ($remainingVolume >= 200) {
                $bagNumber = $this->generateBagNumber($bank);

                BloodBag::create([
                    'bag_number' => $bagNumber,
                    'donation_id' => $donation->id,
                    'donor_id' => $donation->donor_id,
                    'bank_id' => $donation->bank_id,
                    'blood_type_id' => $donation->blood_type_id,
                    'volume_ml' => $remainingVolume,
                    'collection_date' => $donation->donation_date,
                    'expiry_date' => $donation->donation_date->addDays(42),
                    'status' => 'available',
                    'notes' => "Poche créée automatiquement lors du don #{$donation->id} (volume restant)"
                ]);
            }

            // Mettre à jour le stock de la banque (maintenant basé sur les poches)
            $bloodStock = BloodStock::firstOrCreate([
                'bank_id' => $appointment->bank_id,
                'blood_type_id' => $blood_type_id
            ]);

            // Mettre à jour les statistiques du stock
            StockHelper::updateStockStatistics($bloodStock);

            // Marquer le rendez-vous comme terminé
            $appointment->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);

            return back()->with('success', 'Don enregistré avec succès. ' . ($bagsToCreate + ($remainingVolume >= 200 ? 1 : 0)) . ' poche(s) créée(s).');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'enregistrement du don : ' . $e->getMessage());
        }
    }

    /**
     * Afficher le calendrier des rendez-vous
     */
    public function calendar()
    {
        $bank = $this->getAdminBank();

        $appointments = Appointment::with('donor')
            ->where('bank_id', $bank->id)
            ->where('status', '!=', 'cancelled')
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->donor->name,
                    'start' => $appointment->appointment_date,
                    'end' => Carbon::parse($appointment->appointment_date)->addMinutes(30),
                    'status' => $appointment->status,
                    'url' => route('admin.appointments.show', $appointment->id)
                ];
            });

        return view('admin.appointments.calendar', compact('appointments'));
    }

    /**
     * Statistiques des rendez-vous
     */
    public function statistics()
    {
        $bank = $this->getAdminBank();

        // Statistiques par statut
        $statusStats = Appointment::where('bank_id', $bank->id)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Rendez-vous par mois (6 derniers mois)
        $monthlyStats = Appointment::where('bank_id', $bank->id)
            ->where('appointment_date', '>=', now()->subMonths(6))
            ->selectRaw('DATE_FORMAT(appointment_date, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Rendez-vous du jour
        $todayAppointments = Appointment::with('donor')
            ->where('bank_id', $bank->id)
            ->whereDate('appointment_date', today())
            ->where('status', '!=', 'cancelled')
            ->orderBy('appointment_date')
            ->get();

        return view('admin.appointments.statistics', compact('statusStats', 'monthlyStats', 'todayAppointments'));
    }

    /**
     * Générer un numéro de poche unique
     */
    private function generateBagNumber($bank)
    {
        $prefix = strtoupper(substr($bank->name, 0, 3));
        $year = date('Y');
        $month = date('m');

        // Trouver le dernier numéro pour ce mois
        $lastBag = BloodBag::where('bag_number', 'like', "{$prefix}{$year}{$month}%")
            ->orderBy('bag_number', 'desc')
            ->first();

        if ($lastBag) {
            $lastNumber = intval(substr($lastBag->bag_number, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $year . $month . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
