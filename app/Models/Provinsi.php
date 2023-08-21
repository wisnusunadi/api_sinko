<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    /**
     * @var string
     */
    protected $table = 'provinsi';

    /**
     * @var array
     */

    public function Ekspedisi()
    {
        return $this->belongsToMany(Ekspedisi::class, 'ekspedisi_provinsi');
    }
}
