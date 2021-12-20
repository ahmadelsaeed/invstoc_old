<?php

namespace App\models\hashtags;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class hashtag_posts_m extends Model
{
    use SoftDeletes;

    protected $table = "hashtag_posts";
    protected $primaryKey = "hashtag_post_id";

    protected $dates = ["deleted_at"];
    protected $fillable = [
        'hashtag_id', 'post_id'
    ];


}
