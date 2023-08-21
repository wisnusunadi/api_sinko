<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    protected $table = 'detail_pesanan';
    protected $fillable = ['pesanan_id', 'penjualan_produk_id', 'detail_rencana_penjualan_id', 'jumlah', 'harga', 'ongkir'];

    public function Pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id')
            ->orderBy('so', 'ASC');
    }
    public function PenjualanProduk()
    {
        return $this->belongsTo(PenjualanProduk::class, 'penjualan_produk_id');
    }
    public function DetailPesananProduk()
    {
        return $this->hasMany(DetailPesananProduk::class);
    }
}
