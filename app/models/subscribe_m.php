<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class subscribe_m extends Model
{
    use SoftDeletes;

    protected $table = "subscribe";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        "email","last_message","message_viewed",
        "submit_msg","message_send"
    ];

}
