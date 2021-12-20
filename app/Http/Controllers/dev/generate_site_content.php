<?php

namespace App\Http\Controllers\dev;

use App\Http\Controllers\dashbaord_controller;
use App\Http\Controllers\dev_controller;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\models\generate_site_content_methods_m;

class generate_site_content extends dev_controller
{

    public function __construct(){
        parent::__construct();

    }

    public function show_all_methods()
    {
        $this->data["all_methods"]=generate_site_content_methods_m::all();

        return view("dev.subviews.generate_edit_content.show_all",$this->data);
    }


    public function save_method(Request $req,$method_id=null) {


        $this->data["method_data"]="";
        $method_img_id=0;

        if ($method_id!=null) {
            $this->data["method_data"]=generate_site_content_methods_m::findOrFail($method_id);
            $method_img_id=$this->data["method_data"]->method_img_id;
            $method_requirments=json_decode($this->data["method_data"]->method_requirments);

            //get method_requirments
            #region get normal_inputs
            if(isset($method_requirments->input_fields)){
                $input_fields=$method_requirments->input_fields;
                if(isset($input_fields->fields)){
                    $this->data["method_data"]->field_name=implode(",",$input_fields->fields);
                }
                $normal_fields_customize=array("label_name","required","type","class");
                foreach ($normal_fields_customize as $key => $cust_field) {
                    if(isset($input_fields->customize->$cust_field)){
                        $this->data["method_data"]->$cust_field=implode(",",(array)$input_fields->customize->$cust_field);
                    }
                }
            }
            #endregion get normal_inputs

            #regionget imgs_fields
            if(isset($method_requirments->imgs_fields)){
                $imgs_fields=$method_requirments->imgs_fields;
                if(isset($imgs_fields->fields)){
                    $this->data["method_data"]->img_field_name=implode(",",$imgs_fields->fields);
                }
                $fields_customize=array(
                    "required"=>"img_required",
                    "need_alt_title"=>"img_need_alt_title",
                    "width"=>"img_width",
                    "height"=>"img_height"
                );
                foreach ($fields_customize as $cust_key => $cust_field) {
                    if(isset($imgs_fields->customize->$cust_key)){
                        $this->data["method_data"]->$cust_field=implode(",",(array)$imgs_fields->customize->$cust_key);
                    }
                }
            }
            #endregionEND get imgs_fields

            #region get select_fields
            if(isset($method_requirments->select_fields)){
                $select_fields=$method_requirments->select_fields;

                if(isset($select_fields->fields)){
                    $this->data["method_data"]->select_field_name=implode(",",$select_fields->fields);
                }

                $models=convert_inside_obj_to_arr($select_fields->tables,"model","object");
                $text_cols=convert_inside_obj_to_arr($select_fields->tables,"text_col","object");
                $val_cols=convert_inside_obj_to_arr($select_fields->tables,"val_col","object");

                $this->data["method_data"]->select_model=implode(",",$models);
                $this->data["method_data"]->select_text_col=implode(",",$text_cols);
                $this->data["method_data"]->select_val_col=implode(",",$val_cols);


                $fields_customize=array(
                    "label_name"=>"select_label_name",
                    "class"=>"select_class",
                    "multiple"=>"select_multiple"
                );
                foreach ($fields_customize as $cust_key => $cust_field) {
                    if(isset($select_fields->customize->$cust_key)){
                        $this->data["method_data"]->$cust_field=implode(",",(array)$select_fields->customize->$cust_key);
                    }
                }
            }
            #endregion get select_fields

            #region get arr_inputs
            if(isset($method_requirments->arr_fields)){
                $main_sets=$method_requirments->arr_fields->fields;
                $arr_customize=$method_requirments->arr_fields->customize;

                $arr_set_name=array_keys((array)$main_sets);
                $set_fields=array();
                $set_labels=array();
                $set_types=array();
                $set_classes=array();
                $add_tiny_mce=array();

                $cust_keys=array(
                    "label_name"=>"set_labels",
                    "field_type"=>"set_types",
                    "field_class"=>"set_classes",
                    "add_tiny_mce"=>"add_tiny_mce"
                );
                foreach ($main_sets as $set_key => $set) {
                    $set_fields[]=implode(",",$set);
                    foreach ($cust_keys as $cust_key => $cust_value) {
                        if(isset($arr_customize->$set_key->$cust_key)){
                            array_push($$cust_value,implode(",",(array)$arr_customize->$set_key->$cust_key));
                        }else{
                            array_push($$cust_value,"");
                        }
                    }
                }

                $this->data["method_data"]->set_name=$arr_set_name;
                $this->data["method_data"]->set_fields=$set_fields;
                $this->data["method_data"]->set_labels=$set_labels;
                $this->data["method_data"]->set_types=$set_types;
                $this->data["method_data"]->set_classes=$set_classes;
                $this->data["method_data"]->add_tiny_mce=$add_tiny_mce;


            }
            #endregion get arr_inputs

            #region get slider_inputs
            if(isset($method_requirments->slider_fields)){
                $slider_fields=$method_requirments->slider_fields->fields;
                $slider_customize=$method_requirments->slider_fields->customize;

                $slider_field_name=$slider_fields;
                $slider_label_name=array();
                $slider_accept=array();
                $slider_need_alt_title=array();
                $slider_additional_inputs_arr=array();
                $slider_width=array();
                $slider_height=array();


                $cust_keys=array(
                    "label_name"=>"slider_label_name",
                    "accept"=>"slider_accept",
                    "need_alt_title"=>"slider_need_alt_title",
                    "additional_inputs_arr"=>"slider_additional_inputs_arr",
                    "width"=>"slider_width",
                    "height"=>"slider_height"
                );
                foreach ($slider_field_name as $slider_key => $slider) {
                    foreach ($cust_keys as $cust_key => $cust_value) {
                        if(isset($slider_customize->$slider->$cust_key)){
                            array_push($$cust_value,implode(",",(array)$slider_customize->$slider->$cust_key));
                        }else{
                            array_push($$cust_value,"");
                        }
                    }
                }


                $this->data["method_data"]->slider_field_name=$slider_field_name;
                $this->data["method_data"]->slider_label_name=$slider_label_name;
                $this->data["method_data"]->slider_accept=$slider_accept;
                $this->data["method_data"]->slider_need_alt_title=$slider_need_alt_title;
                $this->data["method_data"]->slider_additional_inputs_arr=$slider_additional_inputs_arr;
                $this->data["method_data"]->slider_width=$slider_width;
                $this->data["method_data"]->slider_height=$slider_height;


            }
            #endregion get slider_inputs
            //END get method_requirments
        }

        if(isset($req)&&count($req->all())>0){
            $inputs_arr=$req->all();

            $this->validate($req,[
                "method_name"=>"required|unique:generate_site_content_methods,method_name,".$method_id.",id",
                "method_title"=>"required"
            ]);

            $inputs_arr["method_img_id"]=$this->general_save_img(
                $req,
                $item_id=$method_id,
                $img_file_name="method_img",
                $new_title="",
                $new_alt="",
                $upload_new_img_check="method_img_checkbox",
                $upload_file_path="/edit_content_imgs",
                $width=0,
                $height=0,
                $photo_id_for_edit=$method_img_id
            );

            $inputs_arr["method_name"]=  string_safe($inputs_arr["method_name"]);

            $method_requirments=array();
            //format method_requirments

            #region normal fields
            //fields
            if(!empty($inputs_arr["field_name"])){
                $method_requirments["input_fields"]=new \stdClass();
                $normal_fields=explode(",",$inputs_arr["field_name"]);
                $method_requirments["input_fields"]->fields=$normal_fields;

                //customzie
                $method_requirments["input_fields"]->customize=new \stdClass();
                $normal_fields_customize=array("label_name","required","type","class");
                foreach ($normal_fields_customize as $key => $normal_fields_customize_item) {
                    $customize_values=explode(",",$inputs_arr["$normal_fields_customize_item"]);
                    if(count($customize_values)==count($normal_fields)){
                        $method_requirments["input_fields"]->customize->$normal_fields_customize_item=
                            array_combine($normal_fields,$customize_values);
                    }
                }
            }
            #endregion normal fields

            #region imgs_fields
            //fields
            if(!empty($inputs_arr["img_field_name"])){
                $method_requirments["imgs_fields"]=new \stdClass();
                $imgs_fields=explode(",",$inputs_arr["img_field_name"]);
                $method_requirments["imgs_fields"]->fields=$imgs_fields;

                //customzie
                $method_requirments["imgs_fields"]->customize=new \stdClass();
                $img_fields_customize=array(
                    "required"=>"img_required",
                    "need_alt_title"=>"img_need_alt_title",
                    "width"=>"img_width",
                    "height"=>"img_height"
                );
                foreach ($img_fields_customize as $cust_key => $customize_item) {
                    $customize_values=explode(",",$inputs_arr["$customize_item"]);
                    if(count($customize_values)==count($imgs_fields)){
                        $method_requirments["imgs_fields"]->customize->$cust_key=
                            array_combine($imgs_fields,$customize_values);
                    }
                }
            }
            #endregion imgs_fields

            #region select_fields
            //fields
            if(!empty($inputs_arr["select_field_name"])) {
                $select_fields = explode(",", $inputs_arr["select_field_name"]);
                $select_tables = explode(",", $inputs_arr["select_model"]);
                $select_text_cols = explode(",", $inputs_arr["select_text_col"]);
                $select_val_cols = explode(",", $inputs_arr["select_val_col"]);

                $method_requirments["select_fields"] = new \stdClass();

                $method_requirments["select_fields"]->fields = $select_fields;

                //tables
                $method_requirments["select_fields"]->tables = new \stdClass();




                foreach ($select_fields as $key => $select_field) {
                    $method_requirments["select_fields"]->tables->$select_field = new \stdClass();

                    $method_requirments["select_fields"]->tables->$select_field->model = $select_tables[$key];
                    $method_requirments["select_fields"]->tables->$select_field->text_col = $select_text_cols[$key];
                    $method_requirments["select_fields"]->tables->$select_field->val_col = $select_val_cols[$key];
                }

                //customzie
                $method_requirments["select_fields"]->customize = new \stdClass();
                $select_fields_customize = array(
                    "label_name" => "select_label_name",
                    "class" => "select_class",
                    "multiple" => "select_multiple"
                );

                foreach ($select_fields_customize as $cust_key => $customize_item) {
                    $customize_values = explode(",", $inputs_arr["$customize_item"]);
                    if (count($customize_values) == count($select_fields)) {
                        $method_requirments["select_fields"]->customize->$cust_key =
                            array_combine($select_fields, $customize_values);
                    }
                }
            }
            #endregion select_fields

            #region arr fields
            //fields
            $arr_main_set_fields=array_diff($inputs_arr["set_name"],array(""));
            if(count($arr_main_set_fields)>0){
                $method_requirments["arr_fields"]=new \stdClass();
                $method_requirments["arr_fields"]->fields=new \stdClass();


                $arr_set_fields=$inputs_arr["set_fields"];
                foreach ($arr_main_set_fields as $key => $main_set) {
                    $method_requirments["arr_fields"]->fields->$main_set=explode(",",$arr_set_fields[$key]);
                }

                //customzie
                $method_requirments["arr_fields"]->customize=new \stdClass();
                $arr_fields_customize=array(
                    "label_name"=>"set_labels",
                    "field_type"=>"set_types",
                    "field_class"=>"set_classes",
                    "add_tiny_mce"=>"add_tiny_mce"
                );

                foreach ($arr_main_set_fields as $set_key => $main_set) {
                    $method_requirments["arr_fields"]->customize->$main_set=new \stdClass();
                    $set_fields=$method_requirments["arr_fields"]->fields->$main_set;
                    foreach ($arr_fields_customize as $cust_key => $normal_fields_customize_item) {
                        $customize_values=explode(",",$inputs_arr["$normal_fields_customize_item"][$set_key]);
                        if(count($customize_values)==count($set_fields)){
                            $method_requirments["arr_fields"]->customize->
                            $main_set->$cust_key=
                                array_combine($set_fields,$customize_values);
                        }
                    }
                }
            }
            #endregion arr fields

            #region slider fields
            //fields
            $slider_fields=array_diff($inputs_arr["slider_field_name"],array(""));
            if(count($slider_fields)>0){
                $method_requirments["slider_fields"]=new \stdClass();
                $method_requirments["slider_fields"]->fields=$slider_fields;

                //customzie
                $method_requirments["slider_fields"]->customize=new \stdClass();
                $slider_fields_customize=array(
                    "label_name"=>"slider_label_name",
                    "accept"=>"slider_accept",
                    "need_alt_title"=>"slider_need_alt_title",
                    "additional_inputs_arr"=>"slider_additional_inputs_arr",
                    "width"=>"slider_width",
                    "height"=>"slider_height"
                );

                foreach ($slider_fields as $key => $slider_item) {
                    $method_requirments["slider_fields"]->customize->$slider_item=new \stdClass();

                    foreach ($slider_fields_customize as $cust_key => $customize_item) {
//                            $customize_values=explode(",",$inputs_arr["$customize_item"][$key]);
                        $customize_values=$inputs_arr["$customize_item"][$key];
                        //if(count($customize_values)==count($slider_fields)){
                        $method_requirments["slider_fields"]->customize->$slider_item->$cust_key=
                            $customize_values;
                        //}
                    }

                }
            }

            #endregion slider fields

            //END format method_requirments
            $inputs_arr["method_requirments"]=json_encode($method_requirments);

            if ($method_id==null) {
                $returned_id=generate_site_content_methods_m::create($inputs_arr);
                $method_id=$returned_id->id;

                if($method_id>0){
                    $this->data["success"]='<div class="alert alert-success">
                        <strong>Inserted!</strong>.
                        </div>';
                }

            }
            else{
                $returned_id=generate_site_content_methods_m::findOrFail($method_id)->update($inputs_arr);
                if($returned_id){
                    $this->data["success"]='<div class="alert alert-success">
                        <strong>Updated!</strong>.
                        </div>';
                }
            }


            \Session::flash("msg",$this->data["success"]);
            return redirect(url("/dev/generate_edit_content/save/$method_id"));

        }

        return view("dev.subviews.generate_edit_content.save",$this->data);
    }


}
