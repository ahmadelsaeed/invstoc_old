<?php

namespace App\models\posts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class user_saved_posts_m extends Model
{
    use SoftDeletes;

    protected $table = "user_saved_posts";
    protected $primaryKey = "save_post_id";

    protected $dates = ["deleted_at"];
    protected $fillable = [
        'user_id', 'post_id'
    ];


}
