<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Afficher la liste des rendez-vous du donneur
     */
    public function index()
    {
        $user = Auth::user();
        $donor = $user->donor;

        if (!$donor) {
            return redirect()->route('home')
                ->with('error', 'Profil donneur non trouvé.');
        }

        $appointments = $donor->appointments()
            ->with(['bank'])
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);

        return view('donor.appointments.index', compact('appointments'));
    }

    /**
     * Afficher le formulaire de création de rendez-vous
     */
    public function create()
    {
        $user = Auth::user();
        $donor = $user->donor;

        if (!$donor) {
            return redirect()->route('home')
                ->with('error', 'Profil donneur non trouvé.');
        }

        // Récupérer les banques actives
        $banks = Bank::where('status', 'active')->get();

        return view('donor.appointments.create', compact('banks'));
    }

    /**
     * Créer un nouveau rendez-vous
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $donor = $user->donor;

        if (!$donor) {
            return redirect()->route('home')
                ->with('error', 'Profil donneur non trouvé.');
        }

        $request->validate([
            'bank_id' => 'required|exists:banks,id',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:500',
        ]);

        // Vérifier que la banque est active
        $bank = Bank::findOrFail($request->bank_id);
        if ($bank->status !== 'active') {
            return back()->withErrors(['bank_id' => 'Cette banque n\'est pas disponible.']);
        }

        // Combiner date et heure
        $appointmentDateTime = Carbon::parse($request->appointment_date . ' ' . $request->appointment_time);

        // Vérifier que l'heure est dans les heures d'ouverture (8h-18h)
        $hour = $appointmentDateTime->hour;
        if ($hour < 8 || $hour >= 18) {
            return back()->withErrors(['appointment_time' => 'Les rendez-vous sont possibles entre 8h et 18h.']);
        }

        // Vérifier qu'il n'y a pas déjà un rendez-vous à cette date
        $existingAppointment = $donor->appointments()
            ->where('appointment_date', $appointmentDateTime)
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();

        if ($existingAppointment) {
            return back()->withErrors(['appointment_date' => 'Vous avez déjà un rendez-vous à cette date.']);
        }

        // Créer le rendez-vous
        $appointment = Appointment::create([
            'donor_id' => $donor->id,
            'bank_id' => $request->bank_id,
            'appointment_date' => $appointmentDateTime,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        return redirect()->route('donor.appointments.index')
            ->with('success', 'Rendez-vous créé avec succès. En attente de confirmation.');
    }

    /**
     * Afficher les détails d'un rendez-vous
     */
    public function show(Appointment $appointment)
    {
        $user = Auth::user();
        $donor = $user->donor;

        if (!$donor || $appointment->donor_id !== $donor->id) {
            abort(403, 'Accès non autorisé.');
        }

        $appointment->load(['bank', 'donation']);

        return view('donor.appointments.show', compact('appointment'));
    }

    /**
     * Annuler un rendez-vous
     */
    public function cancel(Appointment $appointment)
    {
        $user = Auth::user();
        $donor = $user->donor;

        if (!$donor || $appointment->donor_id !== $donor->id) {
            abort(403, 'Accès non autorisé.');
        }

        // Vérifier que le rendez-vous peut être annulé
        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Ce rendez-vous ne peut plus être annulé.');
        }

        $appointment->update(['status' => 'cancelled']);

        return redirect()->route('donor.appointments.index')
            ->with('success', 'Rendez-vous annulé avec succès.');
    }

    /**
     * Afficher le calendrier des disponibilités
     */
    public function calendar()
    {
        $user = Auth::user();
        $donor = $user->donor;

        if (!$donor) {
            return redirect()->route('home')
                ->with('error', 'Profil donneur non trouvé.');
        }

        $banks = Bank::where('status', 'active')->get();
        $appointments = $donor->appointments()
            ->where('appointment_date', '>=', now())
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        return view('donor.appointments.calendar', compact('banks', 'appointments'));
    }
}