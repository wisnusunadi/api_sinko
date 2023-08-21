<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoseriTGbj extends Model
{
    use HasFactory;

    protected $table = "t_gbj_noseri";

    protected $fillable = ['created_at', 'updated_at', 't_gbj_detail_id', 'noseri_id', 'status_id', 'layout_id', 'state_id', 'jenis', 'waktu_tf', 'transfer_by', 'created_by'];


    function seri()
    {
        return $this->belongsTo(NoseriBarangJadi::class, 'noseri_id');
    }

}
