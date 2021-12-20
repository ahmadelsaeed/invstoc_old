<?php

namespace App\Http\Controllers\actions\posts;

use App\Events\notifications;
use App\Events\trending;
use App\models\notification_m;
use App\models\posts\comment_likes_m;
use App\models\posts\post_comments_m;
use App\models\posts\post_likes_m;
use App\models\posts\posts_m;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class likes extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }


    public function like_post(Request $request){
        $output=[];

        $post_id=clean($request->get("post_id"));
        $post_data=posts_m::findOrFail($post_id);
        $post_user_data=User::findOrFail($post_data->user_id);

        $post_like_obj=post_likes_m::where("post_id",$post_id)->
        where("user_id",$this->user_id);

        if(is_object($post_like_obj->get()->first())){
            $likes_deleted_count=$post_like_obj->get()->count();
            $post_like_obj->delete();

            $output["response"]="unliked";
            $output["post_likes_count"]=$post_data->post_likes_count-$likes_deleted_count;
            $post_data->update([
                "post_likes_count"=>$post_data->post_likes_count-$likes_deleted_count
            ]);

            #region trending

            if ($post_data->post_where == "workshop")
            {
                trending::event_action(
                    $target_id = $post_data->post_where_id,
                    $post_id = $post_data->post_id,
                    $action_type="unlike",
                    $target = "workshop"
                );
            }
            elseif ($post_data->post_where == "profile")
            {
                trending::event_action(
                    $target_id = $post_data->post_where_id,
                    $post_id = $post_data->post_id,
                    $action_type="unlike",
                    $target = "profile"
                );
            }


            #endregion

        }
        else{
            post_likes_m::create([
                "post_id"=>$post_id,
                "user_id"=>$this->user_id
            ]);
            $output["response"]="liked";
            $output["post_likes_count"]=$post_data->post_likes_count+1;
            $post_data->update([
                "post_likes_count"=>$post_data->post_likes_count+1
            ]);

            #region trending

            if ($post_data->post_where == "workshop")
            {
                trending::event_action(
                    $target_id = $post_data->post_where_id,
                    $post_id = $post_data->post_id,
                    $action_type="like",
                    $target = "workshop"
                );
            }
            elseif ($post_data->post_where == "profile")
            {
                trending::event_action(
                    $target_id = $post_data->post_where_id,
                    $post_id = $post_data->post_id,
                    $action_type="like",
                    $target = "profile"
                );
            }

            #endregion

            //add notification to post owner
            if($this->user_id!=$post_data->user_id){
                notifications::add_notification([
                    'not_title'=>"Liked your post",
                    'not_type'=>"like",
                    'not_link'=>"posts/".$post_user_data->full_name."/".$post_user_data->user_id."/".$post_data->post_id,
                    'not_from_user_id'=>$this->user_id,
                    'not_to_user_id'=>$post_data->user_id
                ]);
            }
        }

        echo json_encode($output);
    }

    public function like_comment(Request $request){
        $output=[];

        $comment_id=clean($request->get("comment_id"));
        $comment_data=post_comments_m::findOrFail($comment_id);

        $post_data=posts_m::findOrFail($comment_data->post_id);
        $post_data->user_data=User::findOrFail($post_data->user_id);

        $comment_like_obj=comment_likes_m::where("comment_id",$comment_id)->
        where("user_id",$this->user_id)->get()->first();


        if(is_object($comment_like_obj)){
            $comment_like_obj->delete();
            $output["response"]="unliked";
            $output["comment_likes_count"]=$comment_data->comment_likes_count-1;
            $comment_data->update([
                "comment_likes_count"=>$comment_data->comment_likes_count-1
            ]);

            #region trending

            if ($post_data->post_where == "workshop")
            {
                trending::event_action(
                    $target_id = $post_data->post_where_id,
                    $post_id = $post_data->post_id,
                    $action_type="unlike",
                    $target = "workshop"
                );
            }
            elseif ($post_data->post_where == "profile")
            {
                trending::event_action(
                    $target_id = $post_data->post_where_id,
                    $post_id = $post_data->post_id,
                    $action_type="unlike",
                    $target = "profile"
                );
            }

            #endregion

        }
        else{
            comment_likes_m::create([
                "comment_id"=>$comment_id,
                "user_id"=>$this->user_id
            ]);
            $output["response"]="liked";
            $output["comment_likes_count"]=$comment_data->comment_likes_count+1;
            $comment_data->update([
                "comment_likes_count"=>$comment_data->comment_likes_count+1
            ]);


            #region trending

            if ($post_data->post_where == "workshop")
            {
                trending::event_action(
                    $target_id = $post_data->post_where_id,
                    $post_id = $post_data->post_id,
                    $action_type="like",
                    $target = "workshop"
                );
            }
            elseif ($post_data->post_where == "profile")
            {
                trending::event_action(
                    $target_id = $post_data->post_where_id,
                    $post_id = $post_data->post_id,
                    $action_type="like",
                    $target = "profile"
                );
            }

            #endregion

            if($this->user_id!=$comment_data->user_id){
                notifications::add_notification([
                    'not_title'=>"Liked your comment",
                    'not_type'=>"like",
                    'not_link'=>"posts/".$post_data->user_data->full_name."/".$post_data->user_data->user_id."/".$post_data->post_id."?comment_id=".$comment_data->post_comment_id,
                    'not_from_user_id'=>$this->user_id,
                    'not_to_user_id'=>$comment_data->user_id
                ]);
            }

        }

        echo json_encode($output);
    }

    public function get_post_username_likes(Request $request){
        $output=[];
        $output["html"] = "";

        $post_id = intval(clean($request->get("post_id",0)));

        if ($post_id > 0)
        {
            $get_likes = post_likes_m::where("post_id",$post_id)->pluck('user_id')->all();
            if (count($get_likes))
            {
                $get_likes = implode(',',$get_likes);
                $get_users = User::get_users("
                    AND u.user_id in ($get_likes)
                ");
                $li_items = "";
                foreach($get_users as $key => $user_obj)
                {
                    $li_items .= \View::make("blocks.users_li_item")->with([
                        "user_obj" => $user_obj
                    ])->render();
                }
                $output["html"] = \View::make("blocks.users_ul_items")->with([
                    "li_items" => $li_items
                ])->render();;
            }
        }

        echo json_encode($output);
        return;
    }


}
