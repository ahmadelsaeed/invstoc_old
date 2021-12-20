<?php

namespace App\models\posts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class post_shares_m extends Model
{
    use SoftDeletes;

    protected $table = "post_shares";
    protected $primaryKey = "post_share_id";

    protected $dates = ["deleted_at"];
    protected $fillable = [
        'post_id', 'user_id'
    ];


}
