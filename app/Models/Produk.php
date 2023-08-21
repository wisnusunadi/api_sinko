<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    /**
     * @var string
     */
    protected $table = 'produk';

    /**
     * @var array
     */

    public function PenjualanProduk()
    {
        return $this->belongsToMany(PenjualanProduk::class, 'detail_penjualan_produk')
            ->withPivot('jumlah')
            ->withTimestamps();
    }
    public function GudangBarangJadi()
    {
        return $this->hasMany(GudangBarangJadi::class);
    }
}
