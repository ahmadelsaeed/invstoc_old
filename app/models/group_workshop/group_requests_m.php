<?php

namespace App\models\group_workshop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class group_requests_m extends Model
{

    use SoftDeletes;

    protected $table = "group_requests";
    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'group_id','user_id','created_at','updated_at'
    ];


}
