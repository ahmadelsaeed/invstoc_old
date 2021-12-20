<?php

/*
  ---------------------------------------------------------
    New Functions
  ---------------------------------------------------------
 */

function generate_fields_for_edit_content($fields_arr,$default_field_type="text"){
    $return_fields_arr=array();
    
    
    foreach ($fields_arr as $key => $field) {
        $return_fields_arr[$field]=array(
            "field_name"=>"$field",
            "field_type"=>"$default_field_type",
            "field_class"=>"form-control"
        );
    }
    
    return $return_fields_arr;
}

function generate_arr_fields_for_edit_content($fileds_arr){
    
    $return_fields=array();
    foreach ($fileds_arr as $key => $field) {
        $return_fields[$field]=array(
            "label_name"=>$field,
            "field_name"=>$field,
            "field_type"=>"text",
            "field_class"=>"form-control",
            "add_tiny_mce"=>"no"
        );
    }


    return $return_fields;
}

function generate_imgs_fields_for_edit_content($fields_arr){
    $return_fields=array();
    
    foreach ($fields_arr as $key => $field) {
        $return_fields[$field]=array(
            "field_name"=>"$field"."[]",
            "field_name_without_brackets"=>"$field",
            "required"=>"yes",
            "need_alt_title"=>"no",
            "width"=>"0",
            "height"=>"0",
        );
    }
    
    return $return_fields;
}

function generate_slider_fieldes_for_edit_content($fields_arr){
    $return_fields=array();
    foreach ($fields_arr as $key => $field) {
        
        $field_name=$field["field_name"];

        $additional_inputs_arr="";
        if(isset($field["additional_inputs_arr"])){
            $additional_inputs_arr=$field["additional_inputs_arr"];
        }

        
        $return_fields[$field_name]=array(
            "field_name"=>"$field_name",
            "label_name"=>"$field_name",
            "field_id"=>"$field_name"."_id",
            "accept"=>"image/*",
            "need_alt_title"=>"no",
            "additional_inputs_arr"=>$additional_inputs_arr,
            "width"=>"0",
            "height"=>"0",
            "folder"=>"edit_content_folder"
        );
    }
    
    return $return_fields;
}

function generate_select_tags_for_edit_content($fields_arr){
    $return_fields=array();

    foreach ($fields_arr as $key => $field) {
        $return_fields[$field]=array(
            "field_name"=>"$field",
            "label_name"=>"$field",
            "text"=>array(""),
            "values"=>array(""),
            "selected_value"=>array(""),
            "class"=>"form-control",
            "multiple"=>"",
            "required"=>"",
            "disabled"=> ""
        );

    }
    return $return_fields;
}

function generate_default_array_inputs_html($fields_name,$data,$key_in_all_fields="",$requried="required",$grid_default_value=12){
    $labels_name=array();
    $required=array();
    $type=array();
    $values=array();
    $class=array();
    $grid=array();



    foreach ($fields_name as $key => $value) {

        if ($key_in_all_fields!="") {
            $labels_name[$value]=capitalize_string($value);
            $required[$value]=$requried;

            if (isset($data->$value)) {
                $values[$value]=$data->$value;
            }else{
                $values[$value]="";
            }
        }
        else{
            $labels_name[]=capitalize_string($value);
            $required[]=$requried;

            if (isset($data->$value)) {
                $values[]=$data->$value;
            }else{
                $values[]="";
            }
        }


        $type[$value]="text";
        $class[$value]="form-control";
        $grid[$value]=$grid_default_value;
    }


    return array($labels_name , $fields_name , $required , $type , $values , $class,$grid);
}

function reformate_arr_without_keys($arr){
    
    $new_arr=array();
    
    foreach ($arr as $key => $value) {
        $new_arr[]=$value;
    }
    
    return $new_arr;
}

/*
  =========================================================
    END New Functions
  =========================================================
 */

function generate_img_tags_for_form($filed_name,$filed_label,$required_field="",$checkbox_field_name,$need_alt_title="yes",$required_alt_title="",$old_path_value="",$old_title_value="",$old_alt_value="",$recomended_size="",$disalbed="",$displayed_img_width="50",$display_label="",$img_obj="",$grid=""){

    if(is_object($img_obj)){
        $old_path_value=url($img_obj->path);
        $old_title_value=$img_obj->title;
        $old_alt_value=$img_obj->alt;
        $disalbed="disabled";
    }


    $filed_name_id=$filed_label."id";
    $filed_name_id_jquery="#".$filed_name_id;

    $checkbox_field_name_id=$checkbox_field_name."id";
    $checkbox_field_name_id_jquery="#".$checkbox_field_name_id;

    $title_field_name=$filed_label."title";
    $alt_field_name=$filed_label."alt";


    $html_tags='<script type="text/javascript">';
        $html_tags.='$(function(){';
            $html_tags.='$("'.$checkbox_field_name_id_jquery.'").change(function(){';
                $html_tags.='var check=$(this).is(":checked");';
                $html_tags.='if (check==true) {';
                    $html_tags.='$("'.$filed_name_id_jquery.'").removeAttr("disabled")';
                $html_tags.='}';
                $html_tags.='else{';
                    $html_tags.='$("'.$filed_name_id_jquery.'").attr("disabled","disabled");';
                $html_tags.='}';
            $html_tags.='});';
        $html_tags.='});';

    $html_tags.='</script>';

    $html_tags.='<div class="'.$grid.' form-group">';
        $html_tags.='<label for="">'.$display_label.' '.$recomended_size.'</label>';
        $html_tags.='<div class="">';

            $file_size_class="col-md-4";
            if ($need_alt_title!="yes") {
                $file_size_class="";
            }

            $html_tags.='<div class="'.$file_size_class.'">';
                $html_tags.='<input type="file" class="form-control" name="'.$filed_name.'" '.$disalbed.' id="'.$filed_name_id.'" '.$required_field.' >';
            $html_tags.='</div>';

            if ($need_alt_title=="yes") {
                $html_tags.='<div class="col-md-4">';
                    $html_tags.='<input type="text" class="form-control" placeholder="image title" name="'.$title_field_name.'" '.$required_alt_title.' value="'.$old_title_value.'">';
                $html_tags.='</div>';

                $html_tags.='<div class="col-md-4">';
                    $html_tags.='<input type="text" class="form-control" placeholder="image alt" name="'.$alt_field_name.'" '.$required_alt_title.' value="'.$old_alt_value.'">';
                $html_tags.='</div>';
            }

        $html_tags.='</div>';

        if ($disalbed!="") {
            $html_tags.='<div class="col-md-12">';
                $html_tags.='<div class="col-md-4">';

                    $html_tags.='<div class="row-fluid">';

                        if(strpos($old_path_value,"pdf")>0){
                            $html_tags.='<a class="btn btn-info" target="_blank" href="'.$old_path_value.'" >link</a>';
                        }
                        else{
                            $html_tags.='<img src="'.$old_path_value.'" alt="'.$old_alt_value.'" title="'.$old_title_value.'" width="'.$displayed_img_width.'">';
                        }


                    $html_tags.='</div>';

                    $html_tags.='<div class="row-fluid">';
                        $html_tags.='<input type="checkbox" name="'.$checkbox_field_name.'" id="'.$checkbox_field_name_id.'">:upload new file';
                    $html_tags.='</div>';

                $html_tags.='</div>';
            $html_tags.='</div>';
        }


    $html_tags.='</div>';

    return $html_tags;
}

