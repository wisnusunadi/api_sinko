<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ekspedisi extends Model
{
    /**
     * @var string
     */
    protected $table = 'ekspedisi';

    /**
     * @var array
     */
    public function Provinsi()
    {
        return $this->belongsToMany(Provinsi::class, 'ekspedisi_provinsi');
    }
}
