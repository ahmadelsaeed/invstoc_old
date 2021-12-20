<?php

namespace App\Http\Controllers\front;

//use Illuminate\Http\Request;
use App\models\langs_m;
use App\models\pages\page_tags_m;
use App\models\pages\pages_m;
use App\models\pages\pages_select_tags_m;
use App\models\pages\pages_translate_m;
use Redirect;
use Illuminate\Support\Facades\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class pages extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function show_item(){

        $current_user = $this->data["current_user"];
        $page_slug =\Request::segment(2);

        $page_data=pages_m::get_pages(
            "
                AND page.page_type = 'default'
                AND page.hide_page = 0
                AND page_trans.page_title <> ''
                AND page_trans.page_slug = '$page_slug'
            ",
            $order_by = "" ,
            $limit = "",
            $check_self_translates = false,
            $default_lang_id=$this->lang_id
        );

//        dump($page_data);
//        die();

        abort_if((!count($page_data)),404);
        $page_data = $page_data[0];


        //increase view number
        $page_obj=pages_m::find($page_data->page_id);
        $page_obj->update([
            "page_views"=>$page_obj->page_views+1
        ]);

//        dump($page_data);
        $this->data["page_data"] = $page_data;

        $this->data["meta_title"] = $page_data->page_meta_title;
        $this->data["meta_desc"] = $page_data->page_meta_desc;
        $this->data["meta_keywords"] = $page_data->page_meta_keywords;


        //return view('front.subviews.pages.index', $this->data);

         if (is_object($current_user))
        {

          return view('front.subviews.pages.user_index',$this->data);
        }
        else{

         return view('front.subviews.pages.visitor_index',$this->data);
        }
    }

    public function about_page()
    {

        $this->data["meta_title"] = $this->data["about_us_page"]->meta_title;
        $this->data["meta_desc"] = $this->data["about_us_page"]->meta_description;
        $this->data["meta_keywords"] = $this->data["about_us_page"]->meta_keywords;


        return view('front.subviews.pages.about_page', $this->data);



    }

}
