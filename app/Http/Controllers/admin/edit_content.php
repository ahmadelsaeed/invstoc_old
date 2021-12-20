<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\admin_controller;
use App\Http\Controllers\dashbaord_controller;
use App\models\attachments_m;
use App\models\generate_site_content_methods_m;
use App\models\langs_m;
use Cache;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\models\site_content_m;
use Illuminate\Support\Facades\Redirect;

class edit_content extends admin_controller
{
    public function __construct(){
        parent::__construct();


        $this->data["disable_hide_input"]="";
    }

    public function show_methods()
    {
        if (!check_permission($this->user_permissions,"admin/category","show_action"))
        {
            return Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
        }

        $this->data["methods"] = generate_site_content_methods_m::all();
        return view("admin.subviews.edit_content.show_methods",$this->data);
    }

    public function check_function(Request $request,$lang_id,$slug){

        if (!
            (
                check_permission($this->user_permissions,"admin/category","add_action")
                ||
                check_permission($this->user_permissions,"admin/category","edit_action")
            )
        )
        {
            return Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
        }

        $get_this_method=generate_site_content_methods_m::where("method_name","=",$slug)->get()->first();

        if(!is_object($get_this_method)){
            return redirect("/");
        }

        $get_this_method_setting=$get_this_method->method_requirments;
        $get_this_method_setting=json_decode($get_this_method_setting,true);

        //input_fields
        $input_fields=array();
        if(isset($get_this_method_setting["input_fields"]["fields"])){
            $input_fields=generate_fields_for_edit_content($get_this_method_setting["input_fields"]["fields"],"textarea");
            $input_fields_cutomize=$get_this_method_setting["input_fields"]["customize"];

            if(isset($input_fields_cutomize)){
                foreach($input_fields_cutomize as $cust_type_key=>$cust_type_value){
                    foreach($cust_type_value as $cust_field_key=>$cust_field_value) {
                        $input_fields[$cust_field_key][$cust_type_key]=$cust_field_value;
                    }
                }
            }

            $input_fields=  reformate_arr_without_keys($input_fields);
        }

        //END input_fields

        //arr_fields
        $arr_fields=array();
        if(isset($get_this_method_setting["arr_fields"]["fields"])){
            $fields_arr=$get_this_method_setting["arr_fields"]["fields"];
            $fields_arr_customize=$get_this_method_setting["arr_fields"]["customize"];


            foreach ($fields_arr as $key => $set) {
                $set=generate_arr_fields_for_edit_content($set);
                //check if there is customization for this set or not
                if(isset($fields_arr_customize[$key])){
                    foreach($fields_arr_customize[$key] as $cust_type_key=>$cust_type_value){
                        foreach($cust_type_value as $cust_field_key=>$cust_field_value) {
                            $set[$cust_field_key][$cust_type_key]=$cust_field_value;
                        }
                    }
                }

                $set=reformate_arr_without_keys($set);
                $arr_fields[]=$set;
            }
        }
        //END arr_fields

        //imgs_fields
        $imgs_fields=array();
        if(isset($get_this_method_setting["imgs_fields"]["fields"])){
            $fields=$get_this_method_setting["imgs_fields"]["fields"];
            $imgs_fields_cutomize=$get_this_method_setting["imgs_fields"]["customize"];
            $imgs_fields=generate_imgs_fields_for_edit_content($fields);

            if(isset($imgs_fields_cutomize)){
                foreach($imgs_fields_cutomize as $cust_type_key=>$cust_type_value){
                    foreach($cust_type_value as $cust_field_key=>$cust_field_value) {
                        $imgs_fields[$cust_field_key][$cust_type_key]=$cust_field_value;
                    }
                }
            }

            $imgs_fields=reformate_arr_without_keys($imgs_fields);
        }
        //END imgs_fields

        //slider_fields
        $slider_fields=array();
        if(isset($get_this_method_setting["slider_fields"]["fields"])){
            $slider_fields_items=$get_this_method_setting["slider_fields"]["fields"];


            $items_arr=array();
            foreach ($slider_fields_items as $key => $item) {
                $items_arr[]=["field_name"=>$item];
            }

            $slider_fields=generate_slider_fieldes_for_edit_content($items_arr);

            foreach ($slider_fields as $slider_item_key => $slider_item) {


                if(isset($get_this_method_setting["slider_fields"]["customize"][$slider_item_key])){
                    $slider_fields_cutomize=$get_this_method_setting["slider_fields"]["customize"]["$slider_item_key"];
                    foreach($slider_fields_cutomize as $cust_type_key=>$cust_type_value){
                        $slider_fields["$slider_item_key"]["$cust_type_key"]="$cust_type_value";
                    }
                }
            }

            $slider_fields=  reformate_arr_without_keys($slider_fields);
        }
        //END slider_fields

        //select fields

        $select_fields=array();
        if(isset($get_this_method_setting["select_fields"]["fields"])){
            $select_fields_items=$get_this_method_setting["select_fields"]["fields"];
            $select_fields_models=$get_this_method_setting["select_fields"]["tables"];
            $select_fields_customize=$get_this_method_setting["select_fields"]["customize"];

            $select_fields=generate_select_tags_for_edit_content($select_fields_items);

            //get select tables data
            foreach ($select_fields as $key => $field) {
                $table_name=$select_fields_models[$key]["model"];
                $text_col=$select_fields_models[$key]["text_col"];
                $val_col=$select_fields_models[$key]["val_col"];


                if(!empty($table_name)&&!empty($text_col)&&!empty($val_col)){
                    $table_data_values=$table_name::All()->lists("$text_col","$val_col")->keys()->all();
                    $table_data_text=$table_name::All()->lists("$text_col","$val_col")->values()->all();

                    $select_fields[$key]["values"]=$table_data_values;
                    $select_fields[$key]["text"]=$table_data_text;
                }
                else{
                    unset($select_fields[$key]);
                }
            }


            //customize

            if(isset($select_fields_customize)){
                foreach($select_fields_customize as $cust_type_key=>$cust_type_value){
                    foreach($cust_type_value as $cust_field_key=>$cust_field_value) {
                        if(isset($select_fields[$cust_field_key])){
                            $select_fields[$cust_field_key][$cust_type_key]=$cust_field_value;
                        }
                    }
                }
            }

            $select_fields=  reformate_arr_without_keys($select_fields);
        }

        //END select fields

        return $this->general_edit_content(
            $method_request=$request,
            $input_fields,
            $arr_fields,
            $imgs_fields,
            $site_content_row_id=0,
            $content_title=$get_this_method->method_title,
            $content_method=$get_this_method->method_name,
            $img_path="",
            $slider_fields,
            $select_fields,
            $lang_id
        );

        //return $this->$slug($request);
    }

