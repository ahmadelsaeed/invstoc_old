<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class email_settings_m extends Model
{
    protected $table = "email_settings";

    protected $fillable = [
        "sender_email","email_subject","email_body",
        "run_send","offset","limit"
    ];


}
