<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class langs_m extends Model
{

    use SoftDeletes;

    protected $table = "langs";

    protected $primaryKey = "lang_id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'lang_title',"lang_text","lang_img_id","lang_direction"
    ];

    static function get_all_langs($additional_where = "",$return_obj="no")
    {

        $langs = DB::select("
             select  lang.*
             
             #small_img
             ,lang_img.path as lang_img_path, lang_img.title as lang_img_title
             ,lang_img.alt as lang_img_alt
             
             #joins
             from langs as lang 
             LEFT OUTER JOIN attachments lang_img on (lang.lang_img_id = lang_img.id)
             
             #where
             where lang.deleted_at is null $additional_where ");


        if($return_obj!="no"){

           if(is_array($langs)&&count($langs)){
               return $langs[0];
           }
        }

        return $langs;

    }

}
