<?php

namespace App\Http\Controllers;

use App\Http\Controllers\front\category;
use App\models\ads_m;
use App\models\currency_rates_m;
use App\models\followers_m;
use App\models\group_workshop\workshops_count_m;
use App\models\langs_m;
use App\models\notification_m;
use App\models\pages\pages_m;
use App\models\pages\users_accounts_m;
use App\models\permissions\permissions_m;
use App\models\category_m;
use App\models\posts\orders_list_m;
use App\models\posts\posts_m;
use App\models\settings_m;
use App\models\sortable_menus_m;
use App\User;
use Cache;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;

//models
use App\models\generate_site_content_methods_m;
use App\models\site_content_m;
use App\models\attachments_m;
use Request;
use Schema;
use View;

//END models


class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;
    public $data=array();
    public $user_id=1;
    public $related_user_id=1;
    public $lang_id=1;
    public $languageLocale;
    public $book_lang_id=1;
    public $post_limit=5;

    public function __construct()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $this->data["default_timezone"] = "Africa/Cairo";

        $get_current_lang_id = \session('current_lang_id');
        $this->languageLocale = \session('language_locale');
        $get_book_lang_id = \session('book_lang_id');
//        dump($get_current_lang_id);

        if (!isset($get_current_lang_id))
        {
            \session([
                'current_lang_id' => $this->lang_id,
            ]);
        }
        else{
            $this->lang_id = $get_current_lang_id;
        }

        if (!isset($get_book_lang_id))
        {
            \session([
                'book_lang_id' => $this->book_lang_id
            ]);
        }
        else{
            $this->book_lang_id = $get_book_lang_id;
        }

        $current_user = Auth::user();
        $this->data["current_user"] = null;

        if (isset($current_user))
        {
            $this->data["current_user"] = User::get_users(" AND u.user_id = ".Auth::user()->user_id." ");
            $this->data["current_user"] = $this->data["current_user"][0];
            $this->user_id = $this->data["current_user"]->user_id;
            $this->related_user_id = $this->data["current_user"]->related_id;

            $this->data["current_user"] = $this->_get_followers_followings($this->data["current_user"]);

//            dump("id=>5 : my followers ");
//            dump($this->data["current_user"]);

            $this->data["default_timezone"] = $this->data["current_user"]->timezone;

            //check if this user can post or not
            $this->data["user_can_post"]=posts_m::check_unclosed_orders($this->user_id);
        }

        //date_default_timezone_set($this->data["default_timezone"]);

        $this->data["all_langs"] = langs_m::get_all_langs();
        $this->data["lang_ids"] = $this->data["all_langs"];
        $this->data["current_lang"]=langs_m::get_all_langs(" AND lang.lang_id=$this->lang_id ","yes");
        $this->data['language_locale'] = strtolower($this->languageLocale);
        $this->data["current_book_lang"]=langs_m::get_all_langs(" AND lang.lang_id=$this->book_lang_id ","yes");

        $all_langs_titles=convert_inside_obj_to_arr($this->data["all_langs"],"lang_title");
        if(in_array(Request::segment(1),$all_langs_titles)){
            $lang_row=langs_m::get_all_langs(" AND lang.lang_title='".Request::segment(1)."'");
            if(isset($lang_row[0])&&is_object($lang_row[0])){
                $this->lang_id=$lang_row[0]->lang_id;
                $this->data["current_lang"]=langs_m::get_all_langs(" AND lang.lang_id=$this->lang_id","yes");
            }
        }


        $this->data["lang_url_segment"]="";
        if($this->lang_id!=1){
            $this->data["lang_url_segment"]=$this->data["current_lang"]->lang_title;
        }

        $all_langs_titles = convert_inside_obj_to_arr($this->data["all_langs"], "lang_title");
        $all_segms=\Request::segments();


        $this->data["change_lang_url"]="";
        if(count($all_segms)&&in_array($all_segms[0],$all_langs_titles)){
            unset($all_segms[0]);
            $this->data["change_lang_url"]=implode("/",$all_segms);
        }
        elseif(count($all_segms)){
            $this->data["change_lang_url"]=implode("/",$all_segms);
        }

        $this->data["settings"] = settings_m::findOrFail(1);

        //csrf increase time
        $config = config('session');
        $config["lifetime"] = 7200;

//        $full_text = 'link beside https://www.facebook.com/ <img class="emojioneemoji" src="http://localhost/seoera/forex/public_html/front/js/emoji/32/1f602.png" alt="1f602.png" /><div>https://www.facebook.com/<br /></div>';
//        dump($full_text);
//        dump(search_text_url($full_text));
//        die();

