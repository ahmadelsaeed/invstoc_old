<?php

namespace App\Events;


use App\models\category_m;
use App\models\pair_currency_m;
use App\models\posts\comment_likes_m;
use App\models\posts\post_comments_m;
use App\models\posts\posts_m;
use DB;
use Illuminate\Http\Request;

class comments_utilities
{

    public static $method_return_data=[];
    public static $comments_limit=5;

    public static function load_post_comments(Request $request,$this_user_id,$with_group_by_parent_comment_id=true,$comment_id=null,$parent_comment_id=0){

        //get post data
        $post_id=clean($request->get("post_id"));
        //get last or first loaded comment to get next or previous comments
        $last_comment_loaded=clean($request->get("last_comment_loaded","0"));

        $after_comment=clean($request->get("after_comment","0"));
        $before_comment=clean($request->get("before_comment","0"));

        $get_next_or_prev=clean($request->get("get_next_or_prev","next"));

        $post_data=posts_m::findOrFail($post_id);
        $check_next_comments=[];

        //load all post comments and group by
        $all_post_comments=
            post_comments_m::
            select(DB::raw(
                "
                    post_comments.*,
                    post_comments.created_at as 'comment_at',
                    users.full_name,
                    users.gender,
                    users.is_privet_account,
                    user_img.path as 'logo_path',
                    comment_img.path as 'comment_img_path'
                "))->
            join("users","users.user_id","=","post_comments.user_id")->
            leftjoin("attachments as user_img","user_img.id","=","users.logo_id")->
            leftjoin("attachments as comment_img","comment_img.id","=","post_comments.com_img_id")->
            where("post_id",$post_id);

        $check_next_comments=post_comments_m::where("post_id",$post_id);

        if($comment_id!=null){
            //make sure that comment's parent_post_comment_id=0
            $com_obj=post_comments_m::findOrFail($comment_id);
            if($com_obj->parent_post_comment_id>0){
                $comment_id=$com_obj->parent_post_comment_id;
            }

            $all_post_comments=$all_post_comments->where(function ($query)use($comment_id){
                $query->where("post_comment_id",$comment_id)->
                orWhere("parent_post_comment_id",$comment_id);
            })->get();

            //get first parent_comment in this post to show (show_previous_comments_btn)
            $first_parent_comment_id=post_comments_m::
            select("post_comment_id")->
            where("post_id",$post_id)->
            where("parent_post_comment_id","0")->
            limit(1)->get()->first();

            if(is_object($first_parent_comment_id)&&$first_parent_comment_id->post_comment_id!=$comment_id){
                self::$method_return_data["show_previous_comments_btn"]="yes";
                self::$method_return_data["previous_comment_id"]=$comment_id;
            }

            $check_next_comments=$check_next_comments->
            where("post_comment_id",">",$comment_id)->
            where("parent_post_comment_id","0")->
            get()->all();

        }
        else{

            $check_next_comments=$check_next_comments->
            where("parent_post_comment_id",$parent_comment_id);

            $all_post_comments=$all_post_comments->where("parent_post_comment_id",$parent_comment_id);
            if($get_next_or_prev=="next"){
                $all_post_comments=$all_post_comments->
                where("post_comment_id",">",$last_comment_loaded);

                $check_next_comments=$check_next_comments->
                where("post_comment_id",">",$last_comment_loaded);


            }
            else{
                if($after_comment>0){
                    $all_post_comments=$all_post_comments->
                    where("post_comment_id","<",$after_comment);

                    $check_next_comments=$check_next_comments->
                    where("post_comment_id","<",$after_comment);
                }

                if($before_comment>0){
                    $all_post_comments=$all_post_comments->
                    where("post_comment_id",">",$before_comment);

                    $check_next_comments=$check_next_comments->
                    where("post_comment_id",">",$before_comment);
                }
            }

            $check_next_comments=$check_next_comments->
            limit(self::$comments_limit)->
            offset(self::$comments_limit)->
            get()->all();

            $all_post_comments=$all_post_comments->
            limit(self::$comments_limit)->
            get();
        }


        //load this user comments likes
        $all_user_comment_likes=comment_likes_m::
        whereIn("comment_id",$all_post_comments->pluck("post_comment_id")->all())->
        where("user_id",$this_user_id)->
        get()->groupBy("comment_id")->all();

        if ($with_group_by_parent_comment_id){
            $all_post_comments=$all_post_comments->groupBy("parent_post_comment_id")->all();
        }

        self::$method_return_data["post_data"]=$post_data;
        self::$method_return_data["all_post_comments"]=$all_post_comments;
        self::$method_return_data["all_user_comment_likes"]=$all_user_comment_likes;
        self::$method_return_data["comments_limit"]=self::$comments_limit;
        self::$method_return_data["parent_comments_count"]=post_comments_m::where("post_id",$post_id)->where("parent_post_comment_id","0")->count();
        self::$method_return_data["check_next_comments"]=isset_and_array($check_next_comments);

        return self::$method_return_data;
    }

}