function generate_inputs_html($labels_name , $fields_name , $required , $type , $values , $class,$grid="")
{
    $html_tags = "";

    foreach ($fields_name as $key => $value) {

        $grid_col="12";
        if(isset($grid[$key])){
            $grid_col=$grid[$key];
        }

        $html_tags.='<div data-hidediv="'.$fields_name[$key].'_div_id'.'" class="col-md-'.$grid_col.' form-group '.$fields_name[$key].'_div_class'.'">';
            $html_tags.='<label for="'.$fields_name[$key].'_id">'.$labels_name[$key].'</label>';

            $html_tags.='<div '.(($type[$key]=="checkbox")?'style="height: 34px;"':"").' >';

            if ($type[$key] == 'textarea') {

                $html_tags .= '<textarea name="'.$value.'" style="resize:vertical" class="form-control '.$class[$key].'" id="'.$fields_name[$key].'_id">'.$values[$key].'</textarea>';

            }
            else if($type[$key] == 'number')
            {
                $html_tags.='<input type="'.$type[$key].'" step="0.0001" class="form-control '.$class[$key].'" '.$required[$key].' name="'.$value.'" value="'.$values[$key].'" id="'.$fields_name[$key].'_id" >';

            }
            else if($type[$key] == 'checkbox')
            {
                $class[$key]=str_replace("form-control"," ",$class[$key]);
                $html_tags.='<input type="'.$type[$key].'" value="1" class="'.$class[$key].'" '.$required[$key].' name="'.$value.'" '.(($values[$key]==1)?"checked":"").' id="'.$fields_name[$key].'_id" >';
            }
            else if($type[$key]=="date_time"){

                if($values[$key]!=""&&$values[$key]!="0000-00-00 00:00:00"){
                    //$values[$key]=\Carbon\Carbon::now();
                    $values[$key]=date('m/j/Y g:i a',strtotime($values[$key]));
                    if($values[$key]=="01/1/1970 2:00 am"){
                        $values[$key]="";
                    }
                }
                else{
                    $values[$key]="";
                }
                $html_tags.="<div class='input-group date' id='datetimepicker_$key'>";
                    //01/30/2017 4:29 PM
                    $html_tags.='<input type="text" class="form-control '.$class[$key].' " value="'.$values[$key].'" '.$required[$key].' name="'.$fields_name[$key].'" id="'.$fields_name[$key].'_id" />';
                    $html_tags.='<span class="input-group-addon">';
                        $html_tags.='<span class="glyphicon glyphicon-calendar"></span>';
                    $html_tags.='</span>';
                $html_tags.='</div>';

                $html_tags.='<script type="text/javascript">';
                    $html_tags.='$(function () {';
                        $html_tags.="$('#datetimepicker_$key').datetimepicker();";
                    $html_tags.='});';
                $html_tags.='</script>';
            }
            else if($type[$key]=="date"){

                $html_tags.="<div class='input-group date' id='datetimepicker_$fields_name[$key]'>";
                //01/30/2017 4:29 PM

                if($values[$key]!=""&&$values[$key]!="1970-01-01"&&$values[$key]!="0000-00-00"){
                    //$values[$key]=\Carbon\Carbon::now();
                    $values[$key]=date('d-m-Y',strtotime($values[$key]));
                }
                else{
                    $values[$key]="";
                }



                $html_tags.='<input type="text" class="form-control '.$class[$key].' " value="'.$values[$key].'" '.$required[$key].' name="'.$fields_name[$key].'" id="'.$fields_name[$key].'_id" />';
                $html_tags.='<span class="input-group-addon">';
                $html_tags.='<span class="glyphicon glyphicon-calendar"></span>';
                $html_tags.='</span>';
                $html_tags.='</div>';

                $html_tags.='<script type="text/javascript">';
                $html_tags.='$(function () {';
                $html_tags.="$('#datetimepicker_$fields_name[$key]').datetimepicker({format: 'DD-MM-YYYY'});";
                $html_tags.='});';
                $html_tags.='</script>';
            }
            else if($type[$key]=="month_year"){

                $html_tags.="<div class='input-group' id='datetimepicker_$fields_name[$key]'>";
                //01/30/2017 4:29 PM

//                if($values[$key]!=""&&$values[$key]!="1970-01-01"&&$values[$key]!="0000-00-00"){
//                    //$values[$key]=\Carbon\Carbon::now();
//                    $values[$key]=date('m-Y',strtotime($values[$key]));
//                }
//                else{
//                    $values[$key]="";
//                }

                $html_tags.='<input type="text" class="form-control '.$class[$key].' " value="'.$values[$key].'" '.$required[$key].' name="'.$fields_name[$key].'" id="'.$fields_name[$key].'_id" />';
                $html_tags.='<span class="input-group-addon">';
                $html_tags.='<span class="glyphicon glyphicon-calendar"></span>';
                $html_tags.='</span>';
                $html_tags.='</div>';

                $html_tags.='<script type="text/javascript">';
                $html_tags.='$(function () {';
                $html_tags.="
                console.log('dasdasa');
                $('#datetimepicker_$fields_name[$key]').datetimepicker({
                    viewMode: 'years',
                    format: 'MM/YYYY'
                });";
                $html_tags.='});';
                $html_tags.='</script>';
            }
            else{
                $html_tags.='<input type="'.$type[$key].'" class="form-control '.$class[$key].'" '.$required[$key].' name="'.$fields_name[$key].'" value="'.$values[$key].'" id="'.$fields_name[$key].'_id" >';
            }

            $html_tags.='</div>';
        $html_tags.='</div>';

    }


    return $html_tags;

}

