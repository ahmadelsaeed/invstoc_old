<?php

namespace App\models\pages;

use App\models\attachments_m;
use File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class page_select_currency_m extends Model
{
    use SoftDeletes;

    protected $table = "page_select_currency";

    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'page_id','pair_currency_id','price'
    ];

    static function get_data($additional_where = "", $order_by = "" , $limit = "",$check_self_translates = false,$default_lang_id=1,$load_slider=false)
    {
        $results = DB::select("
            
            select 
            cur.price as 'cur_price',
            pair.pair_currency_name,
            pair.pair_currency_id,
            page.*,
            page_trans.*,
            page.created_at as 'page_created_at',


            #child cat data
            child_cat_trans.cat_id as child_cat_id,
            child_cat_trans.cat_name as child_cat_name,
            child_cat_trans.cat_slug as child_cat_slug,
            child_cat.parent_id as child_cat_parent_id,

            #parent cat data
            parent_cat_trans.cat_name as parent_cat_name,
            parent_cat_trans.cat_slug as parent_cat_slug,
            parent_cat_trans.cat_id as parent_cat_id,

            #small_img
            small_page_img.path as small_img_path, small_page_img.title as small_img_title, small_page_img.alt as small_img_alt
             
            #big_img
            ,big_page_img.path as big_img_path, big_page_img.title as big_img_title, big_page_img.alt as big_img_alt
             
            
            from page_select_currency as cur
            INNER JOIN pair_currency as pair on (cur.pair_currency_id = pair.pair_currency_id AND pair.deleted_at is NULL)
            INNER JOIN pages as page on (cur.page_id = page.page_id AND page.deleted_at is NULL)
            inner join pages_translate as page_trans on (page.page_id = page_trans.page_id and page_trans.lang_id = $default_lang_id)

            LEFT OUTER join category child_cat on (page.cat_id = child_cat.cat_id and child_cat.deleted_at is null)
            LEFT OUTER join category_translate as child_cat_trans on (child_cat.cat_id = child_cat_trans.cat_id and child_cat_trans.lang_id = $default_lang_id)

            LEFT OUTER join category parent_cat on (child_cat.parent_id = parent_cat.cat_id and parent_cat.deleted_at is null)
            LEFT OUTER join category_translate as parent_cat_trans on (parent_cat.cat_id = parent_cat_trans.cat_id and parent_cat_trans.lang_id = $default_lang_id)


            LEFT OUTER JOIN attachments small_page_img on (page.small_img_id = small_page_img.id)
            LEFT OUTER JOIN attachments big_page_img on (page.big_img_id = big_page_img.id)
    
            #where
            where page.deleted_at is null  $additional_where
             
            #order by
            $order_by
             
            #limit
            $limit
        
        ");

        if ((is_array($results) && count($results) == 1)||$load_slider)
        {
            foreach($results as $key => $page)
            {

                //get slider data
                $slider_ids = json_decode($page->page_slider);
                $page->slider_imgs = array();
                if (is_array($slider_ids)&&  count($slider_ids) >0) {

                    $slider_imgs = attachments_m::whereIn("id",$slider_ids)->get()->all();
                    $page->slider_imgs=$slider_imgs;
                }

            }
        }

        return $results;
    }

}
