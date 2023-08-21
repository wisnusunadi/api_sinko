<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesananPart extends Model
{
    protected $table = 'detail_pesanan_part';
    protected $fillable = ['pesanan_id', 'm_sparepart_id', 'jumlah', 'harga', 'ongkir'];

    public function Pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
    public function Sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'm_sparepart_id');
    }
    public function DetailLogistikPart()
    {
        return $this->hasOne(DetailLogistikPart::class);
    }
    public function OutgoingPesananPart()
    {
        return $this->hasMany(OutgoingPesananPart::class, 'detail_pesanan_part_id');
    }
}
