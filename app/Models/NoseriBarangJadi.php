<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoseriBarangJadi extends Model
{
    use HasFactory;

    protected $table = "noseri_barang_jadi";

    function GudangBarangJadi()
    {
        return $this->belongsTo(GudangBarangJadi::class, 'gdg_barang_jadi_id');
    }
    function NoseriTGbj()
    {
        return $this->hasMany(NoseriTGbj::class,'id');
    }

}
