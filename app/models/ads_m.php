<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ads_m extends Model
{
    use SoftDeletes;

    protected $table = "ads";
    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];
    protected $fillable = [
        'ads_title', 'page_name', 'ad_img', 'ad_link', 'ad_script', 'ad_show', 'position'
    ];

    static function get_ads_img($additional_where = "")
    {
        $res = DB::select("
            select ad.*
            
            #ad_img
            ,ad_img.path as ad_img_path, ad_img.title as ad_img_title , ad_img.alt as ad_img_alt
            
            from ads as ad
            LEFT JOIN attachments as ad_img on (ad.ad_img = ad_img.id)
            
            where ad.deleted_at is NULL $additional_where
            
        ");

        return $res;
    }
}
