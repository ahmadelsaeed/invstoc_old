<?php

namespace App\Events;


use App\models\notification_m;
use App\User;
use Carbon\Carbon;

class notifications
{

    public static $max_notification_number=50;

    public static function get_notification_html($notification_objs,$current_user_timezone){
        $return_html="";

        foreach ($notification_objs as $key => $not_obj) {
            $return_html.=\View::make("blocks.notification_block")->with([
                "not_obj"=>$not_obj,
                "current_user_timezone"=>$current_user_timezone
            ])->render();
        }

        return $return_html;
    }

    public static function get_user_notification($user_id,$limit="20",$current_user_timezone){

//        $date_time = Carbon::now()->subHours(6)->toDateTimeString();
        //#AND note.created_at >= '$date_time'

        $all_notes_objs=notification_m::get_notifications(" 
            AND note.not_to_user_id=$user_id
            
            order by note.not_id desc
            limit $limit
        ");

        if(!isset_and_array($all_notes_objs)){
            return "There's no notifications for you";
        }

        return self::get_notification_html($all_notes_objs,$current_user_timezone);
    }

    public static function get_user_follower_notification($user_id,$limit="20",$current_user_timezone){

        $all_notes_objs=notification_m::get_notifications(" 
            AND note.not_to_user_id=$user_id
            AND note.not_type='follow'
            order by note.not_id desc
            limit $limit
        ");

        if(!isset_and_array($all_notes_objs)){
            return "There's no notifications for you";
        }

        return self::get_notification_html($all_notes_objs,$current_user_timezone);
    }

    public static function add_notification($data){
        date_default_timezone_set('UTC');


        $data["created_at"]=date("Y-m-d H:i:s");
        $data["updated_at"]=date("Y-m-d H:i:s");


        $not_obj=notification_m::create($data);

        $to_user_id = $data["not_to_user_id"];

        if (isset($data["get_users_data"]) && isset($data["get_users_data"][$to_user_id]) & isset($data["get_users_data"][$to_user_id][0]))
        {
            $to_user_data = $data["get_users_data"][$to_user_id][0];
        }
        else{
            $to_user_data = User::findORFail($to_user_id)->first();
        }

        $user_cache_notification=\Cache::get("user_cache_notification_".$to_user_id);

        if(!is_string($user_cache_notification)){
            $user_cache_notification="";
        }

        $user_cache_notification=json_decode($user_cache_notification,true);
        if(!isset_and_array($user_cache_notification)){
            $user_cache_notification=[];
        }

        $notes=
            notification_m::get_notifications(
                " AND note.not_id=$not_obj->not_id"
            );

        $user_cache_notification[$not_obj->not_id]=self::get_notification_html($notes,$to_user_data->timezone);

        if ($data['not_type'] == "follow")
        {
            User::findOrfail($to_user_id)->update([
                "not_seen_followers_notifications" => ($to_user_data->not_seen_followers_notifications + 1),
            ]);

            \Cache::put("user_cache_follow_notification_".$to_user_id,json_encode($user_cache_notification),60);
        }
        else{
            User::findOrfail($to_user_id)->update([
                "not_seen_all_notifications" => ($to_user_data->not_seen_all_notifications + 1),
            ]);
            \Cache::put("user_cache_notification_".$to_user_id,json_encode($user_cache_notification),60);
        }


        //remove all user notifications if exceeded max_notification_number
        if (false)
        {
            $rows=notification_m::
            where("not_to_user_id",$to_user_id)->
            limit(100)->
            offset(self::$max_notification_number)->
            orderBy("not_id","desc")->
            get();

            if(isset_and_array($rows->pluck("not_id")->all())){
                notification_m::destroy($rows->pluck("not_id")->all());
            }
        }

    }



}