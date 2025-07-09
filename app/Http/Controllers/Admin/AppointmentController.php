<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Donation;
use App\Models\BloodType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Afficher la liste des rendez-vous de la banque
     */
    public function index(Request $request)
    {
        $bank = Auth::user()->bank;

        $query = Appointment::with(['donor', 'donation'])
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
        // Vérifier que le rendez-vous appartient à la banque de l'admin
        if ($appointment->bank_id !== Auth::user()->bank_id) {
            abort(403);
        }

        $appointment->load(['donor', 'donation', 'donation.bloodType']);

        return view('admin.appointments.show', compact('appointment'));
    }

    /**
     * Confirmer un rendez-vous
     */
    public function confirm(Appointment $appointment)
    {
        // Vérifier que le rendez-vous appartient à la banque de l'admin
        if ($appointment->bank_id !== Auth::user()->bank_id) {
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
        // Vérifier que le rendez-vous appartient à la banque de l'admin
        if ($appointment->bank_id !== Auth::user()->bank_id) {
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
     * Marquer un rendez-vous comme terminé et créer le don
     */
    public function complete(Request $request, Appointment $appointment)
    {
        // Vérifier que le rendez-vous appartient à la banque de l'admin
        if ($appointment->bank_id !== Auth::user()->bank_id) {
            abort(403);
        }

        if ($appointment->status !== 'confirmed') {
            return back()->with('error', 'Ce rendez-vous doit être confirmé pour être terminé.');
        }

        $request->validate([
            'blood_type_id' => 'required|exists:blood_types,id',
            'volume' => 'required|numeric|min:0.3|max:0.5',
            'notes' => 'nullable|string|max:500'
        ]);

        // Créer le don
        $donation = Donation::create([
            'donor_id' => $appointment->donor_id,
            'bank_id' => $appointment->bank_id,
            'appointment_id' => $appointment->id,
            'blood_type_id' => $request->blood_type_id,
            'volume' => $request->volume,
            'donation_date' => now(),
            'status' => 'collected',
            'notes' => $request->notes
        ]);

        // Marquer le rendez-vous comme terminé
        $appointment->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        return back()->with('success', 'Don enregistré avec succès.');
    }

    /**
     * Afficher le calendrier des rendez-vous
     */
    public function calendar()
    {
        $bank = Auth::user()->bank;

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
        $bank = Auth::user()->bank;

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
}