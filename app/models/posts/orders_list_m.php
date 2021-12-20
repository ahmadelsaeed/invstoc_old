<?php

namespace App\models\posts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class orders_list_m extends Model
{
    protected $table = "orders_list";
    protected $primaryKey = "list_id";
    protected $fillable = [
        'user_id', 'post_id'
    ];

    public $timestamps=false;


}
