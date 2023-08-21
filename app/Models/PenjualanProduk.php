<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualanProduk extends Model
{
    /**
     * @var string
     */
    protected $table = 'penjualan_produk';

    /**
     * @var array
     */

    public function Produk()
    {
        return $this->belongsToMany(Produk::class, 'detail_penjualan_produk')
            ->withPivot('jumlah')
            ->orderBy('id', 'ASC');
    }
}