function generate_select_years($already_selected_value , $earliest_year , $class , $name,$label=""  )
{
    $html="<div class='col-md-6'>";
        $html.='<label for="">'.$label.'</label>';
        
        $html.="<div class='col-md-12'>";
            $html.='<select class="form-control '.$class.'" style="cursor: pointer" name="'.$name.'" >';
            $html.= generate_years_options($earliest_year,$already_selected_value);
        $html.="</div>";
    $html.="</div>";
    return $html;
}

function generate_years_options($earliest_year,$already_selected_value="",$remove_first = false)
{

    $options = "";
    foreach (range(date('Y'), $earliest_year)  as $key =>$x) {
        if ($key==0 && $remove_first == false) {
            $options.="<option value='0'></option>";
        }
        $options.='<option value="'.$x.'"'.($x == $already_selected_value ? ' selected="selected"' : '').'>'.$x.'</option>';
    }
    $options.='</select>';

   return $options;
}

function generate_select_month($field_name,$label,$selected_value,$class,$required){
    
    $months=array(0=>"",1 => "Jan", 2 => "Feb", 3 => "Mar", 4 => "Apr", 5 => "May", 6 => "Jun", 7 => "Jul", 8 => "Aug", 9 => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec");

    $html='<div class="col-md-6 form-group">';
        $html.='<label for="">'.$label.'</label>';
        $html.="<div class='col-md-12'>";
            $html.="<select name='".$field_name."' ".$required." class='".$class."'>";
                foreach ($months as $key => $value) {
                    $selected="";
                    if ($key==$selected_value) {
                        $selected="selected";
                    }

                    $html.="<option value='".$key."'>".$value."</option>";
                }
            $html.="</select>";
        $html.="</div>";
    $html.="</div>";
    
    return $html;
}

//generate_array_input create array of items of array of fields field 
//like that
//(1)
//<input type="text" name="field[]" value="1" />
//<textarea>1</textarea>
//(/1)
//(2)
//<input type="text" name="field[]" value="2" />
//<textarea>2</textarea>
//(/2)

//$label_name,$field_name,$required,$type,$values,$class all of these prameters are arraies
//except $values its array of arraies
//$values = array(
//  array("Home","First Page","About Us"),
//  array("Home","First Page","About Us")
//);
//$default_values = array(
//  "Home","First Page","About Us"
//);

function generate_array_input($label_name,$field_name,$required,$type,$values,$class,$add_tiny_mce="",$default_values=array(),$data=""){

    if($data!=""){
        foreach ($field_name as $field_key => $field_value) {
            if(isset($data->$field_value)){
                $values[$field_key]=$data->$field_value;
            }
        }
    }


    $field_id=$field_name[0]."_id";
    $add_item_class=$field_name[0]."_add_item";
    $remove_item_class=$field_name[0]."_remove_item";

    $contain_items_class=$field_name[0]."_contain_items";
    $first_item_class=$field_name[0]."_first_item";

    if(count($default_values)==0){

        foreach ($field_name as $key => $value) {
            $default_values[]="";
        }

    }

    $html_tag="<div class='contain_arr_input_$field_name[0]'>";
    $new_tags="";

    $html_tag.='<script type="text/javascript">'.PHP_EOL;
        $html_tag.='$(function(){'.PHP_EOL;

            $html_tag.='$(".'.$remove_item_class.'").click(function(){'.PHP_EOL;
                $html_tag.='$(this).parent().remove();'.PHP_EOL;
                $html_tag.='return false;'.PHP_EOL;
            $html_tag.='});'.PHP_EOL;

            $html_tag.='var text_area_counter='.(count($values[0])+1).' '.PHP_EOL;

            $html_tag.='$(".'.$add_item_class.'").click(function(){'.PHP_EOL;
                $html_tag.='var new_id="'.$field_id.'"+"textarea"+text_area_counter'.PHP_EOL;

                $html_tag.=PHP_EOL.'console.log(new_id)'.PHP_EOL;

                $new_tags.='<div class="row" style="margin: 10px;">';
                    $new_tags.='<div class="col-md-12" style="padding-bottom: 5px;border-bottom: 2px solid #000;">';

                        foreach ($field_name as $key => $single_field) {
                            $new_tags.='<label for="">'.$label_name[$key].'</label>';
                            if ($type[$key]!="textarea") {
                                $new_tags.='<input type="'.$type[$key].'" class="'.$class[$key].'" value="'.$default_values[$key].'" name="'.$field_name[$key].'[]">';
                            }
                            else{
                                $new_tags.='<textarea class="new_id '.$class[$key].'" style="resize:vertical" name="'.$field_name[$key].'[]"></textarea>';


                            }

                        }//end foreach
                    $new_tags.='</div>';
                $new_tags.='</div>';



                //$html_tag.='$(".'.$contain_items_class.'").append($(".'.$first_item_class.'").first().clone());';
                $html_tag.='$(".'.$contain_items_class.'").append(\''.$new_tags.'\');'.PHP_EOL;

                $html_tag.='$(".new_id").addClass(new_id);';

                if ($add_tiny_mce=="yes") {
                    //$html_tag.="/*tinymce.remove();*/window.tinymce.dom.Event.domLoaded = true;".PHP_EOL;
                    //$html_tag.='tinyMCE.init({ selector: "#"+new_id});'.PHP_EOL;
                    //$html_tag.='console.log("."+new_id)';
                    $html_tag.='$.each($("."+new_id),function(){
                        if($(this).hasClass("ckeditor")){
                            $(this).ckeditor();
                        }
                    });'.PHP_EOL;
                }

                //increase count of text_area_counter
                $html_tag.='text_area_counter++;'.PHP_EOL;
                $html_tag.='return false;'.PHP_EOL;
            $html_tag.='});'.PHP_EOL;

        $html_tag.='});'.PHP_EOL;
    $html_tag.='</script>'.PHP_EOL;

    $html_tag.='<div class="col-md-12 form-group">';
        $html_tag.='<label for="">'.$label_name[0].' Section </label>';
        $html_tag.='<div class="col-md-12">';

        if (isset($values[0])&&count($values[0])&&is_array($values[0])) {
            foreach ($values[0] as $value_key => $item_values) {
                $html_tag.='<div class="row" style="margin: 10px;padding-bottom: 5px;border-bottom: 2px solid #000;">';
                foreach ($field_name as $field_key => $filed) {
                        if(!isset($values[$field_key][$value_key])){
                            continue;
                        }
                        $html_tag.='<div class="col-md-12">';
                            $html_tag.='<label for="">'.$label_name[$field_key].'</label>';
                            if ($type[$field_key]!="textarea") {
                                $item_val="";
                                if(isset($values[$field_key][$value_key])){
                                    $item_val=$values[$field_key][$value_key];
                                }
                                $html_tag.='<input type="'.$type[$field_key].'" class="'.$class[$field_key].'" name="'.$field_name[$field_key].'[]" value="'.$item_val.'">';
                            }
                            else{
                                $html_tag.='<textarea class="'.$class[$field_key].'" style="resize:vertical" name="'.$field_name[$field_key].'[]">'.$values[$field_key][$value_key].'</textarea>';
                            }
                        $html_tag.='</div>';

                }//end fileds foreach
                $html_tag.='<button type="button" class="btn btn-danger '.$remove_item_class.'">Remove</button>';
                $html_tag.='</div>';

            }//end values foreach
        }//end if

        $html_tag.='</div>';

        $html_tag.='<div class="col-md-12 '.$contain_items_class.'">';
            $html_tag.='<div class="row '.$first_item_class.'" style="margin: 10px;">';
                $html_tag.='<div class="col-md-12" style="padding-bottom: 5px;border-bottom: 2px solid #000;">';

                    foreach ($field_name as $key => $single_field) {
                        $html_tag.='<label for="">'.$label_name[$key].'</label>';
                        if ($type[$key]!="textarea") {
                            $html_tag.='<input type="'.$type[$key].'" class="'.$class[$key].'" value="'.$default_values[$key].'" name="'.$field_name[$key].'[]">';
                        }
                        else{
                            $html_tag.='<textarea id="tinymcetest" class="'.$class[$key].'" style="resize:vertical" name="'.$field_name[$key].'[]"></textarea>';
                        }

                    }//end foreach
                $html_tag.='</div>';
            $html_tag.='</div>';
        $html_tag.='</div>';

        $html_tag.='<div class="col-md-12">';
            $html_tag.='<button type="button" data-newid="'.(count($values[0])+1).'" class="btn btn-warning '.$add_item_class.'">Add Item</button>';
        $html_tag.='</div>';


    $html_tag.='</div></div>';

    return $html_tag;
}


