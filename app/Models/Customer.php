<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';
    protected $fillable = ['id_provinsi', 'nama', 'telp', 'email', 'alamat', 'npwp', 'ktp', 'batas', 'pic', 'ket', 'izin_usaha', 'nama_pemilik', 'modal_usaha', 'hasil_penjualan'];

    public function Ekatalog()
    {
        return $this->hasMany(Ekatalog::class);
    }

}
