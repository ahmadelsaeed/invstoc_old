<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class settings_m extends Model
{

    protected $table = "settings";
    protected $primaryKey = "set_id";
    public $timestamps = false;
    protected $fillable = [
        'general_currency','rate','register_require_verification',
        'allow_delete_order','show_brokers_trending'
    ];

}