/**
 * generate_slider_imgs_tags
 *
 * @return slider tags
 * 
 * @param $slider_photos array of img obj
 * @param string $field_name ex slider_file
 * @param string $field_label field label
 * @param string $field_id field id
 * @param string $accept file accept like image/*
 * @param string $need_alt_title if yes you can add title&alt else no title& alt generated
 * @param arr $additional_inputs_arr get params of generate_inputs_html($labels_name , $fields_name , $required , $type , $values , $class)
 * 
 * 
 */  

function generate_slider_imgs_tags($slider_photos=array(),$field_name="",$field_label="",$field_id="",$accept="image/*",$need_alt_title="yes",$additional_inputs_arr=array(),$show_as_link=false,$add_item_label="Add Image")
{   
    $html_tags="";
    
    $field_name_arr=$field_name."[]";
    $alt_field=$field_name."_alt[]";
    $title_field=$field_name."_title[]";
    
    $old_alt_field=$field_name."_edit_alt[]";
    $old_title_field=$field_name."_edit_title[]";
    
    
    $slider_img="slider_img".$field_name;
    $add_img_btn_class="add_page_slider_img_btn".$field_name;
    $slider_img_remover="slider_img_remover".$field_name;

    $slider_photos_ids=array();
    if (is_array($slider_photos)&&  count($slider_photos)) {
        $slider_photos_ids=convert_inside_obj_to_arr($slider_photos, "id");
    }
    
    $slider_photos_ids=  json_encode($slider_photos_ids);
    
    $json_values_of_slider_id="json_values_of_slider_id".$field_name;
    $json_values_of_slider="json_values_of_slider".$field_name;
	
    
    //js code
    $html_tags.='<script type="text/javascript">$(function(){'.PHP_EOL;
        
        $html_tags.='$(".'.$add_img_btn_class.'").click(function(){'.PHP_EOL;
            $html_tags.='var new_item=$(".'.$slider_img.' .item").first().clone();'.PHP_EOL;
            $html_tags.='$(".'.$slider_img.'").append(new_item);'.PHP_EOL;
            $html_tags.='return false;'.PHP_EOL;
        $html_tags.='});'.PHP_EOL;
        
        $html_tags.='$(".'.$slider_img_remover.'").click(function(){'.PHP_EOL;
            $html_tags.='$(this).parent().remove();'.PHP_EOL;
            $html_tags.='var removed_id=$(this).data("photoid");'.PHP_EOL;
            $html_tags.='var ids=JSON.parse($("#'.$json_values_of_slider_id.'").val());'.PHP_EOL;
            $html_tags.='$.each(ids,function(index,value){'.PHP_EOL;
                $html_tags.='if (removed_id==value) {'.PHP_EOL;
                    $html_tags.='ids.splice(index,1);'.PHP_EOL;
                $html_tags.='}'.PHP_EOL;
            $html_tags.='});'.PHP_EOL;
            $html_tags.='$("#'.$json_values_of_slider_id.'").val("["+ids.toString()+"]");console.log("["+ids.toString()+"]");'.PHP_EOL;
            $html_tags.='return false;'.PHP_EOL;
        $html_tags.='});'.PHP_EOL;
        
    $html_tags.='});</script>'.PHP_EOL;
    //end slider
    
    //END js code
    
    
    $html_tags.='<div class="form-group">';
       
        //row-fluid
        $html_tags.='<div class="row-fluid">';
            
            //slider tags
            $html_tags.='<div class="col-md-12 '.$slider_img.'">';
                $html_tags.='<div class="row item">';
                $html_tags.='<div class="col-md-4">';
                    $html_tags.='<label>'.$field_label.'</label>';
                    $html_tags.='<input type="file" class="form-control" name="'.$field_name_arr.'" id="'.$field_id.'" accept="'.$accept.'">';
                $html_tags.='</div>';
                if ($need_alt_title=="yes") {
                
                $html_tags.='<div class="col-md-4">';
                    $html_tags.='<label>Image Title</label>';
                    $html_tags.='<input type="text" class="form-control" name="'.$title_field.'" placeholder="Logo Title">';
                $html_tags.='</div>';
                
                $html_tags.='<div class="col-md-4">';
                    $html_tags.='<label>Image Alt</label>';
                    $html_tags.='<input type="text" class="form-control" name="'.$alt_field.'" placeholder="logo Alt">';
                $html_tags.='</div>';
                
                }//end if $need_alt_title
                
                if (is_array($additional_inputs_arr)&&count($additional_inputs_arr)) {
                    $html_tags.='<div class="col-md-6">';
                        //add additional fields
                        $empty_values=array();
                        for($i=0;$i<  count($additional_inputs_arr[0]);$i++){
                            $empty_values[]="";
                        }

                        $html_tags.=generate_inputs_html(
                                $labels_name=$additional_inputs_arr[0],
                                $fields_name=$additional_inputs_arr[1],
                                $required=$additional_inputs_arr[2],
                                $type=$additional_inputs_arr[3],
                                $values=$empty_values,
                                $class=$additional_inputs_arr[5]
                            );
                    $html_tags.='</div>';
                }//end if = add additional fields
                
                $html_tags.='</div>';
            $html_tags.='</div>';
            //END slider tags
            
            //Add Img btn
            $html_tags.='<div class="col-md-12 text-center">';
                $html_tags.='<a href="" class="btn btn-primary '.$add_img_btn_class.'">'.$add_item_label.'</a>';
            $html_tags.='</div>';
            //END Add Img btn
            
            //show_old_imgs
            $html_tags.='<div class="col-md-12">';
                //row-fluid
                $html_tags.='<div class="row-fluid">';
                    if (is_array($slider_photos)&&count($slider_photos)) {
                        $html_tags.='<ul>';
                            foreach ($slider_photos as $key => $img) {
                                $html_tags.='<li class="col-md-12">';
                                    $html_tags.='<div class="row">';
                                        $html_tags.='<div class="col-md-4">';
                                            if($show_as_link==true){
                                                $html_tags.='<a href="'.url("$img->path").'">Link</a>';
                                            }
                                            else{
                                                $html_tags.='<img src="'.url("$img->path").'" alt="'.$img->alt.'" title="'.$img->title.'" style="max-height:270px;width:100%;">';
                                            }
                                        $html_tags.='</div>';
                                        
                                        $html_tags.='<div class="col-md-8">';
                                            
                                            if ($need_alt_title=="yes") {
                                            $html_tags.='<div class="col-md-12 form-group">';
                                                $html_tags.='<label for="">Title</label>';
                                                $html_tags.='<input type="text" class="form-control slider_img_title" name="'.$old_title_field.'" placeholder="Slider Title" value="'.$img->title.'">';
                                            $html_tags.='</div>';
                                            
                                            $html_tags.='<div class="col-md-12 form-group">';
                                                $html_tags.='<label for="">Alt</label>';
                                                $html_tags.='<input type="text" class="form-control slider_img_alt" name="'.$old_alt_field.'" placeholder="Slider Alt" value="'.$img->alt.'">';
                                            $html_tags.='</div>';
                                            }//end if= need alt title
                                            
                                            if (is_array($additional_inputs_arr)&&count($additional_inputs_arr)) {
                                                //add additional fields
                                                $new_values=array();
                                                
                                                foreach ($additional_inputs_arr[4] as $input_v_key => $input_v) {
                                                    
                                                    if (isset($input_v[$key])) {
                                                        //$new_values[]=  array_shift($input_v);
                                                        $new_values[]=  $input_v[$key];

                                                    }
                                                    else{
                                                        $new_values[]="";
                                                    }
                                                }
                                                
                                                
                                                foreach ($additional_inputs_arr[1] as $field_key => $value) {
                                                    if ($key==0) {
                                                        $additional_inputs_arr[1][$field_key]="edit_".$additional_inputs_arr[1][$field_key];
                                                    }
                                                    
                                                }
                                                $html_tags.=generate_inputs_html(
                                                        $labels_name=$additional_inputs_arr[0],
                                                        $fields_name=$additional_inputs_arr[1],
                                                        $required=$additional_inputs_arr[2],
                                                        $type=$additional_inputs_arr[3],
                                                        $values=$new_values,
                                                        $class=$additional_inputs_arr[5]
                                                    );
                                            }//end if = add additional fields

                                        
                                        $html_tags.='</div>';
                                        $html_tags.='<a href="#" class="btn btn-danger '.$slider_img_remover.'" data-photoid="'.$img->id.'">Remove</a>';
                                    $html_tags.='</div>';
                                $html_tags.='</li>';
                            } 
                        $html_tags.='</ul>';
                    }
                $html_tags.='</div>';
                //END row-fluid
            $html_tags.='</div>';
            //END show_old_imgs

            
        $html_tags.='</div>';
        //END row-fluid
        
    $html_tags.='</div>';
    $html_tags.='<input type="hidden" id="'.$json_values_of_slider_id.'" name="'.$json_values_of_slider.'" value="'.htmlentities($slider_photos_ids, ENT_QUOTES, 'UTF-8').'">';

    return $html_tags;
}


                    
/**
 * generate_select_tags
 *
 * @return select tag with it selected option
 * 
 * @param field_name string
 * @param $label_name string
 * @param $text array() option text
 * @param $values array() option value
 * @param $selected_value array of selected values
 * @param $class string do not forget to add form-control
 * @param stirng $multiple put multipe if u want to select mutiple value 
 */            
