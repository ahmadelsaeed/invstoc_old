<?php

namespace App\models\posts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class post_comments_m extends Model
{
    use SoftDeletes;

    protected $table = "post_comments";
    protected $primaryKey = "post_comment_id";

    protected $dates = ["deleted_at"];
    protected $fillable = [
        'post_id', 'user_id', 'parent_post_comment_id','com_img_id',
        'post_comment_body', 'hide_post_comment',
        'comment_likes_count','comment_replies_count'
    ];


}
