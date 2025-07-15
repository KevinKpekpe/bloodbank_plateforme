<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\User;
use App\Models\Donation;
use App\Models\Appointment;
use App\Models\BloodType;
use App\Models\BloodBag;
use App\Helpers\StockHelper;
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
            'total_blood_bags' => BloodBag::count(),
            'available_blood_bags' => BloodBag::where('status', 'available')->count(),
            'reserved_blood_bags' => BloodBag::where('status', 'reserved')->count(),
            'transfused_blood_bags' => BloodBag::where('status', 'transfused')->count(),
            'expired_blood_bags' => BloodBag::where('status', 'expired')->count(),
            'discarded_blood_bags' => BloodBag::where('status', 'discarded')->count()
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
                    ->count(),
                'blood_bags' => BloodBag::whereYear('created_at', $date->year)
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
        $banks = Bank::with(['bloodStocks.bloodType'])->get();

        // Ajouter les statistiques des poches pour chaque banque
        $banks->each(function ($bank) {
            $statistics = StockHelper::getDashboardStatistics($bank);
            $bank->statistics = $statistics;
        });

        // Statistiques globales
        $globalStats = [
            'total_banks' => $banks->count(),
            'active_banks' => $banks->where('status', 'active')->count(),
            'total_blood_bags' => $banks->sum(function ($bank) {
                return $bank->statistics['total_bags'] ?? 0;
            }),
            'total_volume_l' => $banks->sum(function ($bank) {
                return $bank->statistics['total_volume_l'] ?? 0;
            }),
            'available_bags' => $banks->sum(function ($bank) {
                return $bank->statistics['available_bags'] ?? 0;
            }),
            'critical_bags' => $banks->sum(function ($bank) {
                return $bank->statistics['critical_bags'] ?? 0;
            })
        ];

        return view('reports.banks', compact('banks', 'globalStats'));
    }

    /**
     * Rapport des dons
     */
    public function donations(Request $request)
    {
        $query = Donation::with(['donor.user', 'bloodType', 'bloodBags']);

        // Filtres
        if ($request->filled('bank_id')) {
            $query->where('bank_id', $request->bank_id);
        }

        if ($request->filled('blood_type_id')) {
            $query->where('blood_type_id', $request->blood_type_id);
        }

        if ($request->filled('date_from')) {
            $query->where('donation_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('donation_date', '<=', $request->date_to);
        }

        $donations = $query->orderBy('donation_date', 'desc')->paginate(20);

        // Statistiques
        $stats = [
            'total_donations' => Donation::count(),
            'total_blood_bags' => BloodBag::count(),
            'total_volume_l' => BloodBag::sum('volume_ml') / 1000,
            'donations_this_month' => Donation::whereMonth('donation_date', now()->month)->count(),
            'blood_bags_this_month' => BloodBag::whereMonth('created_at', now()->month)->count()
        ];

        $banks = Bank::where('status', 'active')->get();
        $bloodTypes = BloodType::all();

        return view('reports.donations', compact('donations', 'stats', 'banks', 'bloodTypes'));
    }

    /**
     * Rapport des poches de sang
     */
    public function bloodBags(Request $request)
    {
        $query = BloodBag::with(['donor', 'bloodType', 'donation']);

        // Filtres
        if ($request->filled('bank_id')) {
            $query->where('bank_id', $request->bank_id);
        }

        if ($request->filled('blood_type_id')) {
            $query->where('blood_type_id', $request->blood_type_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $bloodBags = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistiques
        $stats = [
            'total_bags' => BloodBag::count(),
            'available_bags' => BloodBag::where('status', 'available')->count(),
            'reserved_bags' => BloodBag::where('status', 'reserved')->count(),
            'transfused_bags' => BloodBag::where('status', 'transfused')->count(),
            'expired_bags' => BloodBag::where('status', 'expired')->count(),
            'discarded_bags' => BloodBag::where('status', 'discarded')->count(),
            'total_volume_l' => BloodBag::sum('volume_ml') / 1000,
            'expiring_soon' => BloodBag::where('status', 'available')
                ->where('expiry_date', '<=', now()->addDays(7))
                ->where('expiry_date', '>', now())
                ->count()
        ];

        $banks = Bank::where('status', 'active')->get();
        $bloodTypes = BloodType::all();
        $statuses = ['available', 'reserved', 'transfused', 'expired', 'discarded'];

        return view('reports.blood-bags', compact('bloodBags', 'stats', 'banks', 'bloodTypes', 'statuses'));
    }

    /**
     * Rapport des stocks par banque
     */
    public function stocks()
    {
        $banks = Bank::where('status', 'active')->get();

        // Ajouter les statistiques détaillées des poches pour chaque banque
        $banks->each(function ($bank) {
            $statistics = StockHelper::getDashboardStatistics($bank);
            $detailedStats = StockHelper::getDetailedStatistics($bank);

            $bank->statistics = $statistics;
            $bank->detailed_stats = $detailedStats;
        });

        // Statistiques globales
        $globalStats = [
            'total_banks' => $banks->count(),
            'total_blood_bags' => $banks->sum(function ($bank) {
                return $bank->statistics['total_bags'] ?? 0;
            }),
            'total_volume_l' => $banks->sum(function ($bank) {
                return $bank->statistics['total_volume_l'] ?? 0;
            }),
            'available_bags' => $banks->sum(function ($bank) {
                return $bank->statistics['available_bags'] ?? 0;
            }),
            'reserved_bags' => $banks->sum(function ($bank) {
                return $bank->statistics['reserved_bags'] ?? 0;
            }),
            'critical_bags' => $banks->sum(function ($bank) {
                return $bank->statistics['critical_bags'] ?? 0;
            }),
            'expiring_soon_bags' => $banks->sum(function ($bank) {
                return $bank->statistics['expiring_soon_bags'] ?? 0;
            })
        ];

        return view('reports.stocks', compact('banks', 'globalStats'));
    }

    /**
     * Export des données
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'donations');
        $format = $request->get('format', 'csv');

        switch ($type) {
            case 'donations':
                return $this->exportDonations($format);
            case 'blood_bags':
                return $this->exportBloodBags($format);
            case 'stocks':
                return $this->exportStocks($format);
            default:
                return back()->with('error', 'Type d\'export non supporté.');
        }
    }

    /**
     * Exporter les dons
     */
    private function exportDonations($format)
    {
        $donations = Donation::with(['donor.user', 'bloodType', 'bloodBags'])
            ->orderBy('donation_date', 'desc')
            ->get();

        $data = [];
        foreach ($donations as $donation) {
            $data[] = [
                'ID' => $donation->id,
                'Donneur' => $donation->donor->user->name,
                'Groupe Sanguin' => $donation->bloodType->name,
                'Volume (L)' => $donation->volume,
                'Poches Créées' => $donation->bloodBags->count(),
                'Statut' => $donation->status,
                'Date de Don' => $donation->donation_date->format('d/m/Y'),
                'Banque' => $donation->bank->name
            ];
        }

        return $this->generateExport($data, 'dons', $format);
    }

    /**
     * Exporter les poches de sang
     */
    private function exportBloodBags($format)
    {
        $bloodBags = BloodBag::with(['donor', 'bloodType', 'donation'])
            ->orderBy('created_at', 'desc')
            ->get();

        $data = [];
        foreach ($bloodBags as $bag) {
            $data[] = [
                'Numéro Poche' => $bag->bag_number,
                'Donneur' => $bag->donor->firstname . ' ' . $bag->donor->surname,
                'Groupe Sanguin' => $bag->bloodType->name,
                'Volume (ml)' => $bag->volume_ml,
                'Statut' => $bag->status,
                'Date Collecte' => $bag->collection_date->format('d/m/Y'),
                'Date Expiration' => $bag->expiry_date->format('d/m/Y'),
                'Banque' => $bag->bank->name
            ];
        }

        return $this->generateExport($data, 'poches_sang', $format);
    }

    /**
     * Exporter les stocks
     */
    private function exportStocks($format)
    {
        $banks = Bank::where('status', 'active')->get();

        $data = [];
        foreach ($banks as $bank) {
            $statistics = StockHelper::getDashboardStatistics($bank);
            $detailedStats = StockHelper::getDetailedStatistics($bank);

            foreach ($detailedStats['by_blood_type'] ?? [] as $bloodType => $stats) {
                $data[] = [
                    'Banque' => $bank->name,
                    'Groupe Sanguin' => $bloodType,
                    'Total Poches' => $stats['count'] ?? 0,
                    'Poches Disponibles' => $stats['available'] ?? 0,
                    'Poches Réservées' => $stats['reserved'] ?? 0,
                    'Volume Total (L)' => $stats['volume_l'] ?? 0,
                    'Poches Expirant Bientôt' => $stats['expiring_soon'] ?? 0
                ];
            }
        }

        return $this->generateExport($data, 'stocks', $format);
    }

    /**
     * Générer l'export
     */
    private function generateExport($data, $filename, $format)
    {
        if ($format === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '_' . date('Y-m-d') . '.csv"',
            ];

            $callback = function() use ($data) {
                $file = fopen('php://output', 'w');

                // En-têtes
                if (!empty($data)) {
                    fputcsv($file, array_keys($data[0]));
                }

                // Données
                foreach ($data as $row) {
                    fputcsv($file, $row);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return back()->with('error', 'Format d\'export non supporté.');
    }
}
