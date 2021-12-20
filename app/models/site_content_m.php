<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class site_content_m extends Model
{
    //

    protected $table="site_content";

    protected $fillable=[
        "content_title","content_json","lang_id"
    ];

    public $timestamps = false;
}
