<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Poli;
use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboardAdmin(Request $request)
    {
        $type = $request->get('type', 'daily'); // default ke harian
        $dateFormat = $type === 'daily' ? 'Y-m-d' : 'Y-m';

        // Label waktu berdasarkan filter
        $labels = $this->generateLabels($type);

        // Ambil data statistik berdasarkan waktu (harian/bulanan)
        $statDokter = $this->getStatistik(Dokter::class, $labels, $dateFormat);
        $statPasien = $this->getStatistik(Pasien::class, $labels, $dateFormat);
        $statPoli   = $this->getStatistik(Poli::class, $labels, $dateFormat);
        $statObat   = $this->getStatistik(Obat::class, $labels, $dateFormat);

        // Total keseluruhan
        $jumlahDokter = Dokter::count();
        $jumlahPasien = Pasien::count();
        $jumlahPoli = Poli::count();
        $jumlahObat = Obat::count();

        return view('admin.dashboard', compact(
            'type',
            'labels',
            'statDokter',
            'statPasien',
            'statPoli',
            'statObat',
            'jumlahDokter',
            'jumlahPasien',
            'jumlahPoli',
            'jumlahObat'
        ));
    }

    private function generateLabels($type)
    {
        $labels = [];
        if ($type === 'daily') {
            $days = now()->daysInMonth;
            for ($i = 1; $i <= $days; $i++) {
                $labels[] = now()->format('Y-m-') . str_pad($i, 2, '0', STR_PAD_LEFT);
            }
        } else {
            for ($i = 1; $i <= 12; $i++) {
                $labels[] = now()->format('Y-') . str_pad($i, 2, '0', STR_PAD_LEFT);
            }
        }
        return $labels;
    }

    private function getStatistik($modelClass, $labels, $dateFormat)
    {
        $data = $modelClass::selectRaw("DATE_FORMAT(created_at, '$dateFormat') as date, COUNT(*) as total")
            ->whereYear('created_at', now()->year)
            ->groupBy('date')
            ->pluck('total', 'date');

        return collect($labels)->map(fn($label) => $data[$label] ?? 0)->values();
    }
}
