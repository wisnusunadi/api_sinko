<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesananProduk extends Model
{
    protected $table = 'detail_pesanan_produk';
    protected $fillable = ['detail_pesanan_id', 'gudang_barang_jadi_id', 'checked_by', 'status_cek'];
    public $timestamps = false;

    public function GudangBarangJadi()
    {
        return $this->belongsTo(GudangBarangJadi::class, 'gudang_barang_jadi_id');
    }
    public function DetailPesanan()
    {
        return $this->belongsTo(DetailPesanan::class, 'detail_pesanan_id');
    }
    public function NoseriDetailPesanan()
    {
        return $this->hasMany(NoseriDetailPesanan::class);
    }
    public function DetailLogistik()
    {
        return $this->hasMany(DetailLogistik::class, 'detail_pesanan_produk_id');
    }
}
