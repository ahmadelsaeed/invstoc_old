<?php

namespace App\models\posts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class post_likes_m extends Model
{
    use SoftDeletes;

    protected $table = "post_likes";
    protected $primaryKey = "post_like_id";

    protected $dates = ["deleted_at"];
    protected $fillable = [
        'post_id', 'user_id'
    ];


}
