<?php

namespace App\Http\Controllers\front;

use App\models\category_m;
use App\models\pages\pages_m;
use App\models\comments;
use App\models\rate;
use App\models\users_or_pages_images_m;
use App\User;
use App\models\pages\pages_translate_m;
use DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use function GuzzleHttp\json_encode;

class articles extends Controller
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

        $this->data["articles"] = [];
        $this->data["rates"] = [];
        $this->data["arts_pagination"] = "";

        $arts_without_paginations = pages_m::
            join("pages_translate","pages.page_id","=","pages_translate.page_id")
            #->join("rate","pages.page_id","=","rate.page_id")
            ->where("pages_translate.page_title","<>","")
            ->where("pages_translate.lang_id","=",$this->lang_id)
            ->whereNull("pages_translate.deleted_at")
            ->where("pages.page_type","article")
            ->where("pages.hide_page",0)
            ->orderBy("pages.page_id","desc")->paginate(9);

        $this->data["arts_pagination"] = $arts_without_paginations;


        $get_news = $arts_without_paginations;
        if (count($get_news)) {
            $get_news_ids = convert_inside_obj_to_arr($get_news, "page_id");

           // Calulate article rate
            foreach ($get_news_ids as $rate_page_id) {
                $sum_rates = rate::where('page_id',$rate_page_id)->sum('value');
                $count_rates =rate::where('page_id',$rate_page_id)->count();
                if ($count_rates > 0) {
                $rates=$sum_rates/$count_rates;

                }else
                {
                    $rates = 0;
                }
                $this->data["rates"][$rate_page_id]=$rates;

            }

            $get_news_ids = implode(',', $get_news_ids);
            $this->data["articles"] = pages_m::get_pages(
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


        $this->data["meta_title"]=show_content($this->data["pages_seo"],"articles_meta_title");
        $this->data["meta_desc"]=show_content($this->data["pages_seo"],"articles_meta_description");
        $this->data["meta_keywords"]=show_content($this->data["pages_seo"],"articles_meta_keywords");

        if (is_object($current_user))
        {

          return view('front.subviews.articles.user_index',$this->data);
        }
        else{

         return view('front.subviews.articles.visitor_index',$this->data);
        }
    }

    public function view_article(Request $request, $news_id)
    {
        $articleId = $request->id;

        $current_user = $this->data["current_user"];

        $this->data["article_data"] = "";

         $news_data = pages_m::get_pages(
            "
                    AND page_trans.page_slug = '$articleId'
                    AND page_trans.page_title <> ''
                    AND page.page_type ='article'
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
                        AND page_trans.page_slug = '$articleId'
                        AND page_trans.page_title <> ''
                        AND page.page_type ='article'
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

        $this->data["article_data"] = $news_data[0];

        $this->data["meta_title"]=$this->data["article_data"]->page_meta_title;
        $this->data["meta_desc"]=$this->data["article_data"]->page_meta_desc;
        $this->data["meta_keywords"]=$this->data["article_data"]->page_meta_keywords;
        $this->data["og_description"]=$this->data["article_data"]->page_meta_desc;
        $this->data["og_title"]=$this->data["article_data"]->page_meta_title;
        $this->data["og_img"]=get_image_or_default($this->data["article_data"]->big_img_path);
        $this->data["og_url"]= url("articles/".$this->data["article_data"]->page_slug) ;

        $articles = pages_m::
            join("pages_translate","pages.page_id","=","pages_translate.page_id")
            ->where("pages_translate.page_title","<>","")
            ->where("pages_translate.lang_id","=",$this->lang_id)
            ->whereNull("pages_translate.deleted_at")
            ->where("pages.page_type","article")
            ->where("pages.hide_page",0)
            ->inRandomOrder()->limit(3)->get();
        $this->data["relted_article"]=$articles;

        $articleId=$news_data[0]->page_id;

        $comments=comments::join("users","comments.user_id","=","users.user_id")->
        select('comments.created_at AS comment_date', 'comments.*','users.*')->
        where('page_id',$articleId)->get();
        $article_comments=array();
        foreach($comments as $comment)
        {
            $comment_id=$comment['id'];
            $user_id=$comment['user_id'];

            $user_image = users_or_pages_images_m::join("attachments","attachment_id","=","attachments.id")
            ->where("user_id_or_users_pages_id",$user_id)
            ->orderBy("users_or_pages_image_id","desc")
            ->first();

            $article_comments[$comment_id]=$comment;
            $article_comments[$comment_id]['user_image']=$user_image['path'];
        }

        $this->data['comments']=$article_comments;

        $sum_rates = rate::where('page_id',$articleId)->sum('value');
        $count_rates =rate::where('page_id',$articleId)->count();
        if ($count_rates > 0) {
        $rates=$sum_rates/$count_rates;

        }else
        {
            $rates = 0;
        }

        $this->data['rates']=$rates;
        $this->data['count_rates']=$count_rates;




        if (is_object($current_user))
        {
            return view('front.subviews.articles.user_view_article',$this->data);
        }
        else{
            return view('front.subviews.articles.visitor_view_article',$this->data);
        }

    }

    public function add_comment(Request $request)
    {
        $comment = new comments ;
        $comment->page_id=$request->article_id;
        $comment->user_id=$request->user_id;
        $comment->comment=$request->comment_body;
        $comment->save();

        $this->data['comment']=$comment;

        // $output=[];
        // $output["return_html"]= \View::make("actions.article_comments")->with($this->data)->render();

       echo \View::make("actions.article_comments")->with($this->data)->render();
    }

    public function add_rate(Request $request)
    {
        $user_id=$request->user_id;
        $page_id=$request->article_id;
        $count_rates =rate::where('page_id',$page_id)->where('user_id',$user_id)->count();
        if ($count_rates <= 0) {
            $rate = new rate ;
            $rate->page_id=$page_id;
            $rate->user_id=$user_id;
            $rate->value=$request->value;
            $rate->save();
        }

    }
}
