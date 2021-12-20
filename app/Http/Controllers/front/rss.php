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


class rss extends Controller
{
	public  function all()
	{  
        
        //start  Get all News 
	    $news = pages_m::
        join("pages_translate","pages.page_id","=","pages_translate.page_id")
        ->where("pages_translate.page_title","<>","")
        ->where("pages_translate.lang_id","=",$this->lang_id)
        ->whereNull("pages_translate.deleted_at")
        ->where("pages.page_type","news")
        ->where("pages.hide_page",0)
        ->orderBy("pages.page_id","desc")->get(); 

        $get_news = $news;
        if (count($get_news)) {
            $get_news_ids = convert_inside_obj_to_arr($get_news, "page_id");
            $get_news_ids = implode(',', $get_news_ids);
            $allNews = pages_m::get_pages(
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
        // end get all news 

        //start  Get all Articles 
	    $articles = pages_m::
        join("pages_translate","pages.page_id","=","pages_translate.page_id")
        ->where("pages_translate.page_title","<>","")
        ->where("pages_translate.lang_id","=",$this->lang_id)
        ->whereNull("pages_translate.deleted_at")
        ->where("pages.page_type","article")
        ->where("pages.hide_page",0)
        ->orderBy("pages.page_id","desc")->get(); 

         $get_articles = $articles;
        if (count($get_articles)) {
            $get_news_ids = convert_inside_obj_to_arr($get_articles, "page_id");
            $get_news_ids = implode(',', $get_news_ids);
            $allArticales = pages_m::get_pages(
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
        // end get all Articles 

        
       // echo '<pre>'; var_dump($allArticales[15]->big_img_path);

        				
        $rssData= '<?xml version="1.0" encoding="UTF-8"?>';
		$rssData.= '<rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom">';
		$rssData.= '<channel>';
		$rssData.= "<title>Invstoc.com | Forex Trading, The first Social Media Forex and economic Website</title>
			 <link>https://invstoc.com/rss/all</link>
			 <description> The first global economic Networking site provides a professional working environment for traders in money exchanges, local and international bourse markets in terms of ensuring freedom of communication among traders around the world to benefit from the exchange of experiences and discuss all recent methods of global trading schools.  </description>
			 <language>en-US</language>
			 <copyright>Copyright 2019 Invstoc. All rights reserved.</copyright>
		     <lastBuildDate>". date('D, d M Y H:i:s ',time()) ."</lastBuildDate> ";

		// start dynamically generate News content here
         foreach ($allArticales as $article_data) 
         {
         	$rssData.= '<item>
  			              <title>'.$article_data->page_title.'</title>
  			              <media:title type="plain">'.$article_data->page_title.'</media:title>
  			              <media:content url="'.get_image_or_default($article_data->big_img_path).'" type="image/jpeg" medium="image"/>
			              <link>https://www.invstoc.com/articles/'.$article_data->page_id.'</link>	
			              <pubDate>'.date('D, d M Y H:i:s ',strtotime($article_data->created_at)).'</pubDate>       
		                </item>';
         }
		
        // end dynamically generate News content here

        // start dynamically generate Articles content here
         foreach ($allNews as $news_data) 
         {
         	$rssData.= '<item>
  			              <title>'.$news_data->page_title.'</title>
  			              <media:title type="plain">'.$news_data->page_title.'</media:title>
  			              <media:content url="'.get_image_or_default($news_data->big_img_path).'" type="image/jpeg" medium="image"/>
			              <link>https://www.invstoc.com/news/'.$news_data->page_id.'</link>	
			              <pubDate>'.date('D, d M Y H:i:s ',strtotime($news_data->created_at)).'</pubDate>       
		                </item>';
         }
		
        // end dynamically generate Articles content here

		$rssData.= '</channel>';
	    $rssData.= '</rss>';
		
		return response($rssData)->header('Content-Type', 'text/xml');
	}
 

 
}