    public function edit_test(Request $request) {

        $input_fields=generate_fields_for_edit_content(array("name","email","pass"));
        $input_fields["name"]["field_type"]="textarea";

        $input_fields=  reformate_arr_without_keys($input_fields);

        $first_set=generate_arr_fields_for_edit_content(array("tag_id","tag_name"));
        $second_set=generate_arr_fields_for_edit_content(array("social_links_href","social_links_title"));

        $first_set=  reformate_arr_without_keys($first_set);
        $second_set=  reformate_arr_without_keys($second_set);

        $arr_fields=array($first_set,$second_set);

        $imgs_fields=generate_imgs_fields_for_edit_content(array("logo1","logo2"));
        $imgs_fields=reformate_arr_without_keys($imgs_fields);

        $slider_fields=generate_slider_fieldes_for_edit_content(array(
            array(
                "field_name"=>"slider1",
                "additional_inputs_arr"=>array("price","slider1_header","any_something_else"),
            ),
            array(
                "field_name"=>"slider2",
                "additional_inputs_arr"=>array("slider2_header"),
            )
        ));


        $slider_fields=  reformate_arr_without_keys($slider_fields);


        return $this->general_edit_content(
            $method_request=$request,
            $input_fields,
            $arr_fields=array(),
            $imgs_fields=array(),
            $site_content_row_id=0,
            $content_title="edit_test",
            $content_method="edit_test",
            $img_path="",
            $slider_fields=array()
        );

//        return view("admin.subviews.dashboard",$this->data);
    }

