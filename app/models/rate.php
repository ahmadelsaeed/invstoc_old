<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class rate extends Model
{   

  //  use SoftDeletes;
    protected $table = "rate"; 
    // public function user()
    // {   
    //     //User_id 
    // 	return $this->belongsTo(User::class);
    // }
}
