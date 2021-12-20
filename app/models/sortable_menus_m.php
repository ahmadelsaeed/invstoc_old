<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class sortable_menus_m extends Model
{
    //

    protected $table="sortable_menus";
    protected $primaryKey="menu_id";

    protected $fillable = ["menu_title","menu_json","lang_id"];

    public $timestamps=false;
}
