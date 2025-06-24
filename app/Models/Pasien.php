<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $fillable = ['user_id', 'nama', 'alamat', 'no_ktp', 'no_hp', 'no_rm'];

    public function daftarPolis()
    {
        return $this->hasMany(DaftarPoli::class, 'id_pasien');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

