<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class category_m extends Model
{
    use SoftDeletes;

    protected $table = "category";

    protected $primaryKey = "cat_id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'big_img_id', 'small_img_id','pdf_id','cat_slider', 'cat_type',
        'parent_id', 'cat_order', 'hide_cat', 'show_in_menu', 'cat_icon','cat_views', 'slug', 'meta_title', 'meta_description', 'meta_keywords'
    ];

    static function get_all_cats($additional_where = "", $order_by = "" , $limit = "",$make_it_hierarchical=false,$default_lang_id=1)
    {

        $cats = DB::select("

            SELECT cat.*,cat_translate.*,

            big_img.path as 'big_img_path',
            big_img.alt as 'big_img_alt',
            big_img.title as 'big_img_title',

            small_img.path as 'small_img_path' ,
            small_img.alt as 'small_img_alt' ,
            small_img.title as 'small_img_title',

            pdf_book.path as 'pdf_path',

            ifnull(parent_cat_trans.cat_id ,0) as 'parent_cat_id',
            ifnull(parent_cat_trans.cat_name ,0) as 'parent_cat_name',
            ifnull(parent_cat_trans.cat_slug ,0) as 'parent_cat_slug',

            concat(parent_cat_trans.cat_name,' - ',cat_translate.cat_name) as 'parent_cat_name_and_child_cat_name'

            FROM `category` as cat

            inner join category_translate as cat_translate on (cat.cat_id=cat_translate.cat_id AND cat_translate.lang_id=$default_lang_id)


            LEFT OUTER JOIN category as parent_cat on (cat.parent_id = parent_cat.cat_id)
            LEFT OUTER join category_translate as parent_cat_trans on (parent_cat.cat_id = parent_cat_trans.cat_id and parent_cat_trans.lang_id = $default_lang_id)

            left outer join attachments as small_img on small_img.id=cat.small_img_id
            left outer join attachments as big_img on big_img.id=cat.big_img_id
            left outer join attachments as pdf_book on pdf_book.id=cat.pdf_id


            #where
            where cat.deleted_at is null $additional_where

            #order by
            $order_by

            #limit
            $limit "
        );



        $hierarchical_arr=array();
        if ($make_it_hierarchical==true) {
            foreach ($cats as $key => $cat) {

                if ($cat->parent_id>0) {
                    $hierarchical_arr[$cat->parent_cat_id]["level_data"]=array(
                        "item_id"=>$cat->parent_cat_id,
                        "item_name"=>$cat->parent_cat_name,
                        "item_slug"=>$cat->parent_cat_slug
                    );


                    $hierarchical_arr[$cat->parent_cat_id]["level_childs"][$cat->cat_id]["level_data"]=array(
                        "item_id"=>$cat->cat_id,
                        "item_name"=>$cat->cat_name,
                        "item_slug"=>$cat->parent_cat_slug."/".$cat->cat_slug
                    );
                }
            }

            return $hierarchical_arr;
        }


        if (is_array($cats) && count($cats) == 1)
        {
            foreach($cats as $key => $cat)
            {

                //get slider data
                $slider_ids = json_decode($cat->cat_slider);
                $cat->slider_imgs = array();
                if (is_array($slider_ids)&&  count($slider_ids) >0) {

                    $slider_imgs = attachments_m::whereIn("id",$slider_ids)->get()->all();
                    $cat->slider_imgs=$slider_imgs;
                }


            }
        }

        return $cats;

    }




}
