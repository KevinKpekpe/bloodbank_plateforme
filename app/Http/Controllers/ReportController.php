<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\User;
use App\Models\Donation;
use App\Models\Appointment;
use App\Models\BloodType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Afficher la page principale des rapports
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Rapport général de la plateforme
     */
    public function general()
    {
        $stats = [
            'total_banks' => Bank::count(),
            'active_banks' => Bank::where('status', 'active')->count(),
            'total_users' => User::count(),
            'total_donors' => User::where('role', 'donor')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_appointments' => Appointment::count(),
            'total_donations' => Donation::count(),
            'available_donations' => Donation::where('status', 'available')->count(),
            'expired_donations' => Donation::where('status', 'expired')->count(),
            'used_donations' => Donation::where('status', 'used')->count()
        ];

        // Statistiques par mois (6 derniers mois)
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyStats[] = [
                'month' => $date->format('F Y'),
                'appointments' => Appointment::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'donations' => Donation::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count()
            ];
        }

        return view('reports.general', compact('stats', 'monthlyStats'));
    }

    /**
     * Rapport des banques de sang
     */
    public function banks()
    {
        $banks = Bank::withCount(['users', 'appointments', 'donations'])
            ->orderBy('appointments_count', 'desc')
            ->get();

        // Statistiques par banque
        $bankStats = $banks->map(function ($bank) {
            return [
                'name' => $bank->name,
                'users_count' => $bank->users_count,
                'appointments_count' => $bank->appointments_count,
                'donations_count' => $bank->donations_count,
                'available_donations' => $bank->donations()->where('status', 'available')->count(),
                'status' => $bank->status
            ];
        });

        return view('reports.banks', compact('banks', 'bankStats'));
    }

    /**
     * Rapport des dons par groupe sanguin
     */
    public function bloodTypes()
    {
        $bloodTypes = BloodType::withCount('donations')->get();

        // Statistiques détaillées par groupe sanguin
        $bloodTypeStats = $bloodTypes->map(function ($bloodType) {
            $donations = $bloodType->donations();

            return [
                'name' => $bloodType->name,
                'total_donations' => $donations->count(),
                'available_donations' => $donations->where('status', 'available')->count(),
                'used_donations' => $donations->where('status', 'used')->count(),
                'expired_donations' => $donations->where('status', 'expired')->count(),
                'total_volume' => $donations->sum('volume')
            ];
        });

        return view('reports.blood-types', compact('bloodTypes', 'bloodTypeStats'));
    }

    /**
     * Rapport des rendez-vous
     */
    public function appointments()
    {
        // Statistiques des rendez-vous
        $appointmentStats = [
            'total' => Appointment::count(),
            'pending' => Appointment::where('status', 'pending')->count(),
            'confirmed' => Appointment::where('status', 'confirmed')->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'cancelled' => Appointment::where('status', 'cancelled')->count()
        ];

        // Rendez-vous par mois
        $monthlyAppointments = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyAppointments[] = [
                'month' => $date->format('F Y'),
                'total' => Appointment::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'confirmed' => Appointment::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->where('status', 'confirmed')
                    ->count(),
                'completed' => Appointment::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->where('status', 'completed')
                    ->count()
            ];
        }

        return view('reports.appointments', compact('appointmentStats', 'monthlyAppointments'));
    }

    /**
     * Rapport des dons
     */
    public function donations()
    {
        // Statistiques des dons
        $donationStats = [
            'total' => Donation::count(),
            'collected' => Donation::where('status', 'collected')->count(),
            'processed' => Donation::where('status', 'processed')->count(),
            'available' => Donation::where('status', 'available')->count(),
            'used' => Donation::where('status', 'used')->count(),
            'expired' => Donation::where('status', 'expired')->count(),
            'total_volume' => Donation::sum('volume')
        ];

        // Dons par mois
        $monthlyDonations = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyDonations[] = [
                'month' => $date->format('F Y'),
                'total' => Donation::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'volume' => Donation::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('volume'),
                'available' => Donation::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->where('status', 'available')
                    ->count()
            ];
        }

        return view('reports.donations', compact('donationStats', 'monthlyDonations'));
    }

    /**
     * Rapport d'activité des utilisateurs
     */
    public function users()
    {
        // Statistiques des utilisateurs
        $userStats = [
            'total' => User::count(),
            'donors' => User::where('role', 'donor')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'superadmins' => User::where('role', 'superadmin')->count(),
            'active' => User::whereNotNull('email_verified_at')->count(),
            'inactive' => User::whereNull('email_verified_at')->count()
        ];

        // Nouveaux utilisateurs par mois
        $monthlyUsers = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyUsers[] = [
                'month' => $date->format('F Y'),
                'total' => User::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'donors' => User::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->where('role', 'donor')
                    ->count(),
                'admins' => User::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->where('role', 'admin')
                    ->count()
            ];
        }

        return view('reports.users', compact('userStats', 'monthlyUsers'));
    }

    /**
     * Exporter un rapport en PDF
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'general');
        $format = $request->get('format', 'pdf');

        // Logique d'export selon le type et format
        switch ($type) {
            case 'general':
                return $this->exportGeneral($format);
            case 'banks':
                return $this->exportBanks($format);
            case 'donations':
                return $this->exportDonations($format);
            default:
                return back()->with('error', 'Type de rapport non supporté');
        }
    }

    private function exportGeneral($format)
    {
        // Logique d'export du rapport général
        return response()->json(['message' => 'Export en cours de développement']);
    }

    private function exportBanks($format)
    {
        // Logique d'export du rapport des banques
        return response()->json(['message' => 'Export en cours de développement']);
    }

    private function exportDonations($format)
    {
        // Logique d'export du rapport des dons
        return response()->json(['message' => 'Export en cours de développement']);
    }
}
