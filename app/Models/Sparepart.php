<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    use HasFactory;

    protected $table = 'm_sparepart';

    public function DetailPesananPart()
    {
        return $this->hasMany(DetailPesananPart::class, 'm_sparepart_id');
    }
}