function generate_select_tags($field_name,$label_name,$text,$values,$selected_value,$class="",$multiple="",$required="",$disabled = "",$data = "",$parent_div_class = "form-group col-md-12",$hide_label=false){

    if($selected_value==""){
        $selected_value=[""];
    }

    if(isset($data->$field_name)){
        $selected_value=$data->$field_name;
        if($multiple=="multiple"){
            $selected_value=json_decode($selected_value,true);
        }
        elseif(!is_array($data->$field_name)){
            $selected_value=array($data->$field_name);
        }
    }

    if(!is_array($selected_value)){
        $selected_value=[];
    }


    if($multiple!=""){
        $field_name.="[]";
    }

    $html_tags="";
    
    $field_id=$field_name."_id";


    $html_tags.='<div class="'.$parent_div_class.'" title="'.$label_name.'">';

        if (!$hide_label){
            $html_tags.='<label for="">'.$label_name.'</label>';
        }

        $html_tags.='<select '.
            $disabled.
            ' '.$multiple.
            ' name="'.$field_name.
            '" id="'.$field_id.
            '" class="'.$class.
            '" '.$required.'>';
            foreach ($values as $key => $value) {
                $selected="";
                if (in_array($value, $selected_value)) {
                    $selected="selected";
                }
                $html_tags.='<option value="'.$values[$key].'" '.$selected.' >'.$text[$key].'</option>';
            }
        $html_tags.='</select>';
    
    $html_tags.='</div>';
    
			
		
    return $html_tags;
}


