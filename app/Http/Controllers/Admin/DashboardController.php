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
        // Total keseluruhan
        $jumlahDokter = Dokter::count();
        $jumlahPasien = Pasien::count();
        $jumlahPoli = Poli::count();
        $jumlahObat = Obat::count();

        return view('admin.dashboard', compact(
            'jumlahDokter',
            'jumlahPasien',
            'jumlahPoli',
            'jumlahObat'
        ));
    }

}
