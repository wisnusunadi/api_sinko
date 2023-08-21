<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoseriDetailLogistik extends Model
{
    protected $table = 'noseri_logistik';
    protected $fillable = ['detail_logistik_id', 'noseri_detail_pesanan_id'];

    public function DetailLogistik()
    {
        return $this->belongsto(DetailLogistik::class, 'detail_logistik_id');
    }
    public function NoseriDetailPesanan()
    {
        return $this->belongsto(NoseriDetailPesanan::class, 'noseri_detail_pesanan_id');
    }
}
