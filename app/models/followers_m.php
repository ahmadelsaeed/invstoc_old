<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class followers_m extends Model
{
    use SoftDeletes;

    protected $table = "followers";
    protected $primaryKey = "follower_id";

    protected $dates = ["deleted_at"];
    protected $fillable = [
        'from_user_id', 'to_user_id'
    ];

}
