<?php

namespace App\models\posts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class custom_users_post_m extends Model
{
    use SoftDeletes;

    protected $table = "custom_users_post";
    protected $primaryKey = "c_u_p_id";

    protected $dates = ["deleted_at"];
    protected $fillable = [
        'post_id', 'user_id'
    ];


}
