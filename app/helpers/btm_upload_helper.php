<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function cms_upload($user_id=0,$files,$folder,$width=0,$height=0,$ext_arr=array(),$return_only_name=false,$absoulte_upload_path="")
{
    $uploaded=array();
    //check if folder_exist
    $CI=&get_instance();
    $url=$CI->config->item("upload_url")."$folder";
    
    if ($absoulte_upload_path!="") {
        $url=$absoulte_upload_path;
    }
    
    if (!file_exists($url)) {
        mkdir($url,0777,TRUE);
    }

    //upload
    if (!empty($_FILES[$files])) {
        $_FILES[$files]['name']=array_diff($_FILES[$files]['name'], array(""));
        foreach ($_FILES[$files]['name'] as $key => $name) {

            $ext = pathinfo($name, PATHINFO_EXTENSION); 
            if (in_array($ext, array("mp4","jpeg","png","jpg","MP4","JPEG","PNG","JPG"))||(count($ext_arr)>0&&  in_array($ext, $ext_arr))) {
                //encrypt $name
                $name=md5($name."student_activities".date('Y-m-d H:i:s').$user_id.$key.time()).".".$ext;
                if ($_FILES[$files]['error'][$key]==0&&move_uploaded_file($_FILES[$files]['tmp_name'][$key],$url.'/'.$name)) {

                    if ($width>0&&$height>0&&in_array($ext, array("jpeg","png","jpg","JPEG","PNG","JPG"))) {
                        smart_resize_image($url.'/'.$name,null,$width,$height,false,$url.'/'.$name);
                    }

                    if ($return_only_name==false) {
                        $uploaded[]= $CI->config->item("project_upload_folder")."$folder/".$name;
                    }else{
                        $uploaded[]= $name;
                    }
                }
                else
                {
                    $uploaded[]=$_FILES[$files]['error'][$key];
                }
            }
            else
            {
                return "not allowed type";
            }
        }//end foreach

    }
    else{
        // dump("No files are selected");
        $error_msg="No files are selected";
    }
    if (!empty($error_msg)) {
        return $error_msg;
    }
    return $uploaded;
}


// for only one file
function bakr_upload($user_id=0,$files,$folder,$width=0,$height=0)
{
    $uploaded=array();
    //check if folder_exist
    $CI=&get_instance();
    $url=$CI->config->item("upload_url")."$folder";
    
    if ($absoulte_upload_path!="") {
        $url=$absoulte_upload_path;
    }
    
    if (!file_exists($url)) {
        mkdir($url,0777,TRUE);
    }

    //upload
    if (!empty($_FILES)) {
            $name = $_FILES[$files]['name'];
            $ext = pathinfo($name, PATHINFO_EXTENSION); 
            if (in_array($ext, array("mp4","jpeg","png","jpg","MP4","JPEG","PNG","JPG"))) {
                //encrypt $name
                $name=md5($name."student_activities".date('Y-m-d H:i:s').$user_id.time()).".".$ext;
                if ($_FILES[$files]['error']==0&&move_uploaded_file($_FILES[$files]['tmp_name'],$url.'/'.$name)) {

                    if ($width>0&&$height>0&&in_array($ext, array("jpeg","png","jpg","JPEG","PNG","JPG"))) {
                        smart_resize_image($url.'/'.$name,null,$width,$height,false,$url.'/'.$name);
                    }

                    if ($return_only_name==false) {
                        $uploaded[]= $CI->config->item("project_upload_folder")."$folder/".$name;
                    }else{
                        $uploaded[]= $name;
                    }
                }
                else
                {
                    $uploaded[]=$_FILES[$files]['error'];
                }
            }
            else
            {
                return "not allowed type";
            }

        }
    else{
        // dump("No files are selected");
        $error_msg="No files are selected";
    }
    if (!empty($error_msg)) {
        return $error_msg;
    }
    return $uploaded;
}