//        dump($this->lang_id);


        #region get total balance

        $this->data["total_user_balance"] = 0;
        if(is_object($this->data["current_user"]))
        {
            $this->data["total_user_balance"] = (
                $this->data["current_user"]->ads_balance + $this->data["current_user"]->referrer_balance
            );
            $get_user_accounts = users_accounts_m::where("user_id",$this->user_id)->get();
            if (count($get_user_accounts))
            {
                $get_user_accounts = collect($get_user_accounts)->pluck('account_balance')->all();
                $get_user_accounts = array_sum($get_user_accounts);
                $this->data["total_user_balance"] += $get_user_accounts;
            }
        }

        #endregion


        #region get activities and childs

        $this->data['activities'] = [];

        $activities = category_m::get_all_cats(
            $additional_where = "
                AND cat.hide_cat=0
                AND cat.cat_type='activity'
                AND cat_translate.cat_name <> ''
            ",
            $order_by = " order by cat_order " ,
            $limit = "",
            $make_it_hierarchical=false,
            $default_lang_id=$this->lang_id
        );
        $activities = collect($activities);

        $parent_activities=$activities->filter(function ($activity) {
            return ($activity->parent_id==0);
        });

        $child_activities=$activities->filter(function ($activity) {
            return ($activity->parent_id>0);
        });

        $this->data["parent_activities"]=$parent_activities;
        $this->data["child_activities"]=$child_activities;

        $activities=$activities->groupBy('parent_id')->all();
        if (count($activities) && isset($activities[0]))
        {
            foreach($activities[0] as $key => $activity)
            {
                $this->data['activities'][$activity->cat_id] = $activity;
                $this->data['activities'][$activity->cat_id]->childs = [];

                if(isset($activities[$activity->cat_id]))
                {
                    $this->data['activities'][$activity->cat_id]->childs = $activities[$activity->cat_id]->all();
                }

            }
        }
        #endregion

        #region get static pages

        $this->data["all_static_pages"] = [];
        $this->data["static_pages"] = [];
        $this->data["sidebar_pages"] = [];
        $this->data["privacy_page"] = "";

        $static_pages = pages_m::get_pages(
                $additional_where = "
                    AND page.page_type = 'default'
                    AND page.hide_page = 0
                    AND page.show_in_existing = 0
                ",
                $order_by = " order by page.page_id " ,
                $limit = "",
                $check_self_translates = false,
                $default_lang_id=$this->lang_id,
                $load_slider=false
            );


        if (count($static_pages))
        {
            $static_pages = collect($static_pages);
            $this->data["static_pages"] = $static_pages->all();
            $this->data["sidebar_pages"] = $static_pages->where("show_in_sidebar",1)->all();
            $this->data["privacy_page"] = $static_pages->where("show_in_privacy",1)->first();
        }

        #endregion

        #region Latest News
        $this->data["latest_news"] = pages_m::get_pages(
            "
                        AND page.page_type = 'news'
                        AND page.hide_page = 0
                        #AND page.show_in_homepage = 1
                        AND page_trans.page_title <> ''
                    ",
            $order_by = " order by page.page_id desc ",
            $limit = " limit 10 ",
            $check_self_translates = false,
            $default_lang_id = $this->lang_id
        );
//        dump($this->data["latest_news"]);
        #endregion

        #region Random Friends

        $user_id = $this->user_id;

        $current_user = $this->data["current_user"];
        $all_except_user_ids = [$user_id];
        $followers_ids = [];
        $followings_ids = [];

        $this->data["random_users"] = [];
        $this->data["random_users_indexes"] = [];

        if (is_object($current_user))
        {
            if (count($current_user->followers))
            {
                $followers_ids = $current_user->followers;
                $all_except_user_ids = array_merge($all_except_user_ids,$followers_ids);
            }
            if (count($current_user->following))
            {
                $followings_ids = $current_user->following;
                $all_except_user_ids = array_merge($all_except_user_ids,$followings_ids);
            }

//        dump($all_except_user_ids);

            $random_users = User::
            leftjoin("attachments","logo_id","=","attachments.id")
                ->whereNotIn("user_id",$all_except_user_ids)
                ->where("user_type","=","user")
                ->where("user_can_login",1)
                ->where("user_active",1)
                ->where("user_is_blocked",0)
                ->where("remember_token",'!=',null)
                ->orderBy('user_id','desc')
                ->select("attachments.path as logo_path","users.*")
                ->take(100)->get()->all();

            if (count($random_users))
            {
                $this->data["random_users"] = $random_users;
                $rand_val = 3;
                if (count($random_users) < 3)
                {
                    $rand_val = count($random_users);
                }

                $random_users_indexes = array_rand($random_users,$rand_val);
                if (!is_array($random_users_indexes))
                {
                    $random_users_indexes = [$random_users_indexes];
                }
                $this->data["random_users_indexes"] = $random_users_indexes;
            }

        }


