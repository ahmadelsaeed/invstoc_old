<?php

namespace App\Http\Controllers\actions;

use App\Events\notifications;
use App\models\followers_m;
use App\models\posts\posts_m;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class updates extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public $delay="0";

    public function get_updates()
    {

        \Debugbar::disable();

        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache'); // recommended to prevent caching of event data.

        $data=[];

        #region notification

        $user_cache_notification=\Cache::get("user_cache_notification_".$this->user_id);
        if(!empty($user_cache_notification)){
            $data["notifications"]=json_decode($user_cache_notification);
            \Cache::put("user_cache_notification_".$this->user_id,"",60);
        }

        #endregion

        #region follow notification

        $user_cache_notification=\Cache::get("user_cache_follow_notification_".$this->user_id);
        if(!empty($user_cache_notification)){
            $data["follow_notifications"]=json_decode($user_cache_notification);
            \Cache::put("user_cache_follow_notification_".$this->user_id,"",60);
        }

        #endregion

        #region messages

        $user_cache_msgs=\Cache::get("user_cache_msgs_".$this->user_id);
        if(!empty($user_cache_msgs)){
            $data["msgs"]=json_decode($user_cache_msgs);
            \Cache::put("user_cache_msgs_".$this->user_id,"",60);
        }
        #endregion

        #region messages

        $user_cache_hot_orders=\Cache::get("user_hot_order_".$this->user_id);
        if(!empty($user_cache_hot_orders)){
            $data["hot_orders"]=json_decode($user_cache_hot_orders);
            \Cache::put("user_hot_order_".$this->user_id,"",60);
        }
        #endregion

        echo "retry: ".$this->delay."\n";
        echo "data: ".json_encode($data)."\n\n";
        ob_flush();
        flush();
    }


    public function load_notifications(){

        User::findOrfail($this->user_id)->update([
            "not_seen_all_notifications" => 0
        ]);

        $output["notifications_html"]=notifications::get_user_notification($this->user_id,notifications::$max_notification_number,$this->data["current_user"]->timezone);

        echo json_encode($output);
    }

    public function load_follow_notifications(){

        User::findOrfail($this->user_id)->update([
            "not_seen_followers_notifications" => 0
        ]);

        $output["notifications_html"]=notifications::get_user_follower_notification($this->user_id,notifications::$max_notification_number,$this->data["current_user"]->timezone);

        echo json_encode($output);
    }


    public function load_hot_orders(){

        $user_follow_users_ids=followers_m::
        where("from_user_id",$this->user_id)->
        get()->pluck("to_user_id")->all();

//        $user_follow_users_ids[]=$this->user_id;

            $get_posts = posts_m::
                select(DB::raw("
                        
                                posts.*,
                                pair.pair_currency_name,
                                cat_trans.cat_name,
                                user.user_id,
                                user.full_name
                                
                            "))
                ->join("users as user",function($join) use ($user_follow_users_ids)
                {
                    $join->on("user.user_id","=","posts.user_id")
                        ->whereNull("user.deleted_at")
                        ->whereIn("user.user_id",$user_follow_users_ids);
                })
                ->join("pair_currency as pair",function($join){
                    $join->on("pair.pair_currency_id","=","posts.pair_currency_id")
                        ->whereNull("pair.deleted_at");
                })
                ->join("category as cat",function($join){
                    $join->on("cat.cat_id","=","posts.cat_id")
                        ->whereNull("cat.deleted_at");
                })
                ->join("category_translate as cat_trans",function($join){
                    $join->on("cat.cat_id","=","cat_trans.cat_id")
                        ->whereNull("cat_trans.deleted_at")
                        ->where("cat_trans.lang_id","=",$this->lang_id);
                })
                ->where([
                    "posts.post_where"              => "profile",
                    "posts.post_or_recommendation"  => "recommendation",
                    "posts.hide_post"               => 0,
                    "posts.post_share_id"           => 0,
                    "posts.post_privacy"            => "public",
                ])
                ->limit(10)
                ->orderBy("posts.post_id","desc")->get();


        //$view_html = "<li class='divider'></li>";
        $view_html = "";


        foreach ($get_posts as $key => $post) {
            $view_html.=\View::make("blocks.hot_order_li")->with([
                "post_data" => $post,
                "poster_full_name"=>$post->full_name,
                "poster_user_id"=>$post->user_id,
                "post_id"=>$post->post_id,
                "created_at"=>$post->created_at,
                "user_homepage_keywords" => $this->data["user_homepage_keywords"],
                "user_timezone" => $this->data["current_user"]->timezone,
                "post_keywords" => $this->data["post_keywords"]
            ])->render();
        }

        $output["hot_orders_html"]=$view_html;
        echo json_encode($output);
    }

}