function generate_radio_btns($field_name,$label_name,$text,$values,$selected_value="",$class="",$data = "",$grid = "col-md-12",$hide_label=false,$additional_data="",$custom_style="",$field_type="radio",$make_ck_button=false,$parent_div_col="",$make_input_multiple="[]"){

    if(isset($data->{$field_name})){
        $selected_value=$data->{$field_name};
    }

    if($field_type=="checkbox"){
        if(!is_array($selected_value)){
            if(is_object(json_decode($selected_value))){
                $selected_value=json_decode($selected_value,true);
            }
            else{
                $selected_value=[$selected_value];
            }
        }
    }

    $html_tags="";

    $field_id=$field_name."_id";



    $html_tags.='<div class="'.$grid.' form-group" '.$custom_style.'>';
    if(!$hide_label){
        $html_tags.='<p for="">'.$label_name.'</p>';
    }

    foreach ($values as $key => $value) {
        $selected="";

        if($field_type=="radio"){
            if ($selected_value!=""&$value==$selected_value) {
                $selected="checked";
            }
            if($key==0&&$selected_value==""){
                $selected="checked";
            }
        }
        elseif($field_type=="checkbox"){
            if (in_array($value,$selected_value)) {
                $selected="checked";
            }
        }


        if ($make_ck_button){
            if ($parent_div_col!="")
            $html_tags.='<div class="'.$parent_div_col.'">';

                $html_tags.='<div class="ck-button">';
                    $html_tags.='<label>';
                    $html_tags.='<input id="'.$field_id.'" class="'.$class.'" name="'.$field_name.$make_input_multiple.'" type="'.$field_type.'" value="'.$values[$key].'" '.$selected.' '.$additional_data.' >';
                    $html_tags.='<span>'.$text[$key].'</span>';
                    $html_tags.='</label>';
                $html_tags.='</div>';

            if ($parent_div_col!="")
            $html_tags.='</div>';
        }
        else{
            $html_tags.='<label class="radio-inline">';
            $html_tags.='<input id="'.$field_id.'" class="'.$class.'" name="'.$field_name.'" type="'.$field_type.'" value="'.$values[$key].'" '.$selected.' '.$additional_data.' >'.$text[$key];
            $html_tags.='</label>';
        }

    }
    $html_tags.='</div>';


    return $html_tags;
}


/**
 * generate_depended_selects
 *
 * @return string 2 select elements ,on change of first element 
 * second elment change relativley
 * 
 * @param string $field_name_1
 * @param string $field_label_1 
 * @param string_array $field_text_1 array of first select options text
 * @param string_array $field_values_1 array of first select options values
 * @param string $field_required_1 this field is required or not
 * @param string $field_class_1 elemet classes pls do not forget form-control
 * 
 * @param string $field_name_2
 * @param string $field_label_2 
 * @param string_array $field_text_2 array of second select options text
 * @param string_array $field_values_2 array of second select options values
 * @param string_array $field_2_depend_values depended values that select2 will change by select 1 values
 * @param string $field_required_2 this field is required or not
 * @param string $field_class_2 elemet classes pls do not forget form-control
 * @param string $field_data_name1 ex:data-fieldname
 * @param string_Array $field_data_values1 data_values_of_first_select
 * @param string $field_data_name2 ex:data-fieldname
 * @param string_Array $field_data_values2 data_values_of_sec_select
 */ 
