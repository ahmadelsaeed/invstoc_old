<?php

namespace App\Http\Controllers\actions\posts;

use App\Events\comments_utilities;
use App\Jobs\SendGeneralNotification;
use App\models\attachments_m;
use App\models\category_m;
use App\models\followers_m;
use App\models\group_workshop\group_members_m;
use App\models\group_workshop\groups_m;
use App\models\group_workshop\workshop_followers_m;
use App\models\group_workshop\workshops_m;
use App\models\hashtags\hashtag_posts_m;
use App\models\hashtags\hashtags_m;
use App\models\pair_currency_m;
use App\models\posts\comment_likes_m;
use App\models\posts\orders_list_m;
use App\models\posts\post_comments_m;
use App\models\posts\post_likes_m;
use App\models\posts\post_shares_m;
use App\models\posts\posts_m;
use App\models\posts\user_saved_posts_m;
use App\models\users_or_pages_images_m;
use App\User;
use Carbon\Carbon;
use DB;
use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class posts extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public $recommendation_over_time=10;

    public function add_post(Request $request)
    {
        if (!(posts_m::check_unclosed_orders($this->user_id))){
            return abort(404);
        }

        //check if it will post at group check if this user if is member in group
        if($request->get("post_where")=="group"){
            $group_id=$request->get("post_where_id");

            $group_obj=groups_m::findOrFail($group_id);

            //check if this user is member in it
            $group_member=group_members_m::
            where("group_id",$group_id)->
            where("user_id",$this->user_id)->get()->first();

            if(!is_object($group_member)){
                abort(404);
            }

            if(!(
                $group_obj->group_post_options=="1"||
                ($group_obj->group_post_options=="0"&&$group_obj->group_owner_id==$this->user_id)||
                ($group_obj->group_post_options=="2"&&$group_member->user_role=="admin")
            )){
                abort(404);
            }
        }

        //if post id is sent in request then this is an edit operation
        $post_id=clean($request->get("post_id","0"));
        $share_post=clean($request->get("share_post","0"));
        if($share_post=="undefined"){
            $share_post="0";
        }

        if($post_id!=0){
            $edit_post_data=posts_m::findOrFail($post_id);

            if($share_post=="0"){
                if($edit_post_data->user_id!=$this->user_id){
                    return;
                }
            }

            $created_post=$edit_post_data;
        }

        //post have privacy setting like only me ,public ,to specific followers

        $post_text=trim(clean($request->get("post_text","")));
        $posts_youtube_links=trim(clean($request->get("posts_youtube_links")));

        $post_files=$this->general_save_slider(
            $request,
            $field_name="post_files",
            $width=0,
            $height=0,
            $new_title_arr="",
            $new_alt_arr="",
            $json_values_of_slider="",
            $old_title_arr="",
            $old_alt_arr="",
            $path="",
            $ext_arr=["jpg","png","jpeg","gif","JPG","PNG","JPEG","GIF"]
        );

        if(empty($post_text)&&!isset_and_array($post_files)){
            btm_dump("not posted");
            return;
        }

        //get hashtags
        $hashtags_words=[];

        $post_text = str_replace("\u00a0", "&nbsp", $post_text);

        if(!empty($post_text)){
            //check if # is found in post text
            $hashtag_last_index=0;

            while(strpos($post_text,"#",$hashtag_last_index)>=0){
                $hashtag_last_index=strpos($post_text,"#",$hashtag_last_index);
                $next_space=strpos($post_text," ",$hashtag_last_index);
                $next_newline=strpos($post_text,"\n",$hashtag_last_index);

                if($hashtag_last_index===false){
                    break;
                }

                if($next_space===false&&$next_newline===false){
                    $hashtags_words[]=trim(substr($post_text,$hashtag_last_index,strlen($post_text)-$hashtag_last_index));
                    break;
                }

                if($next_space===false){
                    $next_space=0;
                }

                if($next_newline===false){
                    $next_newline=0;
                }

                if($next_space==0&&$next_newline==0){
                    break;
                }

                if($next_space>$next_newline&&$next_newline>0){
                    $next_space=$next_newline;
                }

                if($next_space==0){
                    $next_space=$next_newline;
                }


                $hashtags_words[]=trim(substr($post_text,$hashtag_last_index,$next_space-$hashtag_last_index));

                $hashtag_last_index=$next_space+1;
            }

        }

        if($post_id>0&&$share_post=="0"){
            $old_post_imgs=json_decode($request->get("old_post_imgs",""),true);

            if(isset_and_array($old_post_imgs)){
                if(!is_array($post_files)){
                    $post_files=[];
                }

                $post_files=array_merge($old_post_imgs,$post_files);
            }
        }


        $post_type="text";
        if(isset_and_array($post_files)){
            if(count($post_files)==1){
                $post_type="photo";
            }
            elseif(count($post_files)>1){
                $post_type="slider";
                $output["contain_slider"] = true;
            }
        }

        $post_is_approved="1";
        $order_expected_price=0;
        $order_days_number=0;
        $take_profit=0;
        $stop_loss=0;

        if($request->get("post_or_recommendation")=="recommendation"){
            $post_is_approved="0";

            $order_expected_price=$request->get("order_expected_price");
            $take_profit=$request->get("order_take_profit");
            $stop_loss=$request->get("order_stop_loss");

//            $order_days_number=(int)$request->get("order_days_number")+$this->recommendation_over_time;

            if(!($order_expected_price>0)){
                $order_expected_price=0;
            }

//            if(!($order_days_number>0)){
//                $order_days_number=0;
//            }
        }

        $recommendation_end_date=Carbon::now()->addDays($order_days_number)->toDateTimeString();

        if($post_id==0){
            $created_post=posts_m::create([
                'user_id'=>$this->user_id,
                'post_where'=>clean($request->get("post_where","profile")),
                'post_where_id'=>clean($request->get("post_where_id",$this->user_id)),
                'post_or_recommendation'=>clean($request->get("post_or_recommendation")),
                'post_type'=>$post_type,
                //'cat_id'=>clean($request->get("cat_id")),
                'pair_currency_id'=>clean($request->get("pair_currency_id")),
                'sell_or_buy'=>clean($request->get("sell_or_buy")),
                'post_body'=>$post_text,
                'post_files'=>json_encode($post_files),
                'post_privacy'=>"public",
                "post_is_approved"=>$post_is_approved,
                "posts_youtube_links"=>$posts_youtube_links,
                "expected_price"=>$order_expected_price,
                "take_profit"=>$take_profit,
                "stop_loss"=>$stop_loss,
                "closed_price"=>"0",
                "recommendation_end_date"=>date("Y-m-d",strtotime($recommendation_end_date))
            ]);


            if ($request->get("post_where")=="workshop")
            {

                $workshop_id = clean($request->get("post_where_id",$this->user_id));
                $post_owner_id = $this->user_id;

                $return_users = $this->get_work_shop_followers($workshop_id,$post_owner_id);

                $workshop_data = workshops_m::where("workshop_id",$workshop_id)->first();

                if (count($return_users) && is_object($workshop_data))
                {

                    $delay_time = 0;
                    foreach($return_users as $key => $users)
                    {

                        #region create notification job
                            $not_title = "New Post Added on workshop >> $workshop_data->workshop_name";
                            $not_type = "post";
                            $not_link = "workshop/".urlencode($workshop_data->workshop_name)."/".$workshop_data->workshop_id;
                            $from_user_id = $post_owner_id;

                            $users = collect($users)->pluck('user_id')->all();
                            $job = (new SendGeneralNotification($users,$not_title,$not_type,$not_link,$from_user_id))->delay($delay_time);
                            $this->dispatch($job);
                        #endregion

                        $delay_time += 1;
                    }
                }

            }


        }
        elseif($share_post=="0"){
            $created_post->update([
                'post_type'=>$post_type,
                'post_body'=>$post_text,
                'post_files'=>json_encode($post_files),
            ]);
        }
        else{
            $created_post->update([
                "post_shares_count"=>$created_post->post_shares_count+1
            ]);

            $origin_post = posts_m::findOrFail(clean($post_id));

            $created_post=posts_m::create([
                'user_id'=>$this->user_id,
                'post_where'=>clean($request->get("post_where","profile")),
                'post_where_id'=>clean($request->get("post_where_id",$this->user_id)),
                'post_share_id'=>clean($post_id),
                'post_or_recommendation'=>$created_post->post_or_recommendation,
                'post_type'=>"text",
                'post_body'=>$post_text,
               //'cat_id'=>$origin_post->cat_id,
                'pair_currency_id'=>$origin_post->pair_currency_id,
                'sell_or_buy'=>$origin_post->sell_or_buy,
                'expected_price'=>$origin_post->expected_price,
                'closed_price'=>$origin_post->closed_price,
                "take_profit"=>$origin_post->$take_profit,
                "stop_loss"=>$origin_post->$stop_loss,
                'recommendation_status'=>$origin_post->recommendation_status,
                'recommendation_end_date'=>$origin_post->recommendation_end_date,
                'order_is_not_closed'=>$origin_post->order_is_not_closed,
                'post_privacy'=>$origin_post->post_privacy,
            ]);

            post_shares_m::create([
                'user_id'=>$this->user_id,
                'post_id'=>clean($post_id),
            ]);

        }

        if(isset_and_array($post_files)){
            $old_imgs=users_or_pages_images_m::where("post_id",$created_post->post_id)->get()->groupBy("attachment_id")->all();

            $rows=[];
            foreach ($post_files as $key => $img_id) {
                if(isset($old_imgs[$img_id])){
                    unset($old_imgs[$img_id]);
                    continue;
                }

                $rows[]=[
                    "attachment_id"=>$img_id,
                    "user_id_or_users_pages_id"=>$this->user_id,
                    "attachment_type"=>"post",
                    "post_id"=>$created_post->post_id
                ];
            }

            if(isset_and_array($old_imgs)){
                //remove all old images
                $old_imgs_ids=array_keys($old_imgs);
                users_or_pages_images_m::where("post_id",$created_post->post_id)->whereIn("attachment_id",$old_imgs_ids)->delete();
            }

            users_or_pages_images_m::insert($rows);
        }

        if (isset_and_array($hashtags_words)){
            $old_hashtags=hashtag_posts_m::where("post_id",$created_post->post_id)->get()->groupBy("hashtag_id")->all();
            $hashtags_words=array_diff($hashtags_words,[""]);
            $hashtags_words=array_unique($hashtags_words);

            //get hashtag from db or insert it as new one
            $hashtag_rows=hashtags_m::whereIn("hashtag_name",$hashtags_words)->get()->groupBy("hashtag_name")->all();

            foreach ($hashtags_words as $key => $hashtags_word) {
                $hashtag_id=0;
                if(!isset($hashtag_rows[$hashtags_word])){
                    $hashtag_row=hashtags_m::create([
                        "hashtag_name"=>$hashtags_word,
                        "owner_id"=>$this->user_id
                    ]);
                    $hashtag_id=$hashtag_row->hashtag_id;
                }
                else{
                    $hashtag_id=$hashtag_rows[$hashtags_word][0]->hashtag_id;
                }

                if(isset($old_hashtags[$hashtag_id])){
                    unset($old_hashtags[$hashtag_id]);
                    continue;
                }

                //attach post with tag_id
                hashtag_posts_m::create([
                    'hashtag_id'=>$hashtag_id,
                    'post_id'=>$created_post->post_id
                ]);
            }

            if(isset_and_array($old_hashtags)){
                $old_hashtags_ids=array_keys($old_hashtags);
                hashtag_posts_m::where("post_id",$created_post->post_id)->whereIn("hashtag_id",$old_hashtags_ids)->delete();
            }
        }

        if($created_post->post_or_recommendation=="recommendation"){
            $this->send_notification_in_case_of_order($created_post->post_id,$created_post->created_at);
        }

        $this->show_post($request,$created_post->post_id, 'add_post');
    }

    public function show_post(Request $request,$post_id="",$callback = "", $return_data=false,$preview_post=false)
    {
        if($callback=="add_post")
            $postId = $post_id;
        else
            $postId = ($request->get('post_id') !== null ? $request->get('post_id') : $post_id);

        $output=[];
        if ($preview_post)
        {
            $this->data["preview_post"] = $preview_post;
        }

        if($post_id==""){
            $post_id=$request->get('post_id');
        }

        $post_view=clean($request->get("post_view"));

        if(empty($post_view)){
            $post_view="post";
        }

        if(!in_array($post_view,["post","post_lifetime"])){
            $post_view="post";
        }


        //get post data
        $original_post_id=$post_id;
        $post_data=posts_m::findOrFail($postId);
        $post_data->post_created_at=$post_data->created_at;
        $original_post_data="";

        if($post_data->cat_id>0){
            $post_cat=
                category_m::
                select("category_translate.cat_name")->
                join("category_translate","category_translate.cat_id","=","category.cat_id")
                    ->where("category.cat_id",$post_data->cat_id)
                    ->where("category_translate.lang_id",$this->lang_id)
                    ->get()->first();

            if(is_object($post_cat)){
                $post_data->cat_name=$post_cat->cat_name;
            }
        }

        if($post_data->post_where=="group"){
            $group_id=$post_data->post_where_id;

            groups_m::findOrFail($group_id);

            //check if this user is member in it
            $group_member=group_members_m::
            where("group_id",$group_id)->
            where("user_id",$this->user_id)->get()->first();

            if(!is_object($group_member)){
                abort(404);
            }
        }

        #region check if this post is orders collection post

            if (!empty($post_data->post_orders_ids) && $post_data->is_not_editable == 1)
            {
                $post_data->post_orders_ids = json_decode($post_data->post_orders_ids);
                if (count($post_data->post_orders_ids))
                {
                    $get_list_items = posts_m::
                        select(DB::raw("

                            posts.*,
                            pair.pair_currency_name,
                            cat_trans.cat_name

                        "))
                        ->join("pair_currency as pair","pair.pair_currency_id","=","posts.pair_currency_id")
                        ->join("category as cat","cat.cat_id","=","posts.cat_id")
                        ->join("category_translate as cat_trans","cat.cat_id","=","cat_trans.cat_id")
                        ->whereNull("cat.deleted_at")
                        ->whereNull("cat_trans.deleted_at")
                        ->where("cat_trans.lang_id",$this->lang_id)
                        ->whereIn("posts.post_id",$post_data->post_orders_ids)
                        ->where([
                            "posts.user_id" => $post_data->user_id,
                            "posts.post_or_recommendation" => "recommendation",
                            "posts.hide_post" => 0,
                            "posts.post_share_id" => 0,
                            "posts.post_privacy" => "public",
                        ])->get();

                        $get_post_view = \View::make("actions.posts.view.components.orders_list_table")
                            ->with([
                                "post_body" => $post_data->post_body,
                                "get_list_items" => $get_list_items,
                                "post_keywords" => $this->data["post_keywords"],
                                "general_static_keywords" => $this->data["general_static_keywords"],
                            ])->render();

                        $post_data->post_body = $get_post_view;
                }

            }

        #endregion

        $post_data->post_body = search_text_url($post_data->post_body);

        if($post_data->post_share_id>0){


            $original_post_data=posts_m::findOrFail($post_data->post_share_id);
            $original_post_id=$original_post_data->post_id;

            $original_post_data->post_body = search_text_url($original_post_data->post_body);


            $post_data->original_post_data=$original_post_data;
            $post_data->original_post_data->post_created_at=$original_post_data->created_at;
            $post_data->this_post_body=$post_data->post_body;
            $post_data->post_files=$original_post_data->post_files;
            $post_data->post_body=$original_post_data->post_body;
            $post_data->post_type=$original_post_data->post_type;
            $post_data->posts_youtube_links=$original_post_data->posts_youtube_links;
            $post_data->post_or_recommendation=$original_post_data->post_or_recommendation;
        }

        $post_data->post_files=json_decode($post_data->post_files);

        if(isset_and_array($post_data->post_files)){
            //load imgs
            if (count($post_data->post_files) > 1)
            {
                $output["contain_slider"] = true;
            }
            $post_data->imgs_data=attachments_m::whereIn("id",$post_data->post_files)->get()->all();
        }

        //load post hashtags
        $post_data->post_hashtags=
            hashtag_posts_m::
            join("hashtags","hashtags.hashtag_id","=","hashtag_posts.hashtag_id")->
            where("hashtag_posts.post_id",$original_post_id)->get()->all();


        $post_data->post_user=User::get_users(" AND u.user_id=$post_data->user_id");
        if(!isset_and_array($post_data->post_user)){
            $output["post_html"]="";
            echo json_encode($output);
            return;
        };
        $post_data->post_user=$post_data->post_user[0];

        //get $original_post_id user
        if($post_data->post_id!=$original_post_id&&is_object($original_post_data)){
            //this post is shared from another post
            $post_data->orginal_post_user=User::get_users(" AND u.user_id=$original_post_data->user_id");
            if(!isset_and_array($post_data->orginal_post_user)){
                $output["post_html"]="";
                echo json_encode($output);
                return;
            };
            $post_data->orginal_post_user=$post_data->orginal_post_user[0];
        }

        $post_like_obj=post_likes_m::where("post_id",$post_data->post_id)->
        where("user_id",$this->user_id)->get()->first();
        $this->data["this_user_like"]=is_object($post_like_obj);

        if($post_data->pair_currency_id>0){
            $post_data->pair_currency_name=pair_currency_m::find($post_data->pair_currency_id)->pair_currency_name;
        }

        #region get post likes & comments & shares

            $this->data["post_likes_title"] = "";
            $this->data["post_comments_title"] = "";
            $this->data["post_shares_title"] = "";

            $get_likes = post_likes_m::where("post_id",$post_id)
                ->orderBy("post_like_id","desc")->limit(10)->pluck('user_id')->all();
            if (count($get_likes))
            {
                $get_users = User::whereIn("user_id",$get_likes)->pluck('full_name')->all();
                $get_users = implode('<br>',$get_users);
                $this->data["post_likes_title"] = $get_users;
            }

            $get_comments = post_comments_m::where("post_id",$post_id)
                ->orderBy("post_comment_id","desc")->pluck('user_id')->all();

            if(count($get_comments))
            {
                $get_comments = array_unique($get_comments);
                $get_users = User::whereIn("user_id",$get_comments)->limit(10)->pluck('full_name')->all();
                $get_users = implode('<br>',$get_users);
                $this->data["post_comments_title"] = $get_users;
            }

            $get_shares = post_shares_m::where("post_id",$post_id)
                ->orderBy("post_share_id","desc")->pluck('user_id')->all();

            if(count($get_shares))
            {
                $get_shares = array_unique($get_shares);
                $get_users = User::whereIn("user_id",$get_shares)->limit(10)->pluck('full_name')->all();
                $get_users = implode('<br>',$get_users);
                $this->data["post_shares_title"] = $get_users;
            }

        #endregion
        $post_data->post_shares_count = post_shares_m::where("post_id",$post_data->post_id)->count();

        $post_user_name = $post_data->post_user->full_name;
        $this->data["og_title"] = " Post From $post_user_name ";
        $this->data["og_description"] = split_word_into_chars($post_data->post_body,70);

        $this->data["post_data"]=$post_data;
        $output["post_html"]=\View::make("actions.posts.view.$post_view")->with($this->data)->render();
        $output["post_id"]=$post_data->post_id;

        if($return_data){
            return $output;
        }
        echo json_encode($output);
    }

    public function edit_post(Request $request){
        $output=[];

        //get post data
        $post_id=clean($request->get("post_id"));
        $post_data=posts_m::findOrFail($post_id);

        if($post_data->user_id!=$this->user_id){
            return;
        }

        $post_data->post_files=json_decode($post_data->post_files);
        if(isset_and_array($post_data->post_files)){
            //load imgs
            $post_data->imgs_data=attachments_m::whereIn("id",$post_data->post_files)->get()->all();
        }

        $this->data["post_data"]=$post_data;
        $output["return_html"]=\View::make("actions.posts.edit_post_modal")->with($this->data)->render();

        echo json_encode($output);
    }

    public function get_order_closed_price_modal(Request $request){
        $output=[];

        //get post data
        $post_id=clean($request->get("post_id"));
        $post_data=posts_m::findOrFail($post_id);

        if($post_data->user_id!=$this->user_id){
            return;
        }

        if($post_data->closed_price!=0){
            return;
        }

        $this->data["post_data"]=$post_data;
        $output["return_html"]=\View::make("actions.posts.get_order_closed_price_modal")->with($this->data)->render();

        echo json_encode($output);
    }

    public function add_order_closed_price(Request $request){

        $new_output["order_view"] = "";

        //get post data
        $post_id=clean($request->get("post_id"));
        $post_data=posts_m::findOrFail($post_id);

        $closed_price=$request->get("closed_price");

//        if(!($closed_price>0)){
//            return abort(404);
//        }

        if($post_data->user_id!=$this->user_id){
            return abort(404);
        }

        if($post_data->closed_price!=0){
            return abort(404);
        }

        $post_data->expected_price=$post_data->expected_price;

        $recommendation_status="equal";

        if (in_array($post_data->sell_or_buy,["buy","pending_buy"]))
        {
            if($closed_price>$post_data->expected_price){
                $recommendation_status="profit";
            }
            elseif($closed_price<$post_data->expected_price){
                $recommendation_status="lose";
            }
        }

        if (in_array($post_data->sell_or_buy,["sell","pending_sell"]))
        {
            if($closed_price<$post_data->expected_price){
                $recommendation_status="profit";
            }
            elseif($closed_price>$post_data->expected_price){
                $recommendation_status="lose";
            }
        }


        $post_data->update([
            "closed_price"=>$closed_price,
            "recommendation_status"=>$recommendation_status
        ]);



        $user_obj = $this->data["current_user"];
        $return_users = $this->_get_user_followers($user_obj);

        if (count($return_users))
        {
            $diff_price = calc_order_diff_price($post_data);
            $not_title = "Order Status changed to  >> $recommendation_status ";
            if ($diff_price != 0)
            {
                $not_title .= " ($diff_price p)";
            }

            $delay_time = 0;
            foreach($return_users as $key => $users)
            {

                #region create notification job

                $not_type = "order";
                $not_link = "posts/".string_safe($user_obj->full_name)."/$user_obj->user_id/$post_id";
                $from_user_id = $this->user_id;

                $users = collect($users)->pluck('user_id')->all();
                $job = (new SendGeneralNotification($users,$not_title,$not_type,$not_link,$from_user_id))->delay($delay_time);
                $this->dispatch($job);

                #endregion

                $delay_time += 1;
            }
        }

        $new_output["order_view"] = $this->show_post($request,$post_id);

        return ($new_output["order_view"]);

    }

    public function delete_post(Request $request)
    {
        $output=[];

        $post_id=$request->get("post_id");
        $post_data=posts_m::findOrFail($post_id);

        if($post_data->user_id!=$this->user_id){
            return;
        }

        posts_m::destroy($post_id);

        $output["deleted"]="yes";
        echo json_encode($output);
    }

    public function save_post(Request $request){
        $output=[];

        $post_id=clean($request->get("post_id"));
        $post_data=posts_m::findOrFail($post_id);

        user_saved_posts_m::create([
            "post_id"=>$post_id,
            "user_id"=>$this->user_id
        ]);

        $output["msg"]="Saved Successfully";

        echo json_encode($output);
    }

    public function share_post(Request $request)
    {
        $output=[];

        //get post data
        $post_id=clean($request->get("post_id"));
        $post_data=posts_m::findOrFail($post_id);

        $post_data->post_files=json_decode($post_data->post_files);
        if(isset_and_array($post_data->post_files)){
            //load imgs
            $post_data->imgs_data=attachments_m::whereIn("id",$post_data->post_files)->get()->all();
        }

        $this->data["post_data"]=$post_data;
        $output["return_html"]=\View::make("actions.posts.share_post_modal")->with($this->data)->render();

        echo json_encode($output);
    }

    public function show_hashtag_posts(Request $request){
        $hashtag_text=$request->segment(count($request->segments()));
        $hashtag_text=clean($hashtag_text);

        $this->data["hashtag_text"]=$hashtag_text;

        return view('front.subviews.show_hashtag_page', $this->data);
    }

    public function load_hashtag_posts(Request $request){

        $hashtag_text=$request->segment(count($request->segments()));
        $hashtag_text=clean(urldecode($hashtag_text));

        $offset=$request->get("offset","0");

        $hashtag_obj=hashtags_m::where("hashtag_name",$hashtag_text)->get()->first();

        if(!is_object($hashtag_obj)){
            return abort(404);
        }

        $posts_ids=hashtag_posts_m::
        where("hashtag_id",$hashtag_obj->hashtag_id)->
        limit($this->post_limit)->
        offset($offset)->
        orderBy("post_id","desc");

        $next_posts_ids=$posts_ids;

        $posts_ids=$posts_ids->get()->pluck("post_id")->all();

        $next_posts_ids=$next_posts_ids->limit($this->post_limit)->
        offset($offset+$this->post_limit)->
        get()->pluck("post_id")->all();

        if(isset_and_array($next_posts_ids)){
            $output["post_limit"]=$this->post_limit;
        }

        $output["posts_ids"]=$posts_ids;
        echo json_encode($output);
    }

    public function show_single_post(Request $request){
        $post_id=$request->segment(count($request->segments()));
        $post_id=clean($post_id);

        $comment_id=$request->get("comment_id","");
        if(empty($comment_id)){
            $comment_id=post_comments_m::
            where("post_id",$post_id)->
            orderBy("post_comment_id","desc")->
            limit(1)->get()->
            first();

            if(is_object($comment_id)){
                $comment_id=$comment_id->post_comment_id;
            }
            else{
                $comment_id="";
            }
        }

        if(!empty($comment_id)){
            $request["post_id"]=$post_id;
            $this->data=array_merge($this->data,comments_utilities::load_post_comments($request,$this->user_id,true,$comment_id));

            $this->data["comments_html"]=\View::make("actions.posts.view.components.comments_section")->with($this->data)->render();

            $this->data["highlight_comment_id"]=$comment_id;
        }

        $this->data["post_data"]=posts_m::findOrFail($post_id);

        $post_html=$this->show_post($request,$post_id,"show_single_post", true);


        $post_html=$post_html["post_html"];
        $this->data["post_html"]=$post_html;

        return view('front.subviews.show_single_page', $this->data);
    }

    public function preview_single_post(Request $request){

        $post_id=$request->segment(count($request->segments()));
        $post_id=clean($post_id);

        $user_name=$request->segment(3);
        $user_name=clean($user_name);

        $comment_id=$request->get("comment_id","");


        if(is_object($this->data["current_user"]))
        {
            return  Redirect::to("posts/$user_name/$this->user_id/$post_id")->send();
        }

        if(!empty($comment_id)){
            $request["post_id"]=$post_id;
            $this->data=array_merge($this->data,comments_utilities::load_post_comments($request,$this->user_id,true,$comment_id));

            $this->data["comments_html"]=\View::make("actions.posts.view.components.comments_section")->with($this->data)->render();

            $this->data["highlight_comment_id"]=$comment_id;
        }

        $this->data["post_data"]=posts_m::findOrFail($post_id);

        $post_data = $this->data["post_data"];
        $post_files = json_decode($post_data->post_files);

        if (count($post_files) && isset($post_files[0]))
        {

            $get_image = attachments_m::findOrFail($post_files[0]);
            $this->data["og_img"] = url($get_image->path);
        }

        $this->data["preview_post"] = true;

        $post_html=$this->show_post($request,$post_id,true,$preview_post = true);

        $post_html=$post_html["post_html"];
        $this->data["post_html"]=$post_html;

        $this->data["og_title"] = " Post From $user_name ";
        $this->data["og_description"] = " Post From $user_name on invstoc.com ";
        $this->data["og_url"] = $request->fullUrl();


        return view('front.subviews.preview_single_post', $this->data);
    }

    public function send_notification_in_case_of_order($post_id,$created_at){

        $user_follow_users=
        followers_m::
        select("followers.*","u.timezone")
            ->join("users as u",function($join)
            {
                $join->on("followers.from_user_id","=","u.user_id");
            })
            ->where("to_user_id",$this->user_id)->
            limit(300)->
            get()->all();

        //cat_trans.cat_name,
        $post_data = posts_m::
        select(DB::raw("  posts.*,
                                pair.pair_currency_name,

                                user.user_id,
                                user.full_name

                            "))
            ->join("users as user",function($join)
            {
                $join->on("user.user_id","=","posts.user_id")
                    ->whereNull("user.deleted_at");
            })
            ->join("pair_currency as pair",function($join){
                $join->on("pair.pair_currency_id","=","posts.pair_currency_id")
                    ->whereNull("pair.deleted_at");
            })
           /* ->join("category as cat",function($join){
                $join->on("cat.cat_id","=","posts.cat_id")
                    ->whereNull("cat.deleted_at");
            })
            ->join("category_translate as cat_trans",function($join){
                $join->on("cat.cat_id","=","cat_trans.cat_id")
                    ->whereNull("cat_trans.deleted_at")
                    ->where("cat_trans.lang_id","=",$this->lang_id);
            })*/
            ->where([
                "posts.post_id"                 => $post_id,
                "posts.post_where"              => "profile",
                "posts.post_or_recommendation"  => "recommendation",
                "posts.hide_post"               => 0,
//                "posts.post_share_id"           => 0,
                "posts.post_privacy"            => "public",
            ])
            ->orderBy("posts.post_id","desc")->first();

        foreach ($user_follow_users as $follower_obj){

            $follower_id = $follower_obj->from_user_id;

            $follower_hot_order_cache=\Cache::get("user_hot_order_".$follower_id);
            if(!empty($follower_hot_order_cache)){
                $follower_hot_order_cache=json_decode($follower_hot_order_cache,true);
            }
            else{
                $follower_hot_order_cache=[];
            }


            $view_html=\View::make("blocks.hot_order_li")->with([
                "poster_full_name"=>$this->data["current_user"]->full_name,
                "poster_user_id"=>$this->data["current_user"]->user_id,
                "user_timezone"=>$follower_obj->timezone,
                "post_id"=>$post_id,
                "post_data"=>$post_data,
                "post_keywords"=>$this->data["post_keywords"],
                "created_at"=>$created_at,
                "user_homepage_keywords" => $this->data["user_homepage_keywords"]
            ])->render();

            $follower_hot_order_cache[$post_id]=$view_html;
            \Cache::put("user_hot_order_".$follower_id,json_encode($follower_hot_order_cache),60);
        }

    }

    public function make_order_not_closed(Request $request){

        $post_id=$request->get("post_id");
        $post_data=posts_m::findOrFail($post_id);

        if($post_data->user_id!=$this->user_id){
            return;
        }

        if(!in_array($post_data->sell_or_buy,["pending_sell","pending_buy"]))return;

        $post_data->update([
            "order_is_not_closed"=>"1"
        ]);



        $user_obj = $this->data["current_user"];
        $return_users = $this->_get_user_followers($user_obj);

        if (count($return_users))
        {

            $delay_time = 60;
            foreach($return_users as $key => $users)
            {

                #region create notification job
                    $not_title = "Order Status changed to  >> closed";
                    $not_type = "order";
                    $not_link = "posts/".string_safe($user_obj->full_name)."/$user_obj->user_id/$post_id";
                    $from_user_id = $this->user_id;

                    $users = collect($users)->pluck('user_id')->all();
                    $job = (new SendGeneralNotification($users,$not_title,$not_type,$not_link,$from_user_id))->delay($delay_time);
                    $this->dispatch($job);
                #endregion

                $delay_time += $delay_time;
            }
        }


        echo "order is closed";
    }

    public function get_post_username_shares(Request $request){
        $output=[];
        $output["html"] = "";

        $post_id = intval(clean($request->get("post_id",0)));

        if ($post_id > 0)
        {
            $get_shares = post_shares_m::where("post_id",$post_id)->pluck('user_id')->all();
            if (count($get_shares))
            {
                $get_shares = array_unique($get_shares);
                $get_shares = implode(',',$get_shares);
                $get_users = User::get_users("
                    AND u.user_id in ($get_shares)
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


    public function get_work_shop_followers($workshop_id,$post_owner_id)
    {

        $return_users = [];

        $get_followers_ids = workshop_followers_m::
            where("workshop_id",$workshop_id)
            ->where("user_id","<>",$post_owner_id)
            ->get()->pluck("user_id")->all();


        if (count($get_followers_ids))
        {

            $get_followers_ids = implode(',',$get_followers_ids);
            $return_users = User::get_users(" AND u.user_id in ($get_followers_ids) ");

            $return_users = collect($return_users)->chunk(20);
        }

        return $return_users;

    }

}
