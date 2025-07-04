<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DaftarPoli extends Model
{
    protected $fillable = ['id_pasien', 'id_jadwal', 'keluhan', 'nama_pasien', 'no_rm'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->id_pasien) {
                $pasien = Pasien::find($model->id_pasien);
                if ($pasien) {
                    $model->nama_pasien = $pasien->nama;
                    $model->no_rm = $pasien->no_rm;
                }
            }
        });
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien');
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalPeriksa::class, 'id_jadwal');
    }

    // Di model DaftarPoli.php
    public function periksa()
    {
        return $this->hasOne(Periksa::class, 'id_daftar_poli', 'id');
    }

}

