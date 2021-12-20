<?php

namespace App\models\posts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class comment_likes_m extends Model
{
    protected $table = "comment_likes";
    protected $primaryKey = "cl_id";
    protected $fillable = [
        'comment_id', 'user_id'
    ];

    public $timestamps=false;


}
