<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class sessions_m extends Model
{

    protected $table = "sessions";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $fillable = [
        'user_id', 'ip_address', 'user_agent', 'payload', 'last_activity'
    ];

}
