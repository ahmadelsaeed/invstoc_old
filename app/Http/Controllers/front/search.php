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


class search extends Controller
{
    //
    public $lang_seg_1=false;
    public function __construct(){
        parent::__construct();

        $url_seg_1=\Request::segment(1);
        $all_langs_titles=convert_inside_obj_to_arr($this->data["all_langs"],"lang_title");

        if(in_array($url_seg_1,$all_langs_titles)) {
            $this->lang_seg_1=true;
        }

    }

    public function index(){

        $slider_arr = array();
        $this->general_get_content(["search_page"],$slider_arr);

        $this->data["meta_title"]=$this->data["search_page"]->meta_title;
        $this->data["meta_desc"]=$this->data["search_page"]->meta_desc;
        $this->data["meta_keywords"]=$this->data["search_page"]->meta_keywords;


        $search_keyword=\Request::get("search_keyword");
        $search_keyword=urldecode($search_keyword);
        $search_keyword=clean($search_keyword);


        //pagination
        $trips_pagination=pages_translate_m::
        join('pages', 'pages.page_id', '=', 'pages_translate.page_id')->
        where("page_title","like","%$search_keyword%")->
        where("lang_id",$this->lang_id)->
        where("page_type",'trip')->
        paginate(10);

        $trips_pagination->appends(Input::all());

        $this->data["trips_pagination"]=$trips_pagination;

        $trips=[];
        if(isset_and_array($trips_pagination->all())){
            $trips_pagination=$trips_pagination->all();
            $trips_pagination=convert_inside_obj_to_arr($trips_pagination,"page_id");

            $trips=pages_m::get_pages(
                $additional_where = " AND page.page_id in (".implode(",",$trips_pagination).") AND page.hide_page=0 ",
                $order_by = "" ,
                $limit = "",
                $make_it_hierarchical=false,
                $default_lang_id=$this->lang_id
            );
        }
        $this->data["trips"]=$trips;


        return view('front.subviews.search',$this->data);
    }






}
