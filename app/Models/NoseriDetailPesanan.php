<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoseriDetailPesanan extends Model
{
    protected $table = 'noseri_detail_pesanan';
    protected $fillable = ['detail_pesanan_produk_id', 't_tfbj_noseri_id', 'status', 'tgl_uji',];

    public function DetailPesananProduk()
    {
        return $this->belongsTo(DetailPesananProduk::class, 'detail_pesanan_produk_id');
    }
    public function NoseriTGbj()
    {
        return $this->belongsTo(NoseriTGbj::class, 't_tfbj_noseri_id');
    }
    public function NoseriDetailLogistik()
    {
        return $this->hasOne(NoseriDetailLogistik::class);
    }
}
