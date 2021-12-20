<?php

namespace App\Http\Controllers\front;

use App\Events\posts_events;
use App\Http\Controllers\actions\posts\posts;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\models\ads_m;
use App\models\category_m;
use App\models\chat\chat_messages_m;
use App\models\chat\chats_m;
use App\models\followers_m;
use App\models\group_workshop\workshops_count_m;
use App\models\notification_m;
use App\models\pages\pages_m;
use App\models\pages\pages_translate_m;
use App\models\pages\users_accounts_m;
use App\models\posts\orders_list_m;
use App\models\posts\post_likes_m;
use App\models\posts\posts_m;
use App\models\posts\user_saved_posts_m;
use App\models\settings_m;
use App\models\support_messages_m;
use App\models\users_actions_count_m;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        //$this->middleware("check_user");

    }


    public function index(){
        $this->data=array_merge($this->data,posts_events::before_add_post());

        if ($this->data["current_user"]->is_logged_before == 0)
        {
            $this->data["msg"] = "<div class='alert alert-info'>Welcome to our website</div>";
            $email__msg = "Welcome to our website";
            $this->_send_email_to_custom(
                $emails = array($this->data["current_user"]->email) ,
                $data = "$email__msg" ,
                $subject = "Welcome message from Invstoc"
            );

            User::findOrFail($this->user_id)->update([
                "is_logged_before" => 1
            ]);
        }


        #region get first account of code 1 to be follow

            $get_first_account = User::where('username','1')->first();
            if (is_object($get_first_account))
            {

                $get_check = followers_m::
                    where("from_user_id",$this->user_id)
                    ->where("to_user_id",$get_first_account->user_id)->first();

                if (!is_object($get_check))
                {
                    followers_m::create([
                        "from_user_id" => $this->user_id,
                        "to_user_id" => $get_first_account->user_id,
                    ]);
                }

            }

        #endregion

        $this->data["load_user_dna"] = true;

        //dump($this->data["current_user"]);

        return view("front.subviews.home",$this->data);
    }

    public function saved_posts(){
        $liked_posts=post_likes_m::where("user_id",$this->user_id)->get()->pluck("post_id")->all();

        $this->data["liked_posts"]=$liked_posts;

        return view("front.subviews.saved_posts",$this->data);
    }


    public function load_homepage_posts(Request $request){

        $offset=$request->get("offset","0");

        $user_follow_users_ids=followers_m::
        where("from_user_id",$this->user_id)->
        get()->pluck("to_user_id")->all();

        $user_follow_users_ids[]=$this->user_id;

        $posts_ids=
            posts_m::
            select("post_id")->
            where("post_where","profile")->
            whereIn("user_id",$user_follow_users_ids);

        $next_posts_ids=$posts_ids;

        $posts_ids=$posts_ids->limit($this->post_limit)->
            offset($offset)->
            orderBy("post_id","desc")->
            get()->pluck("post_id")->all();

        $next_posts_ids=$next_posts_ids->limit($this->post_limit)->
        offset($offset+$this->post_limit)->
        get()->pluck("post_id")->all();

        if(isset_and_array($next_posts_ids)){
            $output["post_limit"]=$this->post_limit;
        }

        $output["posts_ids"]=$posts_ids;
        echo json_encode($output);
    }

    public function load_saved_posts(Request $request){

        $offset=$request->get("offset","0");

//        $liked_posts=post_likes_m::where("user_id",$this->user_id)->get()->pluck("post_id")->all();

        $liked_posts=user_saved_posts_m::where("user_id",$this->user_id)->get()->pluck("post_id")->all();

        $posts_ids=
            posts_m::
            select("post_id")->
            whereIn("post_id",$liked_posts);

        $next_posts_ids=$posts_ids;

        $posts_ids=$posts_ids->limit($this->post_limit)->
        offset($offset)->
        orderBy("post_id","desc")->
        get()->pluck("post_id")->all();

        $next_posts_ids=$next_posts_ids->limit($this->post_limit)->
        offset($offset+$this->post_limit)->
        get()->pluck("post_id")->all();

        if(isset_and_array($next_posts_ids)){
            $output["post_limit"]=$this->post_limit;
        }

        $output["posts_ids"]=$posts_ids;
        echo json_encode($output);
    }


    public function add_new_account_to_cash_back(Request $request)
    {

        $output = [];
        $output["status"] = "";
        $output["msg"] = "";


        $page_id = intval(clean($request->get("page_id")));
        $account_id = intval(clean($request->get("account_id")));
        $account_number = clean($request->get("account_number"));

        if ($page_id > 0 && !empty($account_number))
        {

            if ($account_id > 0)
            {
                // edit
                users_accounts_m::findOrFail($account_id)->update([
                    "page_id" => $page_id,
                    "user_id" => $this->user_id,
                    "account_number" => $account_number,
                ]);

                #region send email to admins

                $get_company = pages_m::get_pages(
                    $additional_where = " 
                        AND page.page_type = 'company' 
                        AND page.hide_page = 0 
                        AND page.page_id = $page_id
                    ",
                    $order_by = " order by page.page_id " ,
                    $limit = "",
                    $check_self_translates = false,
                    $default_lang_id=$this->lang_id,
                    $load_slider=false
                );
                $get_company = $get_company[0];

                $current_user = $this->data["current_user"];


                $data = " user '$current_user->full_name' with code '$current_user->username'
                        has updated his old account to be under company '$get_company->page_title'
                        with account number '$account_number'
                     " ;
                $this->_send_email_to_all_users_type(
                    $user_type = "admin" ,
                    $data =
                        [
                            "body" => $data,
                        ] ,
                    $subject = "New Account under Company '$get_company->page_title'",
                    $sender = "info@invstoc.com" ,
                    $path_to_file = "",
                    $email_template ="email.body_template"
                );

                #endregion

                $output["status"] = "success";
                $output["msg"] = " Account is Updated Successfully ...";
                echo json_encode($output);
                return;

            }

            // check if exist
            $check_exist = users_accounts_m::where("user_id",$this->user_id)
                ->where("page_id",$page_id)->get()->first();
            if (is_object($check_exist))
            {

                $output["status"] = "error";
                $output["msg"] = " Account is Existed !! ";
            }
            else{
                users_accounts_m::create([
                    "page_id" => $page_id,
                    "user_id" => $this->user_id,
                    "account_number" => $account_number,
                ]);

                #region send email to admins

                $get_company = pages_m::get_pages(
                    $additional_where = " 
                        AND page.page_type = 'company' 
                        AND page.hide_page = 0 
                        AND page.page_id = $page_id
                    ",
                    $order_by = " order by page.page_id " ,
                    $limit = "",
                    $check_self_translates = false,
                    $default_lang_id=$this->lang_id,
                    $load_slider=false
                );
                $get_company = $get_company[0];

                $current_user = $this->data["current_user"];

                $data = " user '$current_user->full_name' with code '$current_user->username'
                        has added new account under company '$get_company->page_title'
                        with account number '$account_number'
                     ";
                $this->_send_email_to_all_users_type(
                    $user_type = "admin" ,
                    $data =
                        [
                            "body" => $data,
                        ] ,
                    $subject = "New Account under Company '$get_company->page_title'",
                    $sender = "info@invstoc.com" ,
                    $path_to_file = "",
                    $email_template ="email.body_template"
                );

                #endregion

                $output["status"] = "success";
                $output["msg"] = " Account is Created Successfully ...";
            }

        }
        else{
            $output["status"] = "error";
            $output["msg"] = " Please Fill Data First !! ";
        }

        echo json_encode($output);
        return;

    }

    public function get_my_cash_back_accounts(Request $request)
    {

        $output = [];
        $output["status"] = "success";
        $output["data"] = "";
        $output["select_items"] = "";

        #region cash back

        $accounts_cond = "
            AND page.hide_page = 0
            AND page.page_type = 'company'
        ";
        $user_accounts = [];
        $user_accounts_pages = [];

        // get current user accounts
        if(is_object($this->data["current_user"]))
        {
            $get_user_accounts = users_accounts_m::where("user_id",$this->user_id)->get()->all();

            $user_accounts = collect($get_user_accounts)->groupBy("page_id")->all();

            if (count($get_user_accounts))
            {
                $pages_ids = convert_inside_obj_to_arr($get_user_accounts,"page_id");
                $pages_ids = implode(',',$pages_ids);

                $user_accounts_pages = pages_m::get_pages("
                    $accounts_cond 
                    AND page.page_id in ($pages_ids)
                ");


                $accounts_cond .= " AND page.page_id not in ($pages_ids) ";
            }
        }

        $accounts = pages_m::get_pages("$accounts_cond");

        if (count($accounts))
        {
            foreach($accounts as $key => $account)
            {
                $output["select_items"] .= "<option value='$account->page_id'>$account->page_title</option>";
            }
        }

        $ads_balance = $this->data["current_user"]->ads_balance;
        $ads_balance_label = show_content($this->data["general_static_keywords"],"ads_balance_label");

        $referrer_balance = $this->data["current_user"]->referrer_balance;
        $referrer_count = $this->data["current_user"]->referrer_count;
        $referrer_balance_label = show_content($this->data["general_static_keywords"],"referrer_balance_label");
        $output["data"] .= '
                <tr>
                    <td> <input type="checkbox" class="form-control check_all_account_items"></td>
                    <td></td>
                    <td></td>
                    <td><p><b>All</b></p></td>
                </tr>
            '; 
        $output["data"] .= '
                <tr>
                    <td><input style="width: 22px;cursor: pointer;" type="checkbox" data-balance="'.$ads_balance.'"  data-account_id="0" class="form-control check_account_item"></td>
                    <td></td>
                    <td><label class="btn-info"><a>'.$ads_balance.'</a></label></td>
                    <td><p><b>'.$ads_balance_label.'</b></p></td>
                </tr>
            ';

        $output["data"] .= '
                <tr>
                    <td><input style="width: 22px;cursor: pointer;" type="checkbox" data-balance="'.$referrer_balance.'"  data-account_id="-1" class="form-control check_account_item"></td>
                    <td></td>
                    <td><label class="btn-info"><a>'.$referrer_balance.'</a></label></td>
                    <td><p><b>'.$referrer_balance_label.' ('.$referrer_count.') </b></p></td>
                </tr>
            ';

        if (count($user_accounts_pages))
        {

            foreach($user_accounts_pages as $key => $acc_obj)
            {

                $acc_id = (isset($user_accounts[$acc_obj->page_id][0])?$user_accounts[$acc_obj->page_id][0]->id:0);
                $account_number = (isset($user_accounts[$acc_obj->page_id][0])?$user_accounts[$acc_obj->page_id][0]->account_number:"");
                $account_balance = (isset($user_accounts[$acc_obj->page_id][0])?$user_accounts[$acc_obj->page_id][0]->account_balance:0);

                $output["data"] .= '<tr>';
                $output["data"] .= '        <td><input style="width: 22px;cursor: pointer;" type="checkbox" data-balance="'.$account_balance.'" data-account_id="'.$acc_id.'" class="form-control check_account_item"></td>';
                $output["data"] .= '        <td><img width="60" height="40" src="'.get_image_or_default($acc_obj->small_img_path).'" />';
                $output["data"] .= '            <label><a href="#" class="edit_account_data" data-page_title="'.$acc_obj->page_title.'" data-page_id="'.$acc_obj->page_id.'" data-account_number="'.$account_number.'" data-id="'.$acc_id.'">Edit</a></label></td>';
                $output["data"] .= '        <td><label class="btn-info"><a>'.$account_balance.'</a></label></td>';
                $output["data"] .= '        <td><p>'.(isset($user_accounts[$acc_obj->page_id][0])?$user_accounts[$acc_obj->page_id][0]->account_number:"").'</p></td>';
                $output["data"] .= '</tr>';
            }

        }

        #endregion


        echo json_encode($output);
        return;

    }

    public function request_accounts_withdraw(Request $request)
    {

        $output = [];
        $output["msg"] = "";

        $current_user = $this->data["current_user"];

        $account_ids = clean($request->get('account_ids'));

        if (!count($account_ids))
        {
            $output["msg"] = "لا توجد حسابات لإجراء الطلب !!";
            echo json_encode($output);
            return;
        }

        $get_accounts = users_accounts_m::whereIn("id",$account_ids)->get()->all();

        $accounts_cond = "
            AND page.hide_page = 0
            AND page.page_type = 'company'
        ";
        $get_brokers = pages_m::get_pages("$accounts_cond");
        $get_brokers = collect($get_brokers)->groupBy("page_id")->all();

        if (!count($get_accounts) && !in_array(0,$account_ids) && !in_array(-1,$account_ids))
        {
            $output["msg"] = "لا توجد حسابات لإجراء الطلب !!";
            echo json_encode($output);
            return;
        }

        $msg_to_admins = " User <b>$current_user->full_name</b> with code <b>$current_user->username</b> has requested
            to withdraw the following accounts data :- <br>
        ";

        foreach($get_accounts as $key => $account)
        {
            if (!isset($get_brokers[$account->page_id]) || !is_object($get_brokers[$account->page_id][0]))
                continue;

            $get_company = $get_brokers[$account->page_id][0];
            $msg_to_admins .= ($key+1)." - Company (<b>$get_company->page_title</b>), User Account Number ($account->account_number), with Balance ($account->account_balance) ";

        }

        if(in_array(0,$account_ids))
        {
            $ads_balance = $this->data["current_user"]->ads_balance;
            $msg_to_admins .= ((count($get_accounts))+1)." - (<b>Ads Balance</b>), with Balance ($ads_balance) ";
        }

        if(in_array(-1,$account_ids))
        {
            $referrer_balance = $this->data["current_user"]->referrer_balance;
            $msg_to_admins .= ((count($get_accounts))+2)." - (<b>Referrer Balance</b>), with Balance ($referrer_balance) ";
        }

        support_messages_m::create([
            "msg_type" => "withdraw_request",
            "name" => $current_user->full_name." ($current_user->username) ",
            "message" => $msg_to_admins,
            "email" => $current_user->email,
        ]);


        #region send notification and message to user

        $admin_notify = show_content($this->data["validation_messages"],"msg_success_and_wait_admin");
        notification_m::create([
            "not_title" => $admin_notify,
            "not_type" => 'admin_notify',
            "not_from_user_id" => 1,
            "not_to_user_id" => $this->user_id,
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s"),
        ]);


        // check if there is exist chat between them or not
        $get_chat = chats_m::get_chats("
                    AND (chat.from_user_id = 1 AND chat.to_user_id = $this->user_id)
                    OR (chat.to_user_id= $this->user_id AND chat.from_user_id  = 1)
                ");

        if (count($get_chat))
        {
            $get_chat = $get_chat[0];
            $chat_id = $get_chat->chat_id;
        }
        else{
            // create chat
            $new_chat = chats_m::create([
                "chat_name" => "رسائل خاصه من الموقع",
                "from_user_id" => 1,
                "to_user_id" => $this->user_id,
            ]);

            if (!is_object($new_chat))
            {
                $output['msg'] = 'Something is wrong try again later !!';
                echo json_encode($output);
                return;
            }

            $chat_id = $new_chat->chat_id;
        }

        // create new message
        $msg_obj=chat_messages_m::create([
            "chat_id" => $chat_id,
            "from_user_id" => 1,
            "to_user_id" => $this->user_id,
            "message" => $admin_notify,
        ]);

        #endregion


        #region increase notifications , messages count

        User::findOrFail($this->user_id)->update([
            "not_seen_all_notifications" => ($current_user->not_seen_all_notifications + 1),
            "not_seen_messages" => ($current_user->not_seen_messages + 1),
        ]);

        #endregion

        #region send email to admins

        $subject = $current_user->full_name." ($current_user->username) make Withdraw Request ";

        // send notification
        notification_m::create([
            "not_title" => $subject,
            "not_from_user_id" => $this->user_id,
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s"),
        ]);

        //1)admin

        $this->_send_email_to_all_users_type(
            $user_type = "admin" ,
            $data =
                [
                    "body" => $msg_to_admins,
                ] ,
            $subject,
            $sender = "info@invstoc.com" ,
            $path_to_file = "",
            $email_template ="email.body_template"
        );

        #endregion
        $output["msg"] = show_content($this->data["validation_messages"],"msg_success");

        echo json_encode($output);
        return;

    }

    public function get_workshops_trending(Request $request)
    {

        $output = [];
        $output["status"] = "success";
        $output["data"] = "";

        $get_trending = workshops_count_m::get_top_workshops();

        if (!count($get_trending))
        {
            $output["data"] = "<tr><th><p>No Workshops Trending until now !!</p></th></tr>";
        }
        else{

            foreach($get_trending as $key => $trend)
            {
                if ($trend->trend_counter == 0)
                    continue;

                $link = url("workshop/$trend->workshop_name/$trend->workshop_id");

                $output["data"] .= "<tr>";
                    $output["data"] .= "<td><a href='$link'><img src='".url(get_image_or_default($trend->path))."' width='50' height='50' class='img-circle'></a></td>";
                    $output["data"] .= "<td><a href='$link'><p>$trend->workshop_name</p></a></td>";
                    $output["data"] .= "<td><label>$trend->trend_counter</label></td>";
                $output["data"] .= "</tr>";
            }

        }

        echo json_encode($output);
        return;
    }

    public function get_books_trending(Request $request)
    {

        $output = [];
        $output["status"] = "success";
        $output["data"] = "";

        $get_trending = category_m::get_all_cats(
            $additional_where = " 
                AND cat.hide_cat=0
                AND cat.cat_type='book'
                AND cat.cat_views > 0
            ",
            $order_by = " order by cat.cat_views desc " ,
            $limit = "",
            $make_it_hierarchical=false,
            $default_lang_id=$this->lang_id
        );

        if (!count($get_trending))
        {
            $output["data"] = "<tr><th><p>No Books Trending until now !!</p></th></tr>";
        }
        else{

            foreach($get_trending as $key => $trend)
            {
                $link = url("books/$trend->cat_id");

                $output["data"] .= "<tr>";
                    $output["data"] .= "<td><a href='$link'><img src='".url(get_image_or_default($trend->small_img_path))."' width='50' height='50' class='img-circle'></a></td>";
                    $output["data"] .= "<td><a href='$link'><p>$trend->cat_name</p></a></td>";
                    $output["data"] .= "<td><label>$trend->cat_views</label></td>";
                $output["data"] .= "</tr>";
            }

        }

        echo json_encode($output);
        return;
    }

    public function get_brokers_trending(Request $request)
    {

        $output = [];
        $output["status"] = "success";
        $output["data"] = "";

        $get_trending = users_accounts_m::get_top_brokers_accounts($default_lang_id=$this->lang_id);

        if (!count($get_trending))
        {
            $output["data"] = "<tr><th><p>No Books Trending until now !!</p></th></tr>";
        }
        else{

            foreach($get_trending as $key => $trend)
            {
                $link = url("brokers/$trend->page_id");

                $output["data"] .= "<tr>";
                    $output["data"] .= "<td><a href='$link'><img src='".url(get_image_or_default($trend->small_img_path))."' width='50' height='50' class='img-circle'></a></td>";
                    $output["data"] .= "<td><a href='$link'><p>$trend->page_title</p></a></td>";
                    $output["data"] .= "<td><label>$trend->broker_accounts</label></td>";
                $output["data"] .= "</tr>";
            }

        }

        echo json_encode($output);
        return;
    }

    public function get_users_trending(Request $request)
    {

        $output = [];
        $output["status"] = "success";
        $output["data"] = "";


        $get_trending = posts_m::
        select(DB::raw("
                posts.user_id, user_obj.* , count(*) as performance, 
                attach.path
            "))
            ->join("users as user_obj","user_obj.user_id","=","posts.user_id")
            ->leftJoin("attachments as attach","attach.id","=","user_obj.logo_id")
            ->where([
                "posts.post_or_recommendation"    => 'recommendation',
                "posts.recommendation_status"     => 'profit',
            ])->groupBy("posts.user_id")->havingRaw("count(*) > 0 ")->orderBy(DB::raw("count(*)"),"desc")->get()->all();

//        $get_trending = users_actions_count_m::get_top_users();

        if (!count($get_trending))
        {
            $output["data"] = "<tr><th><p>No Users Trending until now !!</p></th></tr>";
        }
        else{

            foreach($get_trending as $key => $trend)
            {
                $link = url("report/user/$trend->user_id");

                $output["data"] .= "<tr>";
                    $output["data"] .= "<td><a href='$link'><img src='".url(get_image_or_default($trend->path))."' width='50' height='50' class='img-circle'></a></td>";
                    $output["data"] .= "<td><a href='$link'><p>$trend->full_name</p></a></td>";
                    $output["data"] .= "<td><label>$trend->performance</label></td>";
                $output["data"] .= "</tr>";
            }

        }

        echo json_encode($output);
        return;
    }


    public function get_signals()
    {   
        $current_user = $this->data["current_user"];
        $get_trending = posts_m::
        select(DB::raw("
                posts.user_id, user_obj.* , count(*) as performance, 
                attach.path
            "))
            ->join("users as user_obj","user_obj.user_id","=","posts.user_id")
            ->leftJoin("attachments as attach","attach.id","=","user_obj.logo_id")
            ->where([
                "posts.post_or_recommendation"    => 'recommendation',

            ])->where('recommendation_status', '=', 'loss')->orWhere('recommendation_status', '=', 'profit') 
            ->groupBy("posts.user_id")->havingRaw("count(*) > 0 ")->orderBy(DB::raw("count(*)"),"desc")->get()->all();
            
          
            
            $this->data['trend_users']=[];

            foreach ($get_trending as $trend) {
             $user_id=$trend->user_id;
               $this->data['trend_users'][$user_id]=$trend;
                #region statistics
                    $get_orders_profit = posts_m::where([
                        "user_id" => $user_id,
                        "recommendation_status" => 'profit',
                    ])->count();

                    $get_orders_lose = posts_m::where([
                        "user_id" => $user_id,
                        "recommendation_status" => 'lose',
                    ])->count();

                    $get_orders_equal = posts_m::where([
                        "user_id" => $user_id,
                        "recommendation_status" => 'equal',
                    ])->count();
                    
                    $total_trads=$get_orders_profit+$get_orders_lose;
                    $trade_percentage=number_format($get_orders_profit/$total_trads*100,2);      
                    $this->data['trend_users'][$user_id]["orders_statistics"] = [
                        "profit" => $get_orders_profit,
                        "trade_percentage" => $trade_percentage,
                        "lose" => $get_orders_lose,
                    ];


                    $get_all_profit = posts_m::where([
                        "user_id" => $user_id,
                        "recommendation_status" => 'profit',
                    ])->get();

                     $get_sum_profit=0;
                     foreach ($get_all_profit as $get_profit) {
                         $get_sum_profit+=calc_order_diff_price($get_profit);
                     }

                   
                    $get_all_lose = posts_m::where([
                        "user_id" => $user_id,
                        "recommendation_status" => 'lose',
                    ])->get();
                   
                    $get_sum_lose=0;
                     foreach ($get_all_lose as $get_lose) {
                         $get_sum_lose+=calc_order_diff_price($get_lose);
                     }
                     $total_pips=$get_sum_lose+$get_sum_profit;
                     $pips_percentage=number_format($get_sum_profit/$total_pips*100,2);
                    $this->data['trend_users'][$user_id]["profit_lose_statistics"] = [
                        "profit" => $get_sum_profit,
                        "lose" => $get_sum_lose,
                        "pips_percentage" => $pips_percentage,
                    ];
                    
                
                #endregion

            }
            
            /*echo '<pre>';
            var_dump($this->data['trend_users']);
            exit(); */

         if (is_object($current_user))
        {
            return view('front.subviews.signals.user_index',$this->data);
        }
        else{
           return view('front.subviews.signals.visitor_index',$this->data);
        }
    }


    #region orders_list
    public function add_to_orders_list(Request $request)
    {

        $output = [];
        $output["status"] = "error";
        $output["msg"] = "";
        $output["li_item"] = "";

        $post_id = intval(clean($request->get("post_id",0)));

        if($post_id == 0)
        {
            $output["msg"] = show_content($this->data["validation_messages"],"not_valid_data_supplied");
            echo json_encode($output);
            return;
        }


        #region check if is added before

            $check_exist = orders_list_m::where([
                "user_id" => $this->user_id,
                "post_id" => $post_id
            ])->first();

            if (is_object($check_exist))
            {
                $output["msg"] = show_content($this->data["validation_messages"],"order_is_added_before_to_list");
                echo json_encode($output);
                return;
            }

        #endregion


        #region get post data

        $post_obj = posts_m::
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
            ->where([
                "posts.user_id" => $this->user_id,
                "posts.post_id" => $post_id,
                "posts.post_or_recommendation" => "recommendation",
                "posts.hide_post" => 0,
                "posts.post_share_id" => 0,
                "posts.post_privacy" => "public",
            ])->first();

            if (!is_object($post_obj))
            {
                $output["msg"] = show_content($this->data["validation_messages"],"not_valid_data_supplied");
                echo json_encode($output);
                return;
            }


            orders_list_m::create([
                "user_id" => $this->user_id,
                "post_id" => $post_id
            ]);

            $output["status"] = "success";
            $output["msg"] = show_content($this->data["validation_messages"],"msg_success");

            $output["li_item"] = $this->get_order_list_item($post_obj);

        #endregion

        echo json_encode($output);
        return;
    }

    public function load_orders_list_items(Request $request)
    {

        $output = [];
        $output["status"] = "error";
        $output["li_items"] = "";
        $output["li_items_count"] = 0;


        $get_list_items = orders_list_m::
        select(DB::raw("
            
                posts.*,
                pair.pair_currency_name,
                cat_trans.cat_name
                
            "))
            ->join("posts","posts.post_id","=","orders_list.post_id")
            ->join("pair_currency as pair","pair.pair_currency_id","=","posts.pair_currency_id")
            ->join("category as cat","cat.cat_id","=","posts.cat_id")
            ->join("category_translate as cat_trans","cat.cat_id","=","cat_trans.cat_id")
            ->whereNull("cat.deleted_at")
            ->whereNull("cat_trans.deleted_at")
            ->where("cat_trans.lang_id",$this->lang_id)
            ->where([
                "orders_list.user_id" => $this->user_id,
                "posts.user_id" => $this->user_id,
                "posts.post_or_recommendation" => "recommendation",
                "posts.hide_post" => 0,
                "posts.post_share_id" => 0,
                "posts.post_privacy" => "public",
            ])->get();
        if (count($get_list_items))
        {

            $li_items = "";
            $output["li_items_count"] = count($get_list_items);
            foreach($get_list_items as $key => $pro_obj)
            {
                $li_items .= $this->get_order_list_item($pro_obj);
            }

            $output["status"] = "success";
            $output["li_items"] = $li_items;

        }


        echo json_encode($output);
        return;
    }

    public function remove_order_list_item(Request $request)
    {
        $output = [];
        $output["status"] = "error";

        $post_id = intval(clean($request->get("post_id",0)));

        if ($post_id > 0)
        {

            orders_list_m::where([
                "user_id" => $this->user_id,
                "post_id" => $post_id,
            ])->delete();

            $output["status"] = "success";
        }

        echo json_encode($output);
        return;
    }

    public function make_orders_list_post(Request $request)
    {
        $output = [];
        $output["status"] = "error";
        $output["url"] = "";

        $post_body = clean($request->get('post_body',''));

        $current_user = $this->data["current_user"];

        $get_list_items = orders_list_m::
        select(DB::raw("
            
                posts.*,
                pair.pair_currency_name,
                cat_trans.cat_name
                
            "))
            ->join("posts","posts.post_id","=","orders_list.post_id")
            ->join("pair_currency as pair","pair.pair_currency_id","=","posts.pair_currency_id")
            ->join("category as cat","cat.cat_id","=","posts.cat_id")
            ->join("category_translate as cat_trans","cat.cat_id","=","cat_trans.cat_id")
            ->whereNull("cat.deleted_at")
            ->whereNull("cat_trans.deleted_at")
            ->where("cat_trans.lang_id",$this->lang_id)
            ->where([
                "orders_list.user_id" => $this->user_id,
                "posts.user_id" => $this->user_id,
                "posts.post_or_recommendation" => "recommendation",
                "posts.hide_post" => 0,
                "posts.post_share_id" => 0,
                "posts.post_privacy" => "public",
            ])->get();

        if(count($get_list_items))
        {

            $post_orders_ids = $get_list_items->pluck("post_id")->all();
            $post_orders_ids = array_unique($post_orders_ids);
            $post_orders_ids = json_encode($post_orders_ids);


            $new_post = posts_m::create([
                "user_id" => $this->user_id,
                "post_where" => "profile",
                "post_where_id" => $this->user_id,
                "post_or_recommendation" => "post",
                "post_type" => "text",
                "post_body" => $post_body,
                "post_orders_ids" => $post_orders_ids,
                "post_is_approved" => 1,
                "is_not_editable" => 1,
                "post_privacy" => "public",
            ]);

            orders_list_m::where("user_id",$this->user_id)->delete();

            $output["status"] = "success";
            $output["url"] = url("posts/".string_safe($current_user->full_name)."/$current_user->user_id/$new_post->post_id");
        }

        echo json_encode($output);
        return;
    }

    private function get_order_list_item($post_obj)
    {

        $li_item = "<li class='order_list_li_item' style='margin-bottom: 15px;'>";

        $li_item .= "<span class='post_li_item_label'>$post_obj->pair_currency_name</span> &nbsp;&nbsp;";
        $li_item .= "<span class='post_li_item_label'>".show_content($this->data["post_keywords"],"$post_obj->sell_or_buy"."_label")."</span> &nbsp;&nbsp;";
        $li_item .= "<span class='post_li_item_label'>$post_obj->cat_name</span> &nbsp;&nbsp;";

        $get_expected_price = ($post_obj->expected_price);
        $get_closed_price = ($post_obj->closed_price);

        $get_expected_price_points = strlen(substr(strrchr($get_expected_price, "."), 1));
        $get_closed_price_points = strlen(substr(strrchr($get_closed_price, "."), 1));

        if($get_expected_price_points > 0)
        {
            $multiply_number = 1;
            for($ind = 0;$ind < $get_expected_price_points;$ind++)
            {
                $multiply_number *= 10;
            }
            $get_expected_price = ($get_expected_price * $multiply_number);
        }

        if($get_closed_price_points > 0)
        {
            $multiply_number = 1;
            for($ind = 0;$ind < $get_closed_price_points;$ind++)
            {
                $multiply_number *= 10;
            }
            $get_closed_price = ($get_closed_price * $multiply_number);
        }

        $diff_price = ($get_expected_price - $get_closed_price);
        $diff_price = round($diff_price,3);

        $diff_price = str_replace('-','',$diff_price);


        $li_item .= "<span class='post_li_item_label recommendation_status_".$post_obj->recommendation_status."'> $post_obj->recommendation_status $diff_price p</span> &nbsp;&nbsp;";

//        $li_item .= "<br>";

        if ($post_obj->order_is_not_closed=="1")
        {
            $li_item .= "<span class='post_li_item_label'>".show_content($this->data["post_keywords"],"not_closed_label")." </span> &nbsp;&nbsp;";
        }

        if (!empty($post_obj->recommendation_status))
        {
            $li_item .= "<span class='post_li_item_label'>".show_content($this->data["post_keywords"],"closed_price_label")." : $post_obj->closed_price </span> &nbsp;&nbsp;";
        }


        $li_item .= "<span class='post_li_item_label'>".show_content($this->data["post_keywords"],"open_price_label")." : $post_obj->expected_price </span> &nbsp;&nbsp;";

        $li_item .= "<span class='btn btn-danger remove_order_list_item' data-post_id='".$post_obj->post_id."'> <i class='fa fa-times'></i> </span> &nbsp;&nbsp;";


        $li_item .= "</li>";

        return $li_item;
    }

    #endregion


    public function move_pages_to_translate()
    {

        die();
        $get_pages = pages_m::where("page_type","company")->get();
        foreach($get_pages as $key => $page_obj)
        {

            pages_translate_m::where("page_id",$page_obj->page_id)->update([
                'page_url' => $page_obj->page_url_old,
                'page_url2' => $page_obj->page_url2_old,
                'page_url3' => $page_obj->page_url3_old
            ]);

        }

        die();
    }

}
