<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\admin_controller;
use App\models\attachments_m;
use App\models\category_m;
use App\models\category_translate_m;
use App\models\langs_m;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;


class category extends admin_controller
{
    public function __construct()
    {
        parent::__construct();


    }

    public function index($cat_type="article",$parent_id = 0)
    {

        if (!check_permission($this->user_permissions,"admin/category","show_action"))
        {
            return  Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
        }

        if(!in_array($cat_type,["article","activity","book"])){
            return Redirect::to('admin/dashboard')->send();
        }

        $this->data["cat_type"]=$cat_type;

        $cond_arr=[];
        $cond_arr[]=" AND cat.cat_type='$cat_type' ";
        if($parent_id>0&&$cat_type=="city"){
            $cond_arr[]="and cat.parent_id = $parent_id ";
        }
        elseif($cat_type!="city" && $cat_type!="book"){
            $cond_arr[]="and cat.parent_id = $parent_id ";
        }


        $this->data["all_cats"] = category_m::get_all_cats(implode(" ",$cond_arr) , " order by cat.cat_order ");


        return view("admin.subviews.cats.show")->with($this->data);
    }

    public function save_cat(Request $request , $cat_type = "article" ,$cat_id = null)
    {

        if($cat_id==null){
            if (!check_permission($this->user_permissions,"admin/category","add_action"))
            {
                return Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
            }
        }
        else{
            if (!check_permission($this->user_permissions,"admin/category","edit_action"))
            {
                return Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
            }
        }

        if (is_array($this->data["all_langs"]) && count($this->data["all_langs"]) == 0)
        {
            return Redirect::to('admin/langs/save_lang')->send();
        }

        if(count($this->data["lang_ids"])==0){
            return redirect("/admin/langs/save_lang");
        }

        //cat data
        $this->data["cat_data"] = "";
        $cat_data_translate_rows=collect([]);
        $big_img_id=0;
        $small_img_id=0;
        $pdf_id=0;

        $this->data["selected_cat_type"] = $cat_type;

        $this->data["all_parent_cats"] = [];
        $this->data["all_child_cats"] = [];

        //cat parent data
        if($cat_type=="activity"){
            $this->data["all_parent_cats"] = category_m::get_all_cats(
                " AND cat.cat_type='activity' AND cat.parent_id=0 "
            );
        }
        elseif($cat_type=="article"){
            $this->data["all_parent_cats"] = category_m::get_all_cats(
                " AND cat.cat_type='article' AND cat.parent_id=0 "
            );
        }
        elseif($cat_type=="book"){
            $this->data["all_parent_cats"] = category_m::get_all_cats(
                " AND cat.cat_type='book' "
            );

            $all_parent_cats=category_m::get_all_cats(" AND cat.parent_id=0 AND cat.cat_type='activity'");
            $all_child_cats=category_m::get_all_cats(" AND cat.parent_id>0 AND cat.cat_type='activity'");

            $this->data["all_parent_cats"]=$all_parent_cats;
            $this->data["all_child_cats"]=$all_child_cats;

        }

        if ($cat_id != null){
            $cat_data=category_m::get_all_cats(" AND cat.cat_id=$cat_id");

            if(isset_and_array($cat_data)){
                $cat_data_translate_rows=category_translate_m::where("cat_id",$cat_id)->get();
                $this->data["cat_data"]=$cat_data[0];

                $big_img_id=$this->data["cat_data"]->big_img_id;
                $small_img_id=$this->data["cat_data"]->small_img_id;
                $pdf_id=$this->data["cat_data"]->pdf_id;

                $this->data["cat_data"]->cat_small_img=attachments_m::find($small_img_id);
                $this->data["cat_data"]->cat_big_img=attachments_m::find($big_img_id);
                $this->data["cat_data"]->cat_pdf_book=attachments_m::find($pdf_id);

                if ($cat_type == "book")
                {
                    $this->data["cat_data"]->main_activity = 0;
                    $main_activity = $this->data["cat_data"]->parent_id;
                    if ($main_activity > 0)
                    {
                        $get_main_activity = category_m::where("cat_id",$main_activity)->get()->first();
                        if (count($get_main_activity) > 0 ) {
                          $this->data["cat_data"]->main_activity = $get_main_activity->parent_id;
                        }

                    }
                }

            }
            else{
                abort(404);
            }


        }

        $this->data["cat_data_translate_rows"]=$cat_data_translate_rows;



        if (isset($request) && count($request->all()) >0)
        {

            $rules_values=[
                "cat_name" => $request["cat_name"],
            ];

            $rules_itself=[
                "cat_name.0" => "required",
            ];


            $allowed_slugs_flag = true;

            if($cat_type=="activity"){
                $rules_values=[
                    "cat_name" => $request["cat_name"],
                    "cat_slug" => $request["cat_slug"],
                ];

                $rules_itself=[
                    "cat_name.0" => "required",
                    "cat_slug.0" => "required",
                ];

                $slugs_arr = $request->get("cat_slug");

                if(is_array($slugs_arr)){

                    foreach ($this->data["lang_ids"] as $key => $lang_item) {

                        $translate_row_id=null;
                        if(isset($this->data["cat_data_translate_rows"][$key])){
                            $translate_row=$this->data["cat_data_translate_rows"][$key];
                            $translate_row_id=$translate_row->id;
                        }
                        

                    }
                }
            }
            elseif ($cat_type=="book")
            {
                $rules_values=[
                    "cat_name" => $request["cat_name"],
                    "cat_slug" => $request["cat_slug"],
                ];

                $rules_itself=[
                    "cat_name.0" => "required",
                    "cat_slug.0" => "required",
                ];

                $cat_name_arr = $request->get("cat_name");

                if(is_array($cat_name_arr))
                {

                    foreach ($this->data["lang_ids"] as $key => $lang_item) {

                        $current_name = array_shift($cat_name_arr);
                        if (in_array($current_name,$cat_name_arr) && !empty($current_name))
                        {
                            $this->data["success"] = "<div class = 'alert alert-danger'>The Book Name Can not Be Repeated !!</div>";
                            $allowed_slugs_flag = false;
                            break;
                        }

                        $translate_row_id=null;
                        if(isset($this->data["cat_data_translate_rows"][$key])){
                            $translate_row=$this->data["cat_data_translate_rows"][$key];
                            $translate_row_id=$translate_row->id;
                        }

                        if(!empty($request->get("cat_slug")[$key])){
                            $rules_itself["cat_name_".$lang_item->lang_title]="required|unique:category_translate,cat_name,".$translate_row_id.",id,deleted_at,NULL";
                            $rules_values["cat_name_".$lang_item->lang_title]=trim(string_safe($request->get("cat_name")[$key]));
                        }

                    }
                }

            }


            $validator = Validator::make($rules_values,$rules_itself);

            if (count($validator->messages()) == 0&&$allowed_slugs_flag==true)
            {

                #region add_or_edit
                $inputs=$request->all();

                $inputs["big_img_id"] = $this->general_save_img(
                    $request,
                    $item_id = $cat_id,
                    "big_img",
                    $new_title = $request->get("big_imgtitle"),
                    $new_alt = $request->get("big_imgalt"),
                    $upload_new_img_check = $request->get("big_img_checkbox"),
                    $upload_file_path = "/category",
                    $width = 0, $height = 0,
                    $photo_id_for_edit = $big_img_id
                );

                $inputs["pdf_id"] = $this->general_save_img(
                    $request,
                    $item_id = $cat_id,
                    "pdf_book",
                    $new_title ="",
                    $new_alt = "",
                    $upload_new_img_check = $request->get("pdf"),
                    $upload_file_path = "/books/pdf",
                    $width = 0, $height = 0,
                    $photo_id_for_edit = $pdf_id
                );

                $inputs["small_img_id"] = $this->general_save_img(
                    $request,
                    $item_id = $cat_id,
                    "small_img",
                    $new_title = $request->get("small_imgtitle"),
                    $new_alt = $request->get("small_imgalt"),
                    $upload_new_img_check = $request->get("small_img_checkbox"),
                    $upload_file_path = "/category",
                    $width = 0, $height = 0,
                    $photo_id_for_edit = $small_img_id
                );

                //slider
                $request["json_values_of_slidercat_slider_file"] = json_decode($request->get("json_values_of_slidercat_slider_file"));

                $request["cat_slider"] = $this->general_save_slider(
                    $request,
                    $field_name="cat_slider_file",
                    $width=0,
                    $height=0,
                    $new_title_arr = $request->get("cat_slider_file_title"),
                    $new_alt_arr = $request->get("cat_slider_file_alt"),
                    $json_values_of_slider=$request["json_values_of_slidercat_slider_file"],
                    $old_title_arr = $request->get("cat_slider_file_edit_title"),
                    $old_alt_arr = $request->get("cat_slider_file_edit_alt"),
                    $path="/pages/slider"
                );

                $inputs["cat_slider"] = json_encode($request["cat_slider"]);

                $inputs["cat_type"] = $cat_type;

                $return_id=0;

               /*echo "<pre>";
               var_dump($inputs);
               die;*/

                if ($cat_id != null){
                    // update
                    $check = category_m::find($cat_id)->update($inputs);

                    if ($check == true)
                    {
                        $this->data["msg"] = "<div class='alert alert-success'> Data Successfully Edit </div>";
                        //return redirect("admin/category/save_cat/article/$cat_id");
                        $return_id=$cat_id;
                    }
                    else{
                        $this->data["msg"] = "<div class='alert alert-danger'> Something Is Wrong !!!!</div>";
                    }

                }
                else{
                    // insert

                    $check = category_m::create($inputs);

                    if (is_object($check))
                    {
                        $this->data["msg"] = "<div class='alert alert-success'> Data Successfully Inserted </div>";
                        $return_id=$check->cat_id;
                    }
                    else{
                        $this->data["msg"] = "<div class='alert alert-danger'> Something Is Wrong !!!!</div>";
                    }

                }

                //add || edit category_translate

                $cat_names=$request->get("cat_name");
                $owner_names=$request->get("owner_name");
                $cat_slugs=$request->get("cat_slug");
                $cat_short_descs=$request->get("cat_short_desc");
                $cat_bodies=$request->get("cat_body");
                $cat_meta_titles=$request->get("cat_meta_title");
                $cat_meta_descs=$request->get("cat_meta_desc");
                $cat_meta_keywordss=$request->get("cat_meta_keywords");

                foreach ($this->data["lang_ids"] as $key => $lang_item) {

                    $translate_inputs=[
                        "cat_id"=>$return_id,
                        "cat_name"=>array_shift($cat_names),
                        "owner_name"=>array_shift($owner_names),
                        "cat_slug"=>trim(string_safe(array_shift($cat_slugs))),
                        "cat_short_desc"=>array_shift($cat_short_descs),
                        "cat_body"=>array_shift($cat_bodies),
                        "cat_meta_title"=>array_shift($cat_meta_titles),
                        "cat_meta_desc"=>array_shift($cat_meta_descs),
                        "cat_meta_keywords"=>array_shift($cat_meta_keywordss),
                        "lang_id"=>$lang_item->lang_id
                    ];

//                    if(empty($translate_inputs["cat_name"])||empty($translate_inputs["cat_slug"])){
//                        continue;
//                    }


                    $current_row = $cat_data_translate_rows->filter(function ($value, $key) use($lang_item) {
                        if ($value->lang_id == $lang_item->lang_id)
                        {
                            return $value;
                        }

                    });


                    if(is_object($current_row->first())){
                        //edit_translation row
                        $current_row->first()->update($translate_inputs);
                    }
                    else{
                        //add translation row
                        category_translate_m::create($translate_inputs);
                    }


                }//end foreach


                if($return_id>0){

                    return Redirect::to("admin/category/save_cat/$cat_type/$return_id")->with(["msg"=>$this->data["msg"]])->send();
                }

                #endregion

            }
            else{
                $this->data["errors"]=$validator->messages();
            }



        }//end submit


        return view("admin.subviews.cats.save")->with($this->data);
    }


