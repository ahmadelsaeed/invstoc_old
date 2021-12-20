<?php

namespace App\models\group_workshop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class group_members_m extends Model
{

    protected $table = "group_members";
    protected $primaryKey = "g_m_id";

    protected $fillable = [
        'group_id','user_id','user_role'
    ];

    public $timestamps=false;

}