function generate_depended_selects(
            $field_name_1,$field_label_1,$field_text_1,$field_values_1,$field_selected_value_1
            ,$field_required_1="",$field_class_1="",
            $field_name_2,$field_label_2,$field_text_2,$field_values_2,$field_selected_value_2,
            $field_2_depend_values,$field_required_2="",$field_class_2="",
            $field_data_name1 = "",$field_data_values1="",
            $field_data_name2 = "",$field_data_values2="",
            $first_grid="col-md-6",$second_grid="col-md-6"
    ){
    
    $field_id_1=$field_name_1."_id";
    $field_id_2=$field_name_2."_id";

    $field_div_container_2=$field_name_2."_div_container";
    
    $html_tags="";
    
    $html_tags.='<script type="text/javascript">'.PHP_EOL;
        $html_tags.='$(function(){'.PHP_EOL;
            $html_tags.='$("body").on("change","#'.$field_id_1.'",function(){'.PHP_EOL;
                $html_tags.='var select_1_value=$(this).val();'.PHP_EOL;
                $html_tags.='console.log(select_1_value);'.PHP_EOL;
                $html_tags.='$("#'.$field_id_2.' option").hide();'.PHP_EOL;
                $html_tags.='$(".'.$field_div_container_2.'").show();'.PHP_EOL;
                $html_tags.='var childs=$("#'.$field_id_2.' option[data-targetid="+select_1_value+"]");'.PHP_EOL;
                $html_tags.='$.each(childs,function(index,value){'.PHP_EOL;
                    $html_tags.='$(this).show();'.PHP_EOL;
                    $html_tags.='if (index==0) {'.PHP_EOL;
                        $html_tags.='$(this).attr("selected","selected")'.PHP_EOL;
                    $html_tags.='}'.PHP_EOL;
                $html_tags.='});'.PHP_EOL;
            $html_tags.='});'.PHP_EOL;
        $html_tags.='});'.PHP_EOL;
    $html_tags.='</script>'.PHP_EOL;

    
    $html_tags.='<div class="col-md-12 form-group">';
        $html_tags.='<div class="row">';
            //first select
            $html_tags.='<div class="'.$first_grid.'">';
                $html_tags.='<label>'.$field_label_1.'</label>';
                $html_tags.='<select id="'.$field_id_1.'" name="'.$field_name_1.'" class="'.$field_class_1.'" '.$field_required_1.'>';
                    $html_tags.='<option value="0"></option>';

                    foreach ($field_values_1 as $key => $value) {
                        $selected_value_1="";
                        if ($value==$field_selected_value_1) {
                            $selected_value_1="selected";
                        }
                        
                        $additional_attr="";
                        if (isset($field_data_values1[$key])) {
                            $additional_attr="data-$field_data_name1='".$field_data_values1[$key]."'";
                        }
                        
                        $html_tags.='<option '.$additional_attr.' value="'.$field_values_1[$key].'" '.$selected_value_1.' >'.$field_text_1[$key].'</option>';
                    }
                    
                $html_tags.='</select>';
            $html_tags.='</div>';
            
            //second select
            $display_none_select_2="";
            
            
            if ($field_selected_value_2=="") {
                $display_none_select_2="display:none;";
            }
            $html_tags.='<div class="'.$second_grid.' '.$field_div_container_2.'" style="'.$display_none_select_2.'" >';
                $html_tags.='<label>'.$field_label_2.'</label>';
                $html_tags.='<select id="'.$field_id_2.'" name="'.$field_name_2.'" class="'.$field_class_2.'" '.$field_required_2.'>';

                    $html_tags.='<option value="" data-targetid="0" >'."".'</option>';

                     foreach ($field_values_2 as $key => $value) {
                        $selected_option="";
                        $hide_option="style='display:none;'";
                        if ($field_2_depend_values[$key]==$field_selected_value_1) {
                            $hide_option="";
                        }
                        if ($value==$field_selected_value_2) {
                            $selected_option="selected";
                        }
                        
                        $additional_attr_sec="";
                        if (isset($field_data_values2[$key])) {
                            $additional_attr_sec="data-$field_data_name2='".$field_data_values2[$key]."'";
                        }
                        
                        $html_tags.='<option '.$additional_attr_sec.' value="'.$field_values_2[$key].'" data-targetid="'.$field_2_depend_values[$key].'" '.$selected_option.' '.$hide_option.' >'.$field_text_2[$key].'</option>';
                    }
                    
                $html_tags.='</select>';
            $html_tags.='</div>';

        $html_tags.='</div>';
    $html_tags.='</div>';

    
    return $html_tags;
}