    public function check_validation_for_save_cat(Request $request, $cat_id = null)
    {
        $selected_cat_type=$request->get("selected_cat_type");

        $this->data["lang_ids"]=langs_m::all();


        $all_art_translate_rows=collect([]);
        if ($cat_id != null){
            $this->data["cat_data"]=category_m::find($cat_id);
            $all_art_translate_rows=category_translate_m::where("cat_id",$cat_id)->get();
        }

        $rules_values=[
            "cat_name" => $request["cat_name"],
        ];

        $rules_itself=[
            "cat_name.0" => "required",
        ];

        if($selected_cat_type!="activity"){
            $rules_values=[
                "cat_name" => $request["cat_name"],
                "cat_slug" => $request["cat_slug"],
            ];

            $rules_itself=[
                "cat_name.0" => "required",
                "cat_slug.0" => "required",
            ];

            $slugs_arr = $request->get("cat_slug");

            if(is_array($slugs_arr)){


                foreach ($this->data["lang_ids"] as $key => $lang_item) {

                    $current_slug = array_shift($slugs_arr);
                    if (in_array($current_slug,$slugs_arr)&&!empty($current_slug))
                    {
                        $output["msg_type"] = "error";
                        $output["msg"] = array("The Slugs Can not Be Repeated !!");
                        echo json_encode($output);
                        return;

                    }

                    $current_row = $all_art_translate_rows->filter(function ($value, $key) use($lang_item) {
                        if ($value->lang_id == $lang_item->lang_id)
                        {
                            return $value;
                        }

                    });

                    $translate_row_id = null;
                    if(is_object($current_row->first())){
                        $translate_row_id =$current_row->first()->id;
                    }



                    if(!empty($request->get("cat_slug")[$key])){
                        $rules_values["cat_slug_".$lang_item->lang_title]=trim(string_safe($request->get("cat_slug")[$key]));
                        $rules_itself["cat_slug_".$lang_item->lang_title]="required|unique:category_translate,cat_slug,".$translate_row_id.",id,deleted_at,NULL";
                    }

                }
            }
        }


        $validator = Validator::make($rules_values,$rules_itself);

        \Debugbar::disable();
        $output = array();
        $output["msg_type"] = "success";
        if (count($validator->messages()) > 0)
        {
            $output["msg_type"] = "error";
        }
        $output["msg"] = $validator->messages();
        echo json_encode($output);


    }

    public function delete_cat(Request $request){

        $item_id = (int)$request->get("item_id");

        if (!check_permission($this->user_permissions,"admin/category","delete_action"))
        {
            echo json_encode(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"]);
            return;
        }

        $this->general_remove_item($request,'App\models\category_m');
        category_translate_m::where("cat_id",$item_id)->delete();
        return;
    }

}
