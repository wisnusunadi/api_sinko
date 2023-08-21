<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailLogistikPart extends Model
{
    protected $table = 'detail_logistik_part';
    protected $fillable = ['logistik_id', 'detail_pesanan_part_id','jumlah'];

    public function Logistik()
    {
        return $this->belongsTo(Logistik::class, 'logistik_id');
    }
    public function DetailPesananPart()
    {
        return $this->belongsTo(DetailPesananPart::class, 'detail_pesanan_part_id');
    }
    public function OutgoingPesananPart()
    {
        return $this->hasMany(OutgoingPesananPart::class, 'detail_logistik_part_id');
    }
}
