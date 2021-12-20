<?php

namespace App\Http\Controllers\front;

use App\models\category_m;
use App\models\pages\pages_m;
use App\models\pages\pages_translate_m;
use DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;


class news extends Controller
{
    //
    public $lang_seg_1=false;
    public function __construct(){
        parent::__construct();
        //$this->middleware("check_user");
        $url_seg_1=\Request::segment(1);
        $all_langs_titles=convert_inside_obj_to_arr($this->data["all_langs"],"lang_title");

        if(in_array($url_seg_1,$all_langs_titles)) {
            $this->lang_seg_1=true;
        }

    }

    public function index()
    {

        $current_user = $this->data["current_user"];

        $this->data["news"] = [];
        $this->data["news_pagination"] = "";

        $news_without_paginations = pages_m::
            join("pages_translate","pages.page_id","=","pages_translate.page_id")
            ->where("pages_translate.page_title","<>","")
            ->where("pages_translate.lang_id","=",$this->lang_id)
            ->whereNull("pages_translate.deleted_at")
            ->where("pages.page_type","news")
            ->where("pages.hide_page",0)
            ->orderBy("pages.page_id","desc")->paginate(9);

        $this->data["news_pagination"] = $news_without_paginations;

        $get_news = $news_without_paginations;
        if (count($get_news)) {
            $get_news_ids = convert_inside_obj_to_arr($get_news, "page_id");
            $get_news_ids = implode(',', $get_news_ids);
            $this->data["news"] = pages_m::get_pages(
                "
                    AND page.page_id in ($get_news_ids)
                    AND page_trans.page_title <> ''
                ",
                $order_by = " order by page.page_id desc ",
                $limit = "",
                $check_self_translates = false,
                $default_lang_id = $this->lang_id
            );
        }


        $this->data["meta_title"]=show_content($this->data["pages_seo"],"news_meta_title");
        $this->data["meta_desc"]=show_content($this->data["pages_seo"],"news_meta_description");
        $this->data["meta_keywords"]=show_content($this->data["pages_seo"],"news_meta_keywords");


        if (is_object($current_user))
        {
            return view('front.subviews.news.user_index',$this->data);
        }
        else{
            return view('front.subviews.news.visitor_index',$this->data);
        }
    }

    public function view_news(Request $request, $news_id)
    {
        $newsId = $request->id;
        $current_user = $this->data["current_user"];

        $this->data["news_data"] = "";

          $news_data = pages_m::get_pages(
            "
                    AND page_trans.page_slug = '$newsId'
                    AND page_trans.page_title <> ''
                    AND page.page_type ='news'
                ",
            $order_by = " ",
            $limit = "",
            $check_self_translates = false,
            $default_lang_id = $this->lang_id
        );

         if (!count($news_data))
         {
                $news_data = pages_m::get_pages(
                "
                        AND page_trans.page_slug = '$newsId'
                        AND page_trans.page_title <> ''
                        AND pages.page_type ='news'
                    ",
                $order_by = " ",
                $limit = "",
                $check_self_translates = false,
                $default_lang_id = $this->lang_id
             );
             if (!count($news_data)) {
              return abort(404);
             }

         }

        $this->data["news_data"] = $news_data[0];


        $this->data["meta_title"]=$this->data["news_data"]->page_meta_title;
        $this->data["meta_desc"]=$this->data["news_data"]->page_meta_desc;
        $this->data["meta_keywords"]=$this->data["news_data"]->page_meta_keywords;

        $news = pages_m::
        join("pages_translate","pages.page_id","=","pages_translate.page_id")
        ->where("pages_translate.page_title","<>","")
        ->where("pages_translate.lang_id","=",$this->lang_id)
        ->whereNull("pages_translate.deleted_at")
        ->where("pages.page_type","news")
        ->where("pages.hide_page",0)
        ->inRandomOrder()->limit(3)->get();

    $this->data["relted_news"]=$news;

        if (is_object($current_user))
        {
            return view('front.subviews.news.user_view_news',$this->data);
        }
        else{
            return view('front.subviews.news.visitor_view_news',$this->data);
        }

    }

}
