<?php

namespace App\models\group_workshop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class workshop_followers_m extends Model
{
    use SoftDeletes;

    protected $table = "workshop_followers";

    protected $dates = ["deleted_at"];
    protected $fillable = [
        'workshop_id', 'user_id'
    ];

}
