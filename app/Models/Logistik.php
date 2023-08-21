<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logistik extends Model
{
    protected $table = 'logistik';
    protected $fillable = ['ekspedisi_id', 'status_id', 'nosurat', 'noresi', 'tgl_kirim', 'nama_pengirim'];

    public function Ekspedisi()
    {
        return $this->belongsTo(Ekspedisi::class, 'ekspedisi_id');
    }

    public function DetailLogistik()
    {
        return $this->hasMany(DetailLogistik::class);
    }

    public function DetailLogistikPart()
    {
        return $this->hasMany(DetailLogistikPart::class);
    }
}
