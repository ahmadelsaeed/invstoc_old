<?php

namespace App\models\pages;

use App\models\attachments_m;
use File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class pages_m extends Model
{
    use SoftDeletes;

    protected $table = "pages";

    protected $primaryKey = "page_id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'cat_id','currency_id','event_datetime','importance_degree',
        'current_value','expected_value','previous_value','current_value_status',
        'page_price', 'small_img_id', 'big_img_id', 'page_slider',
        'page_type','page_url_old','page_url2_old','page_url3_old', 'related_pages', 'show_in_homepage',
        'show_in_menu','show_in_privacy','show_in_existing',
        'show_in_sidebar', 'hide_page', 'page_views', 'pdf_en', 'pdf_ar'
    ];

    static function get_pages($additional_where = "", $order_by = "" , $limit = "",$check_self_translates = false,$default_lang_id=1,$load_slider=false)
    {
        $results = DB::select("

            select
            page.*,
            page.show_in_homepage as 'show_in_homepage',
            page_trans.*,
            page.created_at as 'page_created_at',
            DATE(page.event_datetime) as 'event_date',
            DAY(page.event_datetime) as 'event_day',
            MONTH(page.event_datetime) as 'event_month',
            YEAR(page.event_datetime) as 'event_year',

            #currency
            currency.id,
            currency.cur_img,
            currency.cur_rate,
            currency.cur_sign,
            currency.cur_to,
            currency.last_date,
            currency.show_in_menu,
            currency_attach.path as 'currency_img_path',

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


            FROM `pages` as page
            inner join pages_translate as page_trans on (page.page_id = page_trans.page_id and page_trans.lang_id = $default_lang_id AND page_trans.deleted_at is null)

            LEFT OUTER join category child_cat on (page.cat_id = child_cat.cat_id and child_cat.deleted_at is null)
            LEFT OUTER join category_translate as child_cat_trans on (child_cat.cat_id = child_cat_trans.cat_id and child_cat_trans.lang_id = $default_lang_id AND child_cat_trans.deleted_at is null)

            LEFT OUTER join category parent_cat on (child_cat.parent_id = parent_cat.cat_id and parent_cat.deleted_at is null)
            LEFT OUTER join category_translate as parent_cat_trans on (parent_cat.cat_id = parent_cat_trans.cat_id and parent_cat_trans.lang_id = $default_lang_id AND parent_cat_trans.deleted_at is null)

            LEFT OUTER JOIN currency_rates as currency on (currency.id = page.currency_id)
            LEFT OUTER JOIN attachments currency_attach on (currency.cur_img = currency_attach.id)

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


    public function page_prices()
    {
        return $this->belongsToMany(
            'App\models\pages\page_select_currency_m',
            'page_select_currency',
            'page_id',
            'page_id'
        );
    }

}