// generate div of text depend on first select
function generate_depended_selects_and_text(
            $field_name_1,$field_label_1,$field_text_1,$field_values_1,$field_selected_value_1
            ,$field_required_1="",$field_class_1="",
            $field_name_2,$field_label_2,$field_text_2,$field_values_2,$field_selected_value_2,
            $field_2_depend_values,$field_required_2="",$field_class_2=""
    ){
    
    $field_id_1=$field_name_1."_id";
    $field_id_2=$field_name_2."_id";

    $field_div_container_2=$field_name_2."_div_container";
    
    $html_tags="";
    
    $html_tags.='<script type="text/javascript">'.PHP_EOL;
        $html_tags.='$(function(){'.PHP_EOL;
            $html_tags.='$("#'.$field_id_1.'").change(function(){'.PHP_EOL;
                $html_tags.='var select_1_value=$(this).val();'.PHP_EOL;
                $html_tags.='console.log(select_1_value);'.PHP_EOL;
                $html_tags.='$("#'.$field_id_2.' li").hide();'.PHP_EOL;
                $html_tags.='$(".'.$field_div_container_2.'").show();'.PHP_EOL;
                $html_tags.='var childs=$("#'.$field_id_2.' li[data-targetid="+select_1_value+"]");'.PHP_EOL;
                $html_tags.='$.each(childs,function(index,value){'.PHP_EOL;
                    $html_tags.='$(this).show();'.PHP_EOL;
                    $html_tags.='if (index==0) {'.PHP_EOL;
                        $html_tags.='$(this).attr("selected","selected")'.PHP_EOL;
                    $html_tags.='}'.PHP_EOL;
                $html_tags.='});'.PHP_EOL;
            $html_tags.='});'.PHP_EOL;
        $html_tags.='});'.PHP_EOL;
    $html_tags.='</script>'.PHP_EOL;

    
    $html_tags.='<div class="col-md-12 form-group">';
        $html_tags.='<div class="row">';
            //first select
            $html_tags.='<div class="col-md-6 col-md-offset-3">';
                $html_tags.='<label>'.$field_label_1.'</label>';
                $html_tags.='<select id="'.$field_id_1.'" name="'.$field_name_1.'" class="'.$field_class_1.'" '.$field_required_1.'>';
                    $html_tags.='<option value=""></option>';
                    
                    foreach ($field_values_1 as $key => $value) {
                        $selected_value_1="";
                        if ($value==$field_selected_value_1) {
                            $selected_value_1="selected";
                        }
                        $html_tags.='<option value="'.$field_values_1[$key].'" '.$selected_value_1.' >'.$field_text_1[$key].'</option>';
                    }
                    
                $html_tags.='</select>';
            $html_tags.='</div>';
            
            //second select
            $display_none_select_2="";
            
            
            if ($field_selected_value_2=="") {
                $display_none_select_2="display:none;";
            }
            $html_tags.='<div class="col-md-12 '.$field_div_container_2.'" style="'.$display_none_select_2.'" >';
                $html_tags.='<label>'.$field_label_2.'</label>';
                $html_tags.='<ul id="'.$field_id_2.'" name="'.$field_name_2.'" class="'.$field_class_2.'" '.$field_required_2.'>';
                    
                    foreach ($field_values_2 as $key => $value) {
                        $selected_option="";
                        $hide_option="style='display:none;'";
                        if ($field_2_depend_values[$key]==$field_selected_value_1) {
                            $hide_option="";
                        }
                        if ($value==$field_selected_value_2) {
                            $selected_option="selected";
                        }
                        $html_tags.='<li value="'.$field_values_2[$key].'" data-targetid="'.$field_2_depend_values[$key].'" '.$selected_option.' '.$hide_option.' >'.$field_text_2[$key].'</li>';
                    }
                    
                $html_tags.='</ul>';
            $html_tags.='</div>';

        $html_tags.='</div>';
    $html_tags.='</div>';

    
    return $html_tags;
}

function generate_accept_or_refuse($item_obj,$item_primary_col,$accept_or_refuse_col,$model){

    $html="";

    $html.="<a href='#' class='general_accept_item'  data-accept='".$item_obj->{$accept_or_refuse_col}."' data-accepturl='".url("/accept_item")."' data-tablename='".$model."' data-fieldname='".$accept_or_refuse_col."' data-itemid='".$item_obj->{$item_primary_col}."'>";
    if($item_obj->{$accept_or_refuse_col}==1){
        $html.="<div class='inside_review_status review_status_".$item_obj->{$item_primary_col}."'>";
        $html.="<span class='label label-success'>This is accepted,  Reject <i class='fa fa-close'></i> ?</span>";
        $html.="</div>";
    }
    else{
        $html.="<div class='inside_review_status review_status_".$item_obj->{$item_primary_col}."'>";
        $html.="<span class='label label-warning'>This is Rejected, Accept <i class='fa fa-check'></i> ?</span>";
        $html.="</div>";
    }

    $html.="</a>";

    return $html;
}

function generate_multi_accepters($accepturl="",$item_obj,$item_primary_col,$accept_or_refuse_col,$model,$accepters_data=["0"=>"Refused","1"=>"Accepted"]){

//    $accepters_data[1]="Accepted";
//    $accepters_data[2]="Refuseed";

    if($accepturl==""){
        $accepturl=url("/new_accept_item");
    }

    $html="";

    $html.='<div class="parent_accepters_div">';

        foreach ($accepters_data as $accepter_key=>$accepter_val){

            //$item_obj->{$accept_or_refuse_col}
            $html.="<a href='#' class='new_general_accept_item' data-acceptersdata='".json_encode($accepters_data)."'  data-accept='".$accepter_key."' data-accepturl='".$accepturl."' data-tablename='".$model."' data-fieldname='".$accept_or_refuse_col."' data-item_primary_col='".$item_primary_col."' data-itemid='".$item_obj->{$item_primary_col}."'>";
                if($item_obj->{$accept_or_refuse_col}==$accepter_key){
                    $html.="<label class='label label-info'>".$accepter_val."</label>";
                }else{
                    $html.=$accepter_val;
                }
            $html.="</a>&nbsp;&nbsp;&nbsp;&nbsp;";
        }




    $html.='</div>';



    return $html;
}

function generate_self_edit_input($url="",$item_obj,$item_primary_col,$item_edit_col,$table="",$input_type="text",$label="Click To Edit"){

    if($url==""){
        $url=url("/general_self_edit");
    }

    if($table!=""){
        $table="data-tablename='$table'";
    }

    $html="";

        $html.='<span class="general_self_edit" data-url="'.$url.'" data-row_primary_col="'.$item_primary_col.'" data-row_id="'.$item_obj->{$item_primary_col}.'" '.$table.' data-field_name="'.$item_edit_col.'" data-field_value="'.$item_obj->{$item_edit_col}.'" title="'.$label.'" data-input_type="'.$input_type.'">';
            $html.=" ".$item_obj->{$item_edit_col}." ";
            $html.='<i class="fa fa-edit"></i>';
        $html.='</span>';


    return $html;
}

function attrs_divider($attrs,$attrs_length=7,$dividers_arries){

    $new_attrs=[];

    foreach($dividers_arries as $divider_arr){
        $new_attrs_item=[];
        foreach($divider_arr as $field_key=>$field){
            for($i=0;$i<$attrs_length;$i++){
                if(!isset($new_attrs_item[$i])){
                    $new_attrs_item[$i]=[];
                }

                $new_attrs_item[$i][$field]=$attrs[$i][$field];

            }
        }
        $new_attrs[]=$new_attrs_item;
    }

    return $new_attrs;
}