<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class category_translate_m extends Model
{


    use SoftDeletes;

    protected $table = "category_translate";

    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'cat_id','cat_name','owner_name', 'cat_slug','cat_short_desc', 'cat_body',
        'cat_meta_title', 'cat_meta_desc', 'cat_meta_keywords', 'lang_id'
    ];


    public static function get_all_translate_cats($additional_where = "", $order_by = "" , $limit = ""){
        return DB::select("
            select * from category_translate as cat_t
            inner join category as cat on cat.cat_id=cat_t.cat_id
            WHERE cat_t.deleted_at is NULL $additional_where $order_by $limit
        ");

    }


}
