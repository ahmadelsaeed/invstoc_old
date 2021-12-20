<?php

namespace App\models\hashtags;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class hashtags_m extends Model
{
    use SoftDeletes;

    protected $table = "hashtags";
    protected $primaryKey = "hashtag_id";

    protected $dates = ["deleted_at"];
    protected $fillable = [
        'hashtag_name', 'owner_id'
    ];


}
