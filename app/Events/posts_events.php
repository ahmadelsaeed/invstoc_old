<?php

namespace App\Events;


use App\models\category_m;
use App\models\pair_currency_m;

class posts_events
{

    public static $method_return_data=[];

    public static function before_add_post(){

        $get_current_lang_id = \session('current_lang_id');
        if (!isset($get_current_lang_id))
        {
            $get_current_lang_id = 1;
        }

        //load all activities
        $all_childs_activities=category_m::get_all_cats("
                AND cat.cat_type='activity'
                AND cat.parent_id>0
            ",
            $order_by = "" ,
            $limit = "",
            $make_it_hierarchical=false,
            $default_lang_id=$get_current_lang_id
        );

        $all_childs_activities=collect($all_childs_activities);
        self::$method_return_data["all_childs_activities"]=$all_childs_activities;

        //load all pair_currencies
        self::$method_return_data["all_pair_currencies"]=pair_currency_m::get();

        return self::$method_return_data;
    }

}