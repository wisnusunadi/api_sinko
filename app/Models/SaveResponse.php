<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaveResponse extends Model
{
    protected $table = 'tbl_response';
    protected $fillable = ['tipe','url','parameter','response','method','created_at'];
    public $timestamps = false;

}
