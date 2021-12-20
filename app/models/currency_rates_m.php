<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class currency_rates_m extends Model
{
    protected $table = "currency_rates";
    protected $primaryKey = "id";
    public $timestamps = false;

    protected $fillable = [
        'cur_img',"cur_to","cur_rate","last_date",'show_in_homepage','cur_sign','show_in_menu'
    ];


    static function get_all_currencies($additional_where = "",$return_obj="no")
    {

        $results = DB::select("
             select  cur.*
             
             #cur_img
             ,cur_img.path as cur_img_path, cur_img.title as cur_img_title
             ,cur_img.alt as cur_img_alt
             
             #joins
             from currency_rates as cur 
             LEFT OUTER JOIN attachments cur_img on (cur.cur_img = cur_img.id)
             
             #where
             $additional_where ");


        if($return_obj!="no"){

            if(is_array($results)&&count($results)){
                return $results[0];
            }
        }

        return $results;

    }

}