    public function general_edit_content($method_request="",$input_fields=array(),$arr_fields=array(),$imgs_fields=array(),$site_content_row_id=0,$content_title="General Edit Content",$content_method="",$img_path="",$slider_fields=array(),$select_fields=array(),$lang_id=1) {

        $this->data["content_title"]=$content_title;
        $this->data["content_method"]=$content_method;
        $this->data["img_path"]=$img_path;
        $this->data["method_lang_id"]=$lang_id;
        $this->data["current_lang"]=langs_m::find($lang_id);

        $this->data["content_data"]=new \stdClass();

        //get site content by title
        $site_content=site_content_m::where(
            [
                "content_title"=>"$content_method",
                "lang_id"=>"$lang_id"
            ])->get()->first();


        if (!is_object($site_content)) {
            //create new row
            $site_content_row_id=site_content_m::create([
                "content_title"=>"$content_method",
                "lang_id"=>$lang_id
            ]);

            $site_content=site_content_m::where(
                [
                    "content_title"=>"$content_method",
                    "lang_id"=>"$lang_id"
                ])->get()->first();
        }else{
            $site_content_row_id=$site_content->id;
        }


        $content_json=json_decode($site_content->content_json);
    
        if ($content_json!=false) {
            $this->data["content_data"]=$content_json;
        }

        //generate form tags
        $this->data["normal_tags"]=$input_fields;

        //select tags
        $this->data["select_tags"]=$select_fields;
          /*print_r($this->data["content_data"]->img_ids);
          print_r($imgs_fields);
           die; */

        $new_imgs_fields=array();
        foreach ($imgs_fields as $key => $img) {
            $old_img=array();

             $field_name_without_brackets=$img["field_name_without_brackets"];
           if (isset($this->data["content_data"]->img_ids->$field_name_without_brackets) ) {
                $old_img_id=$this->data["content_data"]->img_ids->$field_name_without_brackets;
                $old_img=attachments_m::find($old_img_id);
            }


            if (!is_object($old_img)) {
                $old_img=new \stdClass();
                $old_img->path="";
                $old_img->alt="";
                $old_img->title="";
            }

            $img["img"]=$old_img;
            $new_imgs_fields[]=$img;

        }
       
       
        $this->data["imgs_tags"]=$new_imgs_fields;
        $this->data["arr_tags"]=$arr_fields;

        foreach ($slider_fields as $key => $slider) {
            $slider="slider".($key+1);

            if (!isset($this->data["content_data"]->$slider)) {
                $this->data["content_data"]->$slider=new \stdClass();
            }


            $this->data["content_data"]->$slider->slider_objs=array();

            if (isset($this->data["content_data"]->$slider->img_ids)&&is_array($this->data["content_data"]->$slider->img_ids)&&  count($this->data["content_data"]->$slider->img_ids)) {
                $this->data["content_data"]->$slider->slider_objs=attachments_m::get_imgs_from_arr($this->data["content_data"]->$slider->img_ids);
            }
        }

        $this->data["slider_fields"]=$slider_fields;

        //END generate form tags

        if (isset($_POST["submit"])) {

            $cache_data=Cache::get($content_method."_$lang_id");
            if($cache_data!=null){
                Cache::forget($content_method."_$lang_id");
            }

            //save
            $inputs=array();

            //save form

            //add normal fields
            if (is_array($input_fields)&&  count($input_fields)) {
                $input_fields=  convert_inside_obj_to_arr($input_fields,"field_name","array");
                $inputs=array_from_post($input_fields);
            }
            //END add normal Fields

            //select fields
                $input_fields=  convert_inside_obj_to_arr($select_fields,"field_name","array");
                $select_inputs=array_from_post($input_fields);
                $inputs=array_merge($inputs,$select_inputs);
            //END select fields


            //add img fields
            $img_ids=array();
            if (is_array($imgs_fields)&&  count($imgs_fields)) {
                foreach ($imgs_fields as $key => $img_field) {

                    $old_img=array();
                    $old_img_id=0;
                  
                   $field_name_without_brackets=$img_field["field_name_without_brackets"];
                    if (isset($this->data["content_data"]->img_ids->$field_name_without_brackets)) {
                        $old_img_id=$this->data["content_data"]->img_ids->$field_name_without_brackets;
                        $old_img=attachments_m::find($old_img_id);
                    }


                    $new_title_field=$img_field["field_name_without_brackets"]."title";
                    $new_alt_field=$img_field["field_name_without_brackets"]."alt";


                    $upload_new_img_checkbox=$img_field["field_name_without_brackets"]."_upload_new_img_chcekbox";


                    $imgs_inputs=array_from_post(array(
                        $upload_new_img_checkbox,$new_title_field,$new_alt_field
                    ));


                    if ($img_field["need_alt_title"]=="no") {
                        $imgs_inputs[$new_title_field]="";
                        $imgs_inputs[$new_alt_field]="";

                    }

                    if (!is_object($old_img)) {
                        $old_img=new \stdClass();
                        $old_img->path="";
                        $old_img->alt="";
                        $old_img->title="";
                    }

                    $img_ids[$img_field["field_name_without_brackets"]]=$this->general_save_img(
                        $method_request,
                        $item_id=1,
                        $img_file_name=$img_field["field_name_without_brackets"],
                        $new_title=$imgs_inputs[$new_title_field],
                        $new_alt=$imgs_inputs[$new_alt_field],
                        $upload_new_img_check=$imgs_inputs[$upload_new_img_checkbox],
                        $upload_file_path=  "/general_edit_content",
                        $width=0,
                        $height=0,
                        $photo_id_for_edit=$old_img_id
                    );
                }
            }//end imgs if

            $inputs["img_ids"]=$img_ids;
            //END img Fields

            //add array_input fields
            if (is_array($arr_fields)&&count($arr_fields)) {
                foreach ($arr_fields as $key => $single_arr_fields) {
                    $single_arr_fields=  convert_inside_obj_to_arr($single_arr_fields, "field_name","array");
                    $inputs_new=array_from_post($single_arr_fields);
                    $inputs_new[$single_arr_fields[0]]= array_diff($inputs_new[$single_arr_fields[0]], array(""));

                    $inputs=  array_merge($inputs,$inputs_new);
                }
            }
            //END add array_input fields

            //add sliders
            if (is_array($slider_fields)&&count($slider_fields)) {
                foreach ($slider_fields as $key => $slider_field) {

                    $field_name=$slider_field["field_name"];

                    $new_title_field_name=$field_name."_title";
                    $new_alt_field_name=$field_name."_alt";

                    $old_title_field_name=$field_name."_edit_title";
                    $old_alt_field_name=$field_name."_edit_alt";

                    $json_values_of_slider_field="json_values_of_slider".$field_name;

                    $other_fields=$slider_field["additional_inputs_arr"];
                    if(!empty($other_fields)&&!is_array($other_fields)){
                        $other_fields=explode(",",$other_fields);
                    }

                    $slider_inputs=array_from_post(array(
                        $new_title_field_name,$new_alt_field_name,
                        $json_values_of_slider_field,
                        $old_title_field_name,$old_alt_field_name
                    ));


                    $slider_inputs[$json_values_of_slider_field]=  json_decode($slider_inputs[$json_values_of_slider_field]);


                    $inputs["slider".($key+1)]["img_ids"]=$this->general_save_slider(
                        $method_request,
                        $field_name,
                        $width=0,
                        $height=0,
                        $new_title_arr=$slider_inputs[$new_title_field_name],
                        $new_alt_arr=$slider_inputs[$new_alt_field_name],
                        $json_values_of_slider=$slider_inputs[$json_values_of_slider_field],
                        $old_title_arr=$slider_inputs[$old_title_field_name],
                        $old_alt_arr=$slider_inputs[$old_alt_field_name],
                        $path="/".$slider_field["folder"]
                    );


                    if(is_array($other_fields)){
                        $new_fields=array_from_post($other_fields);

                        $old_other_fields=array();
                        foreach ($other_fields as $field_key => $field_value) {
                            $old_other_fields[]="edit_".$field_value;
                        }
                        $old_fields=array_from_post($old_other_fields);


                        $all_data=array();
                        foreach ($new_fields as $new_field_key => $new_field_val) {

                            $new=$new_fields[$new_field_key];

                            $old=array();
                            if (isset($old_fields["edit_$new_field_key"])&&  is_array($old_fields["edit_$new_field_key"])) {
                                $old=$old_fields["edit_$new_field_key"];
                            }


                            $all_data["$new_field_key"]=  array_merge($old,$new);
                        }

                        $inputs["slider".($key+1)]["other_fields"]=$all_data;
                    }





                }//end foreach
            }
            //END add sliders


            $trurned_id=site_content_m::where("id","=",$site_content_row_id)->update(
                [
                    "content_json"=>  json_encode($inputs)
                ]
            );


//            if ($trurned_id>0) {
            $this->data["success"]="updated";
            $site_content=site_content_m::findOrFail($site_content_row_id);
            $this->data["content_data"]=json_decode($site_content->content_json);

            //generate form tags

            $new_imgs_fields=array();
            foreach ($imgs_fields as $key => $img) {
                $old_img=array();
                $field_name_without_brackets=$img["field_name_without_brackets"];
                if (isset($this->data["content_data"]->img_ids)) {
                    $old_img_id=$this->data["content_data"]->img_ids->$field_name_without_brackets;
                    $old_img=attachments_m::find($old_img_id);
                }


                if (!is_object($old_img)) {
                    $old_img=new \stdClass();
                    $old_img->path="";
                    $old_img->alt="";
                    $old_img->title="";
                }

                $img["img"]=$old_img;
                $new_imgs_fields[]=$img;

            }

            $this->data["imgs_tags"]=$new_imgs_fields;

            //END generate form tags


            //slider
            foreach ($slider_fields as $key => $slider) {
                $slider="slider".($key+1);
                $this->data["content_data"]->$slider->slider_objs=array();
                if (is_array($this->data["content_data"]->$slider->img_ids)&& count($this->data["content_data"]->$slider->img_ids)) {
                    $this->data["content_data"]->$slider->slider_objs=attachments_m::get_imgs_from_arr($this->data["content_data"]->$slider->img_ids);
                }
            }

//            }
        }//end submit

        //return view("admin.subviews.dashboard",$this->data);
        return view("admin.subviews.edit_content.general_edit_content",$this->data);
    }


}