//        dump($this->data["random_users"]);
//        dump($this->data["random_users_indexes"]);

        #endregion

        #region get all ads

        $get_ads = ads_m::get_ads_img();
        $get_ads = collect($get_ads)->groupBy("position")->all();
        $this->data["get_ads"] = $get_ads;

        #endregion

        $slider_arr = array();
        $slider_arr["banks"]=["slider1"];
        $this->general_get_content(
            [
                "general_static_keywords","banks","logo_and_icon","pages_seo",
                "validation_messages","user_homepage_keywords","post_keywords",
                "user_profile_keywords","email_page","intro_keywords"
            ]
            ,$slider_arr
        );

        if(isset($this->data["logo_and_icon"]->logo) && isset($this->data["logo_and_icon"]->logo->path))
        {
            define("SITE_LOGO",$this->data["logo_and_icon"]->logo->path);
        }
        else{
            define("SITE_LOGO","");
        }

        define('welcome_label',show_content($this->data["email_page"],"welcome_label"));
        define('register_email_body',show_content($this->data["email_page"],"register_email_body"));
        define('register_email_to_verify_account',show_content($this->data["email_page"],"register_email_to_verify_account"));
        define('register_email_click_label',show_content($this->data["email_page"],"register_email_click_label"));
        define('success_verification_email_body',show_content($this->data["email_page"],"success_verification_email_body"));
        define('success_verification_login_now',show_content($this->data["email_page"],"success_verification_login_now"));
        define('register_email_footer',show_content($this->data["email_page"],"register_email_footer"));
        define('reset_password_email_body',show_content($this->data["email_page"],"reset_password_email_body"));
        define('reset_password_link',show_content($this->data["email_page"],"reset_password_link"));
        define('social_imgs',json_encode(show_content($this->data["email_page"],"social_imgs")));
        define('social_links',json_encode(show_content($this->data["email_page"],"social_links")));
        define('email_social_imgs',json_encode(show_content($this->data["email_page"],"email_social_imgs")));
        define('email_social_links',json_encode(show_content($this->data["email_page"],"email_social_links")));
        define('copyright',json_encode(show_content($this->data["email_page"],"copyright")));

        $this->data["meta_title"]=show_content($this->data["pages_seo"],"homepage_meta_title");
        $this->data["meta_desc"]=show_content($this->data["pages_seo"],"homepage_meta_description");
        $this->data["meta_keywords"]=show_content($this->data["pages_seo"],"homepage_meta_keywords");
        $this->data["og_title"]=show_content($this->data["pages_seo"],"homepage_meta_title");
        $this->data["og_description"]=show_content($this->data["pages_seo"],"homepage_meta_description");
        $this->data["og_img"]=url(SITE_LOGO);
        $this->data["og_url"]= url("") ;

    }

    /**
     * @param $request >> received by form
     * @param int $user_id >> from current session
     * @param $file_name >> from input file name
     * @param $folder >> /folder_name under uploads
     * @param int $width
     * @param int $height
     * @param array $ext_arr >> additional array of allowed extensions
     * @param bool $return_only_name
     * @param string $absolute_upload_path
     * @return array|string >> array if uploaded
     */
    public function cms_upload($request, $user_id = 0, $file_name, $folder, $width = 0, $height = 0, $ext_arr = array(), $return_only_name=false, $absolute_upload_path="")
    {

        $uploaded = array();
        if (!empty($file_name) && isset($request))
        {

            if ($file_objs = $request->file($file_name))
            {
                if(!is_array($file_objs)){
                    $file_objs=array($file_objs);
                }

                foreach ($file_objs as $key => $file_obj) {

                    if ($file_obj == null){
                        continue;
                    }

                    $uploaded_file_ext = $file_obj->getClientOriginalExtension();
                    $uploaded_origin_file_name = $file_obj->getClientOriginalName().'.'.$uploaded_file_ext;
                    $uploaded_file_encrypted_name = md5($user_id.time().$file_name.$file_obj->getClientOriginalName()).".".$uploaded_file_ext;
                    $uploaded_file_path = "uploads".$folder;

                    $uploaded_full_path_to_file = $uploaded_file_path.'/'.$uploaded_file_encrypted_name;

                    if ($absolute_upload_path != "")
                    {
                        $uploaded_file_path = $absolute_upload_path;
                    }

                    if (in_array($uploaded_file_ext, array("mp3","mp4","jpeg","png","jpg","MP4","JPEG","PNG","JPG","xls","XLS","doc","docx","zip","rar","xlsx","XLSX","csv","CSV","pdf","PDF","gif","GIF"))||(count($ext_arr)>0 && in_array($uploaded_file_ext, $ext_arr)))
                    {
                        $file_obj->move($uploaded_file_path,$uploaded_file_encrypted_name);

                        if ($width >0 && $height >0)
                        {
                            $img = Image::make(($uploaded_full_path_to_file))->resize($width, $height);
                            $img->save(($uploaded_full_path_to_file),70);
                        }

                        if ($return_only_name == true || $return_only_name == "true")
                        {
                            $uploaded[] = $uploaded_file_encrypted_name;
                        }
                        else{
                            $uploaded[] = $uploaded_full_path_to_file;
                        }

                    }
                    else
                    {
                        return "not allowed type";
                    }

                }


            }
            else{
                return "There is not file to upload";
            }


        }
        else{
            return "There is not input file or comming request !!";
        }

        return $uploaded;

    }


    /**
     * @param $request >> received by form
     * @param null $item_id >> null for insert || id for edit
     * @param $img_file_name >> from input file name
     * @param $new_title
     * @param $new_alt
     * @param $upload_new_img_check
     * @param $upload_file_path >> /folder_name
     * @param $width
     * @param $height
     * @param $photo_id_for_edit
     * @param array $ext_arr
     * @return int|string
     */
    public function general_save_img($request , $item_id=null, $img_file_name, $new_title, $new_alt, $upload_new_img_check, $upload_file_path, $width, $height, $photo_id_for_edit, $ext_arr=array())
    {

        $new_title=($new_title==null)?"":$new_title;
        $new_alt=($new_alt==null)?"":$new_alt;

        //$item_id could be pro id , cat_id any thing
        $photo_id="not_enter";

        $upload_img=$this->cms_upload($request,$this->user_id,$img_file_name,$upload_file_path,$width,$height,$ext_arr);

        if ($item_id==null)
        {
            //save attachment first

            if ((!(count($upload_img)>0) && !is_array($upload_img)) || (!(count($upload_img)>0) && is_array($upload_img)) )
            {
                return 0;
            }

            //save main photo
            $upload_img=$upload_img[0];

            $photo_id=attachments_m::save_img(null,$new_title,$new_alt,$upload_img);

            return $photo_id;
        }//end check of upload file


        if ($item_id!=null&&$photo_id_for_edit>0) {
            //edit photo data
            //update image info

            if (is_array($upload_img) && $upload_new_img_check=="on")
            {
                $photo_id=attachments_m::save_img($photo_id_for_edit,$new_title,$new_alt,$upload_img[0]);
                return $photo_id;
            }
            $photo_id=attachments_m::save_img($photo_id_for_edit,$new_title,$new_alt);
        }

        if ($item_id!=null&&$photo_id_for_edit==0) {
            //add new photo data if edit item has new image
            if (is_array($upload_img) && $upload_new_img_check=="on")
            {
                $photo_id=attachments_m::save_img($photo_id_for_edit,$new_title,$new_alt,$upload_img[0]);
                return $photo_id;
            }
            elseif (is_array($upload_img) && count($upload_img) > 0)
            {
                $photo_id=attachments_m::save_img($photo_id_for_edit,$new_title,$new_alt,$upload_img[0]);
                return $photo_id;
            }
            else{
                return $photo_id_for_edit;
            }

        }

        return $photo_id;
    }


    /**
     * @param $request >> from form
     * @param string $field_name >> form_input_file_name
     * @param int $width
     * @param int $height
     * @param $new_title_arr
     * @param $new_alt_arr
     * @param string $json_values_of_slider
     * @param string $path >> /folder_name
     * @param string $old_title_arr old values of existing imgages
     * @param string $old_alt_arr old values of existing images
     * @return array|string
     */
    public function general_save_slider($request, $field_name="", $width=0, $height=0, $new_title_arr, $new_alt_arr, $json_values_of_slider="",$old_title_arr,$old_alt_arr,$path="",$ext_arr = [])
    {

        if ($path=="") {
            $path="/".$field_name;
        }
        //upload new files
        $slider_file = $this->cms_upload($request , $this->user_id,"$field_name",$folder="$path",$width,$height,$ext_arr);//array

        //update old_photos
        if (is_array($json_values_of_slider)&&count($json_values_of_slider)) {
            foreach ($json_values_of_slider as $key => $value) {
                $save_img_title="";
                if(isset($old_title_arr[$key])){
                    $save_img_title=$old_title_arr[$key];
                }

                $save_img_alt="";
                if(isset($old_alt_arr[$key])){
                    $save_img_alt=$old_alt_arr[$key];
                }

                $old_photo_id = attachments_m::save_img($value,$save_img_title,$save_img_alt);
            }
        }

        //add new photos
        if (count($slider_file)&&is_array($slider_file)) {
            foreach ($slider_file as $key => $value) {
                $save_img_title="";
                if(isset($new_title_arr[$key])){
                    $save_img_title=$new_title_arr[$key];
                }

                $save_img_alt="";
                if(isset($new_alt_arr[$key])){
                    $save_img_alt=$new_alt_arr[$key];
                }

                $json_values_of_slider[] = attachments_m::save_img(null,$save_img_title,$save_img_alt,$value);
            }//end foreach
        }

        return $json_values_of_slider;
    }

    /**
     * @param arr_of_str $content_row_title array of content_titles
     * important note the row you can fetch coreectly is the row the saved
     * by general_save_content
     *
     * $slider_imgs_field_arr== $slider_imgs_arr["edit_index_page"]=array("slider1","slider2","slider3")
     *
     */
    public function general_get_content($content_row_title=array(),$slider_imgs_field_arr=array()) {

        foreach ($content_row_title as $key => $title) {

            $cache_data=Cache::get($title."_".$this->lang_id);
            if($cache_data!=null){
                $this->data["$title"]=json_decode($cache_data);
                continue;
            }

            $this->data["$title"]="";
            $edit_content_row=site_content_m::where([
                "content_title"=>"$title",
                "lang_id"=>"$this->lang_id"
            ])->first();
            if(!is_object($edit_content_row)){
                continue;
            }
            $edit_content_row=  json_decode($edit_content_row->content_json);

            $generate_site_content_method=generate_site_content_methods_m::where("method_name","=","$title")->first();

            if(!is_object($generate_site_content_method)){
                return;
            }

            $generate_site_content_method=json_decode($generate_site_content_method->method_requirments);

            //get imgs data
            //check if there is imgs in $edit_content_row
            if (isset($edit_content_row->img_ids)&&  is_object($edit_content_row->img_ids)) {
                foreach ($edit_content_row->img_ids as $img_key => $img_id) {
                    $img_var_name=$img_key;
                    $edit_content_row->$img_var_name=attachments_m::find($img_id);
                    if(!is_object($edit_content_row->$img_var_name)){
                        $edit_content_row->$img_var_name=new \stdClass();
                        $edit_content_row->$img_var_name->path="";
                        $edit_content_row->$img_var_name->title="";
                        $edit_content_row->$img_var_name->alt="";
                    }
                }
            }

            //get slider data

            if (isset($slider_imgs_field_arr["$title"])&&  is_array($slider_imgs_field_arr["$title"])) {
                foreach ($slider_imgs_field_arr["$title"] as $key => $slider) {

                    if(!isset($edit_content_row->$slider)){
                        continue;
                    }

                    $slider_imgs_ids=$edit_content_row->$slider->img_ids;
                    $edit_content_row->$slider->imgs = array();

                    if (is_array($slider_imgs_ids) && count($slider_imgs_ids)) {
                        $edit_content_row->$slider->imgs=attachments_m::get_imgs_from_arr($slider_imgs_ids);
                    }

                }
            }

            //get selected data
            if(isset($generate_site_content_method->select_fields->fields)&&is_array($generate_site_content_method->select_fields->fields)){
                $select_fields=$generate_site_content_method->select_fields->fields;
                $select_tables=$generate_site_content_method->select_fields->tables;

                foreach ($select_fields as $key => $field) {
                    if(isset($edit_content_row->$field)){
                        //get field_value,model
                        $field_value=$edit_content_row->$field;
                        $model_name=$select_tables->$field->model;

                        $edit_content_row->$field=$model_name::find($field_value);
                    }
                }


            }
            //END get selected data

            $this->data["$title"]=$edit_content_row;

            Cache::put($title."_".$this->lang_id,json_encode($edit_content_row),60*60*30);

        }//end foreach

    }

    /**
     * @param array $emails >> array("aa@aa.com","cc@cc.com")
     * @param string $data >> string for default , array for advanced view
     * @param string $subject >> subject of your emails
     * @param string $path_to_file >> valid full path to attachment if exist
     * @return mixed
     */
    public function _send_email_to_custom($emails = array() , $data = "" , $subject = "", $sender = "info@invstoc.com" , $path_to_file = "",$email_template ="" )
    {

        if (is_array($emails) && count($emails) > 0)
        {

            if (is_array($data) && count($data) > 0)
            {
                if (!empty($email_template))
                {
                    $view = "$email_template";
                }
                else{
                    $view = "email.advanced";
                }
            }
            else{
                $data = ["default"=>$data];
                if (!empty($email_template))
                {
                    $view = "$email_template";
                }
                else{
                    $view = "email.default";
                }
            }

            $check = Mail::send($view,$data,function ($message) use ($emails , $subject, $sender, $path_to_file) {

                // changed once for every site
                $message->from($address = $sender);
                $message->sender($address = $sender);

                if ($path_to_file != "" && is_file($path_to_file))
                {
                    $message->attach($path_to_file);
                }

                $message->to($emails)->subject($subject);

            });

        }

        return Mail:: failures();


    }

    public function _send_email_to_all_users_type($user_type = "" , $data = "" , $subject = "", $sender = "info@invstoc.com" , $path_to_file = "", $email_template ="" )
    {
        if (!empty($user_type))
        {

            if ($user_type == "admin")
            {
                $all_users = User::get_users(" AND u.user_type = 'admin' OR u.user_type = 'dev' ");
            }
            else{
                $all_users = User::where("user_type",$user_type)->get()->all();
            }

            if (is_array($all_users) && count($all_users))
            {
                $all_users_email = convert_inside_obj_to_arr($all_users,'email');
                if (is_array($all_users_email) && count($all_users_email))
                {
                    $this->_send_email_to_custom($emails = $all_users_email , $data, $subject, $sender, $path_to_file,$email_template);
                }

            }

        }

    }

    public function general_ajax_loader($model="",$model_static_function="",$func_params=[],$return_data_var_name="rows",$view_path=""){

        if($model==""||$model_static_function==""||$view_path==""){
            return "";
        }

        $this->data["$return_data_var_name"]=call_user_func_array("$model::$model_static_function",$func_params);

        return View::make($view_path,$this->data)->render();
    }

    public function send_user_notification($not_title = "" , $not_type = "" , $user_id = "")
    {
        if (!empty($not_title) && !empty($user_id))
        {
            notification_m::create([
                "not_title" => $not_title,
                "not_type" => $not_type,
                "not_to_user_id" => $user_id
            ]);
        }
    }

    public function send_all_user_type_notifications($not_title = "" , $not_type = "" , $user_type = "")
    {
        if (!empty($not_title) && !empty($user_type))
        {

            if ($user_type == "admin")
            {
                $all_users = User::get_users(" AND u.user_type = 'admin' OR u.user_type = 'factory_admin' ");
            }
            elseif ($user_type == "branch")
            {
                $all_users = User::get_users(" AND u.user_type = 'branch' OR u.user_type = 'branch_admin' ");
            }
            else{
                $all_users = User::where("user_type",$user_type)->get()->all();
            }


            foreach($all_users as $key => $user)
            {
                $this->send_user_notification($not_title, $not_type, $user->user_id);
            }

        }
    }

    public function get_user_permissions()
    {

        $get_permissions = permissions_m::get_permissions( " where per.user_id =  ".$this->user_id." " );
        $get_permissions = collect($get_permissions)->groupBy('page_name');
        $get_permissions = $get_permissions->all();

        return $get_permissions;
    }

    public function check_user_permission($page = "" , $action = "")
    {
        if (!empty($page) && !empty($action))
        {
            $get_permission = permissions_m::get_permissions( " where per.user_id =  ".$this->user_id."
                        AND per_page.page_name = '$page'" );
            if (is_array($get_permission) && count($get_permission))
            {
                $get_permission = $get_permission[0];
                if (isset($get_permission->$action) && $get_permission->$action)
                {
                    return true;
                }

                $additional_permissions=json_decode($get_permission->additional_permissions);
                if (is_array($additional_permissions)&&in_array($action,$additional_permissions))
                {
                    return true;
                }

            }
        }
        return false;
    }

    public function cleaning_input($request_data, $except = array())
    {
        foreach($request_data as $key => $value)
        {
            if (count($except) && in_array($key,$except))
            {
                continue;
            }
            $request_data[$key] = clean($value);
        }

        return $request_data;
    }


    public function ckeditor_upload(){
        \Debugbar::disable();

        if(isset($_FILES['upload'])){
            if(!file_exists("uploads/ckeditor")){
                mkdir("uploads/ckeditor");
            }

            $filen = $_FILES['upload']['tmp_name'];
            $current_time = Carbon::now()->toDateTimeString();
            $current_time = str_replace(':','-',$current_time);

            $file_name_ar = $_FILES['upload']['name'];
            $file_name_ar = explode('.',$file_name_ar);

            $new_file_name = $file_name_ar[0]." - $current_time".'.'.$file_name_ar[1];

            $con_images = "uploads/ckeditor/$new_file_name";
            move_uploaded_file($filen, $con_images );

            $url = url($con_images);

            $funcNum = $_GET['CKEditorFuncNum'] ;
            // Optional: instance name (might be used to load a specific configuration file or anything else).
            $CKEditor = $_GET['CKEditor'] ;
            // Optional: might be used to provide localized messages.
            $langCode = $_GET['langCode'] ;

            // Usually you will only assign something here if the file could not be uploaded.
            $message = '';
            echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
        }
    }

    public function ckeditor_browse(){
        \Debugbar::disable();

        $this->data["search_for_file"]=Input::get("search_for_file");


        return view("front.subviews.browse_files",$this->data);
    }


    #region Customized functions related to current user or visited user

    public function _get_user_cat_items($user_obj)
    {
        $user_obj->cat_items = "";

        if (!empty($user_obj->cat_id))
        {
            $cat_ids = json_decode($user_obj->cat_id);
            if (count($cat_ids))
            {
                $cat_ids = implode(',',$cat_ids);
                $get_cats = category_m::get_all_cats(
                    $additional_where = " AND cat.cat_id in ($cat_ids) ",
                    $order_by = "" ,
                    $limit = "",
                    $make_it_hierarchical=false,
                    $default_lang_id=$this->lang_id
                );

                if (count($get_cats))
                {
                    $get_cats_names = convert_inside_obj_to_arr($get_cats,"cat_name");
                    $get_cats_names = implode('<br>',$get_cats_names);
                    $user_obj->cat_items = $get_cats_names;
                }

            }
        }

        return $user_obj;
    }

    public function _get_followers_followings($user_obj)
    {
        // you follow others
        $user_obj->followers = [];

        $user_obj->followers = followers_m::
            select(DB::raw("followers.from_user_id"))
            ->join("users as user_obj","user_obj.user_id","=","followers.from_user_id")
            ->where("followers.to_user_id",$user_obj->user_id)
            ->where("user_obj.user_type","user")
            ->get()->pluck('from_user_id')->all();

        if (count($user_obj->followers))
        {
            $user_obj->followers = array_unique($user_obj->followers);
        }

        // other follow you
        $user_obj->following = [];
        $user_obj->following = followers_m::
            select(DB::raw("followers.to_user_id"))
                ->join("users as user_obj","user_obj.user_id","=","followers.to_user_id")
                ->where("followers.from_user_id",$user_obj->user_id)
                ->where("user_obj.user_type","user")
                ->get()->pluck('to_user_id')->all();

        if (count($user_obj->following))
        {
            $user_obj->following = array_unique($user_obj->following);
        }

        return $user_obj;
    }

    public function _get_followers_followings_data($user_obj)
    {
        $this->data["followers_data"] = [];
        $this->data["following_data"] = [];

        if (count($user_obj->followers))
        {
            $followers_ids = $user_obj->followers;
            $followers_ids = array_unique($followers_ids);
            $followers_ids = implode(',',$followers_ids);
            $this->data["followers_data"] = User::get_users("
                AND u.user_id in ($followers_ids)
            ");
        }

        if (count($user_obj->following))
        {
            $following_ids = $user_obj->following;
            $following_ids = array_unique($following_ids);
            $following_ids = implode(',',$following_ids);
            $this->data["following_data"] = User::get_users("
                AND u.user_id in ($following_ids)
            ");
        }

    }

    public function _get_user_followers($user_obj)
    {

        $return_users = [];

        $followers = followers_m::
            join("users","followers.from_user_id","=","users.user_id")
            ->where("to_user_id",$user_obj->user_id)->get()->all();
        $followings = followers_m::
            join("users","followers.to_user_id","=","users.user_id")
            ->where("from_user_id",$user_obj->user_id)->get()->all();

        $return_users = array_merge($followers,$followings);
        if (count($return_users))
        {
            $return_users = collect($return_users)->chunk(20);
        }

        return $return_users;

    }


    #endregion

    function barcode($code) {

        $filepath="";
        $text="";
        $size="20";
        $orientation="horizontal";
        $code_type="code128";
        $print=false;
        $SizeFactor=1;

        $code_string = "";
        // Translate the $text into barcode the correct $code_type
        if ( in_array(strtolower($code_type), array("code128", "code128b")) ) {
            $chksum = 104;
            // Must not change order of array elements as the checksum depends on the array's key to validate final code
            $code_array = array(" "=>"212222","!"=>"222122","\""=>"222221","#"=>"121223","$"=>"121322","%"=>"131222","&"=>"122213","'"=>"122312","("=>"132212",")"=>"221213","*"=>"221312","+"=>"231212",","=>"112232","-"=>"122132","."=>"122231","/"=>"113222","0"=>"123122","1"=>"123221","2"=>"223211","3"=>"221132","4"=>"221231","5"=>"213212","6"=>"223112","7"=>"312131","8"=>"311222","9"=>"321122",":"=>"321221",";"=>"312212","<"=>"322112","="=>"322211",">"=>"212123","?"=>"212321","@"=>"232121","A"=>"111323","B"=>"131123","C"=>"131321","D"=>"112313","E"=>"132113","F"=>"132311","G"=>"211313","H"=>"231113","I"=>"231311","J"=>"112133","K"=>"112331","L"=>"132131","M"=>"113123","N"=>"113321","O"=>"133121","P"=>"313121","Q"=>"211331","R"=>"231131","S"=>"213113","T"=>"213311","U"=>"213131","V"=>"311123","W"=>"311321","X"=>"331121","Y"=>"312113","Z"=>"312311","["=>"332111","\\"=>"314111","]"=>"221411","^"=>"431111","_"=>"111224","\`"=>"111422","a"=>"121124","b"=>"121421","c"=>"141122","d"=>"141221","e"=>"112214","f"=>"112412","g"=>"122114","h"=>"122411","i"=>"142112","j"=>"142211","k"=>"241211","l"=>"221114","m"=>"413111","n"=>"241112","o"=>"134111","p"=>"111242","q"=>"121142","r"=>"121241","s"=>"114212","t"=>"124112","u"=>"124211","v"=>"411212","w"=>"421112","x"=>"421211","y"=>"212141","z"=>"214121","{"=>"412121","|"=>"111143","}"=>"111341","~"=>"131141","DEL"=>"114113","FNC 3"=>"114311","FNC 2"=>"411113","SHIFT"=>"411311","CODE C"=>"113141","FNC 4"=>"114131","CODE A"=>"311141","FNC 1"=>"411131","Start A"=>"211412","Start B"=>"211214","Start C"=>"211232","Stop"=>"2331112");
            $code_keys = array_keys($code_array);
            $code_values = array_flip($code_keys);
            for ( $X = 1; $X <= strlen($text); $X++ ) {
                $activeKey = substr( $text, ($X-1), 1);
                $code_string .= $code_array[$activeKey];
                $chksum=($chksum + ($code_values[$activeKey] * $X));
            }
            $code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];
            $code_string = "211214" . $code_string . "2331112";
        } elseif ( strtolower($code_type) == "code128a" ) {
            $chksum = 103;
            $text = strtoupper($text); // Code 128A doesn't support lower case
            // Must not change order of array elements as the checksum depends on the array's key to validate final code
            $code_array = array(" "=>"212222","!"=>"222122","\""=>"222221","#"=>"121223","$"=>"121322","%"=>"131222","&"=>"122213","'"=>"122312","("=>"132212",")"=>"221213","*"=>"221312","+"=>"231212",","=>"112232","-"=>"122132","."=>"122231","/"=>"113222","0"=>"123122","1"=>"123221","2"=>"223211","3"=>"221132","4"=>"221231","5"=>"213212","6"=>"223112","7"=>"312131","8"=>"311222","9"=>"321122",":"=>"321221",";"=>"312212","<"=>"322112","="=>"322211",">"=>"212123","?"=>"212321","@"=>"232121","A"=>"111323","B"=>"131123","C"=>"131321","D"=>"112313","E"=>"132113","F"=>"132311","G"=>"211313","H"=>"231113","I"=>"231311","J"=>"112133","K"=>"112331","L"=>"132131","M"=>"113123","N"=>"113321","O"=>"133121","P"=>"313121","Q"=>"211331","R"=>"231131","S"=>"213113","T"=>"213311","U"=>"213131","V"=>"311123","W"=>"311321","X"=>"331121","Y"=>"312113","Z"=>"312311","["=>"332111","\\"=>"314111","]"=>"221411","^"=>"431111","_"=>"111224","NUL"=>"111422","SOH"=>"121124","STX"=>"121421","ETX"=>"141122","EOT"=>"141221","ENQ"=>"112214","ACK"=>"112412","BEL"=>"122114","BS"=>"122411","HT"=>"142112","LF"=>"142211","VT"=>"241211","FF"=>"221114","CR"=>"413111","SO"=>"241112","SI"=>"134111","DLE"=>"111242","DC1"=>"121142","DC2"=>"121241","DC3"=>"114212","DC4"=>"124112","NAK"=>"124211","SYN"=>"411212","ETB"=>"421112","CAN"=>"421211","EM"=>"212141","SUB"=>"214121","ESC"=>"412121","FS"=>"111143","GS"=>"111341","RS"=>"131141","US"=>"114113","FNC 3"=>"114311","FNC 2"=>"411113","SHIFT"=>"411311","CODE C"=>"113141","CODE B"=>"114131","FNC 4"=>"311141","FNC 1"=>"411131","Start A"=>"211412","Start B"=>"211214","Start C"=>"211232","Stop"=>"2331112");
            $code_keys = array_keys($code_array);
            $code_values = array_flip($code_keys);
            for ( $X = 1; $X <= strlen($text); $X++ ) {
                $activeKey = substr( $text, ($X-1), 1);
                $code_string .= $code_array[$activeKey];
                $chksum=($chksum + ($code_values[$activeKey] * $X));
            }
            $code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];
            $code_string = "211412" . $code_string . "2331112";
        } elseif ( strtolower($code_type) == "code39" ) {
            $code_array = array("0"=>"111221211","1"=>"211211112","2"=>"112211112","3"=>"212211111","4"=>"111221112","5"=>"211221111","6"=>"112221111","7"=>"111211212","8"=>"211211211","9"=>"112211211","A"=>"211112112","B"=>"112112112","C"=>"212112111","D"=>"111122112","E"=>"211122111","F"=>"112122111","G"=>"111112212","H"=>"211112211","I"=>"112112211","J"=>"111122211","K"=>"211111122","L"=>"112111122","M"=>"212111121","N"=>"111121122","O"=>"211121121","P"=>"112121121","Q"=>"111111222","R"=>"211111221","S"=>"112111221","T"=>"111121221","U"=>"221111112","V"=>"122111112","W"=>"222111111","X"=>"121121112","Y"=>"221121111","Z"=>"122121111","-"=>"121111212","."=>"221111211"," "=>"122111211","$"=>"121212111","/"=>"121211121","+"=>"121112121","%"=>"111212121","*"=>"121121211");
            // Convert to uppercase
            $upper_text = strtoupper($text);
            for ( $X = 1; $X<=strlen($upper_text); $X++ ) {
                $code_string .= $code_array[substr( $upper_text, ($X-1), 1)] . "1";
            }
            $code_string = "1211212111" . $code_string . "121121211";
        } elseif ( strtolower($code_type) == "code25" ) {
            $code_array1 = array("1","2","3","4","5","6","7","8","9","0");
            $code_array2 = array("3-1-1-1-3","1-3-1-1-3","3-3-1-1-1","1-1-3-1-3","3-1-3-1-1","1-3-3-1-1","1-1-1-3-3","3-1-1-3-1","1-3-1-3-1","1-1-3-3-1");
            for ( $X = 1; $X <= strlen($text); $X++ ) {
                for ( $Y = 0; $Y < count($code_array1); $Y++ ) {
                    if ( substr($text, ($X-1), 1) == $code_array1[$Y] )
                        $temp[$X] = $code_array2[$Y];
                }
            }
            for ( $X=1; $X<=strlen($text); $X+=2 ) {
                if ( isset($temp[$X]) && isset($temp[($X + 1)]) ) {
                    $temp1 = explode( "-", $temp[$X] );
                    $temp2 = explode( "-", $temp[($X + 1)] );
                    for ( $Y = 0; $Y < count($temp1); $Y++ )
                        $code_string .= $temp1[$Y] . $temp2[$Y];
                }
            }
            $code_string = "1111" . $code_string . "311";
        } elseif ( strtolower($code_type) == "codabar" ) {
            $code_array1 = array("1","2","3","4","5","6","7","8","9","0","-","$",":","/",".","+","A","B","C","D");
            $code_array2 = array("1111221","1112112","2211111","1121121","2111121","1211112","1211211","1221111","2112111","1111122","1112211","1122111","2111212","2121112","2121211","1121212","1122121","1212112","1112122","1112221");
            // Convert to uppercase
            $upper_text = strtoupper($text);
            for ( $X = 1; $X<=strlen($upper_text); $X++ ) {
                for ( $Y = 0; $Y<count($code_array1); $Y++ ) {
                    if ( substr($upper_text, ($X-1), 1) == $code_array1[$Y] )
                        $code_string .= $code_array2[$Y] . "1";
                }
            }
            $code_string = "11221211" . $code_string . "1122121";
        }
        // Pad the edges of the barcode
        $code_length = 20;
        if ($print) {
            $text_height = 20;
        } else {
            $text_height = 0;
        }

        for ( $i=1; $i <= strlen($code_string); $i++ ){
            $code_length = $code_length + (integer)(substr($code_string,($i-1),1));
        }
        if ( strtolower($orientation) == "horizontal" ) {
            $img_width = $code_length*$SizeFactor;
            $img_height = $size;
        } else {
            $img_width = $size;
            $img_height = $code_length*$SizeFactor;
        }
        $image = imagecreate($img_width, $img_height + $text_height);
        $black = imagecolorallocate ($image, 0, 0, 0);
        $white = imagecolorallocate ($image, 255, 255, 255);
        imagefill( $image, 0, 0, $white );
        if ( $print ) {
            imagestring($image, 5, 31, $img_height, $text, $black );
        }
        $location = 10;
        for ( $position = 1 ; $position <= strlen($code_string); $position++ ) {
            $cur_size = $location + ( substr($code_string, ($position-1), 1) );
            if ( strtolower($orientation) == "horizontal" )
                imagefilledrectangle( $image, $location*$SizeFactor, 0, $cur_size*$SizeFactor, $img_height, ($position % 2 == 0 ? $white : $black) );
            else
                imagefilledrectangle( $image, 0, $location*$SizeFactor, $img_width, $cur_size*$SizeFactor, ($position % 2 == 0 ? $white : $black) );
            $location = $cur_size;
        }

        // Draw barcode to the screen or save in a file
        if ( $filepath=="" ) {
            header ('Content-type: image/png');
            imagepng($image);
            imagedestroy($image);
        } else {
            imagepng($image,$filepath);
            imagedestroy($image);
        }
    }
}
