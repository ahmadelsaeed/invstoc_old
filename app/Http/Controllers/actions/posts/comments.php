<?php

namespace App\Http\Controllers\actions\posts;

use App\Events\comments_utilities;
use App\Events\notifications;
use App\Events\trending;
use App\models\notification_m;
use App\models\posts\comment_likes_m;
use App\models\posts\post_comments_m;
use App\models\posts\posts_m;
use App\User;
use DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class comments extends Controller
{

    public $comments_limit;

    public function __construct()
    {
        parent::__construct();
        $this->comments_limit=comments_utilities::$comments_limit;
    }

    public function load_post_comments(Request $request){
        $output=[];

        $this->data=array_merge($this->data,comments_utilities::load_post_comments($request,$this->user_id));

        $output["return_html"]=\View::make("actions.posts.view.components.comments_section")->with($this->data)->render();

        echo json_encode($output);
    }

    public function load_more_comments(Request $request){
        $output=[];

        $parent_comment_id=clean($request->get("comment_id","0"));

        $this->data=array_merge(
            $this->data,
            comments_utilities::load_post_comments($request,$this->user_id,false,null,$parent_comment_id)
        );

        $all_post_comments=$this->data["all_post_comments"];

        $output["return_html"]="";
        foreach($all_post_comments as $parent_comment){
            $this->data["parent_comment"]=$parent_comment;
            $output["return_html"].=\View::make("actions.posts.view.components.parent_comments")->with($this->data)->render();
        }


        if(!$this->data["check_next_comments"]){
            $output["remove_load_more"]="";
        }

        echo json_encode($output);
    }

    public function add_comment(Request $request){

        $post_id=clean($request->get("post_id"));
        $comment_id=clean($request->get("comment_id"));
        $comment_body=trim($request->get("comment_body"));
        $comment_obj="";

        $com_img_id=0;
        if($request->hasFile("comment_file")){
            $com_img_id=$this->general_save_img(
                $request,
                $item_id=null,
                $img_file_name="comment_file",
                $new_title="",
                $new_alt="",
                $upload_new_img_check="",
                $upload_file_path="",
                $width="",
                $height="",
                $photo_id_for_edit=0,
                $ext_arr=["jpg","png","jpeg","gif","JPG","PNG","JPEG","GIF"]
            );
        }

        if(empty($comment_body)&&!$request->hasFile("comment_file")){
            return "will not comment";
        }

        if($comment_id=="undefined"||$comment_id=="0"){
            $comment_id=0;
        }
        else{
            $comment_obj=post_comments_m::findOrFail($comment_id);
        }

        $post_data=posts_m::findOrFail($post_id);
        $post_user_data=User::findOrFail($post_data->user_id);

        $created_comment=post_comments_m::create([
            'post_id'=>$post_id,
            'user_id'=>$this->user_id,
            'parent_post_comment_id'=>$comment_id,
            'com_img_id'=>$com_img_id,
            'post_comment_body'=>$comment_body,
            'hide_post_comment'=>0,
            'comment_likes_count'=>"0",
            'comment_replies_count'=>"0"
        ]);

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
            where("post_comment_id",$created_comment->post_comment_id)->
            get()->first();

        if(is_object($comment_obj)){
            $comment_obj->update([
                "comment_replies_count"=>$comment_obj->comment_replies_count+1
            ]);

            $output["comment_replies_count"]=$comment_obj->comment_replies_count;
        }

        $post_data->update([
            "post_comments_count"=>$post_data->post_comments_count+1
        ]);

        $output["post_comments_count"]=$post_data->post_comments_count;
        $output["commenter_id"]=$this->user_id;

        $this->data["post_data"]=$post_data;
        $this->data["parent_comment"]=$all_post_comments;

        $output["return_html"]=
        \View::make("actions.posts.view.components.parent_comments")->
        with($this->data)->render();


        //add notification to post owner
        if($this->user_id!=$post_data->user_id){
            notifications::add_notification([
                'not_title'=>"commented to your post",
                'not_type'=>"comment",
                'not_link'=>"posts/".$post_user_data->full_name."/".$post_user_data->user_id."/".$post_data->post_id."?comment_id=".$created_comment->post_comment_id,
                'not_from_user_id'=>$this->user_id,
                'not_to_user_id'=>$post_data->user_id
            ]);
        }

        //add notification to comment owner if there is a reply at it
        if($created_comment->parent_post_comment_id>0){
            $parent_comment_obj=post_comments_m::findOrFail($created_comment->parent_post_comment_id);

            if($this->user_id!=$parent_comment_obj->user_id){
                notifications::add_notification([
                    'not_title'=>"Replied at your comment",
                    'not_type'=>"comment",
                    'not_link'=>"posts/".$post_user_data->full_name."/".$post_user_data->user_id."/".$post_data->post_id."?comment_id=".$created_comment->post_comment_id,
                    'not_from_user_id'=>$this->user_id,
                    'not_to_user_id'=>$parent_comment_obj->user_id
                ]);
            }

        }

        #region trending

        if ($post_data->post_where == "workshop")
        {
            trending::event_action(
                $target_id = $post_data->post_where_id,
                $post_id = $post_data->post_id,
                $action_type="comment",
                $target = "workshop"
            );
        }
        elseif ($post_data->post_where == "profile")
        {
            trending::event_action(
                $target_id = $post_data->post_where_id,
                $post_id = $post_data->post_id,
                $action_type="comment",
                $target = "profile"
            );
        }

        #endregion

        echo json_encode($output);
    }

    public function edit_comment(Request $request){
        $output=[];

        //get post data
        $comment_id=clean($request->get("comment_id"));
        $comment_data=
            post_comments_m::
            select(DB::raw(
                "
                    post_comments.*,
                    post_comments.created_at as 'comment_at',
                    comment_img.path as 'comment_img_path'
                "))->
            leftjoin("attachments as comment_img","comment_img.id","=","post_comments.com_img_id")->
            where("post_comment_id",$comment_id)->get()->first();

        if(!is_object($comment_data))return abort(404);

        if($comment_data->user_id!=$this->user_id){
            return;
        }

        $this->data["comment_data"]=$comment_data;
        $output["return_html"]=\View::make("actions.posts.edit_comment_modal")->with($this->data)->render();

        echo json_encode($output);
    }

    public function post_edit_comment(Request $request){
        $output=[];

        //get post data
        $comment_id=clean($request->get("comment_id"));
        $comment_body=trim(clean($request->get("comment_body")));
        $comment_data=post_comments_m::findOrFail($comment_id);

        if(empty($comment_body)){
            return "will not comment";
        }

        if($comment_data->user_id!=$this->user_id){
            return;
        }

        $com_img_id=0;

        if($request->get("keep_image")=="keep_image"){
            $com_img_id=$comment_data->com_img_id;
        }

        if($request->hasFile("comment_file")){
            $com_img_id=$this->general_save_img(
                $request,
                $item_id=null,
                $img_file_name="comment_file",
                $new_title="",
                $new_alt="",
                $upload_new_img_check="",
                $upload_file_path="",
                $width="",
                $height="",
                $photo_id_for_edit=0,
                $ext_arr=["jpg","png","jpeg","gif","JPG","PNG","JPEG","GIF"]
            );
        }

        $comment_data->update([
            "post_comment_body"=>$comment_body,
            "com_img_id"=>$com_img_id
        ]);

        $original_obj=
            post_comments_m::
            select(DB::raw(
                "
                    post_comments.*,
                    post_comments.created_at as 'comment_at',
                    comment_img.path as 'comment_img_path'
                "))->
            leftjoin("attachments as comment_img","comment_img.id","=","post_comments.com_img_id")->
            where("post_comment_id",$comment_id)->get()->first();


        $output["return_html"]=
            \View::make("actions.posts.view.components.comment_div_itsef")->
            with(["original_obj"=>$original_obj])->render();

        echo json_encode($output);
    }

    public function delete_comment(Request $request){
        $output=[];

        $comment_id=$request->get("comment_id");
        $comment_data=post_comments_m::findOrFail($comment_id);
        $post_data=posts_m::findOrFail($comment_data->post_id);


        if(!in_array($this->user_id,[$comment_data->user_id,$post_data->user_id])){
            return;
        }

        //check if this comment is reply then reduce post_comments_count for parent comment
        if($comment_data->parent_post_comment_id>0){
            $parent_comment=post_comments_m::findOrFail($comment_data->parent_post_comment_id);
            $parent_comment->update([
                "comment_replies_count"=>($parent_comment->comment_replies_count-1)
            ]);
        }

        $comment_data->delete();

        //if this comment is parent of other comments remove them too.
        $child_comments=post_comments_m::where("parent_post_comment_id",$comment_id)->get();

        $post_data->update([
            "post_comments_count"=>$post_data->post_comments_count-(1+$child_comments->count())
        ]);

        post_comments_m::where("parent_post_comment_id",$comment_id)->delete();

        #region trending

        if ($post_data->post_where == "workshop")
        {
            trending::event_action(
                $target_id = $post_data->post_where_id,
                $post_id = $post_data->post_id,
                $action_type="uncomment",
                $target = "workshop"
            );
        }
        elseif ($post_data->post_where == "profile")
        {
            trending::event_action(
                $target_id = $post_data->post_where_id,
                $post_id = $post_data->post_id,
                $action_type="uncomment",
                $target = "profile"
            );
        }

        #endregion


        $output["post_comments_count"]=$post_data->post_comments_count;
        $output["deleted"]="yes";
        echo json_encode($output);
    }

    public function get_post_username_comments(Request $request){
        $output=[];
        $output["html"] = "";

        $post_id = intval(clean($request->get("post_id",0)));

        if ($post_id > 0)
        {
            $get_likes = post_comments_m::where("post_id",$post_id)->pluck('user_id')->all();
            if (count($get_likes))
            {
                $get_likes = array_unique($get_likes);
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
