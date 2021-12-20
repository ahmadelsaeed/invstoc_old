<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class pair_currency_m extends Model
{
    protected $table = "pair_currency";
    protected $primaryKey = "pair_currency_id";
    public $timestamps = false;

    protected $fillable = [
       'pair_currency_name'
    ];


}
