<?php


function generate_menu_links(
        $menu_data,
        $add_css_class="",
        $id="",
        $class_for_first_ul="",
        $level=0,
        $li_css_class="",
        $anchor_css_class="",
        $use_drop_down_menu="dropdown-menu",
        $class_for_li_level_0="",
        $just_one_level=true
        ){
    $html="";
    
    $html.="<ul class='".$class_for_first_ul." ".$add_css_class."' id='".$id."'>";
    
    foreach ($menu_data as $key => $item) {

        if(
            !isset($item["level_data"]["item_slug"])||
            !isset($item["level_data"]["item_name"])
        ){
            continue;
        }
        
        $li_class_attrs="";
        $dropdown_child_id="";
        if (isset($item["level_childs"])) {
            $dropdown_child_id="drop_down_"."level_".$level."_".$key;
            $li_class_attrs='data-toggle="dropdown" data-toggle="#'.$dropdown_child_id.'"';
        }
        
        $li_first_class="";
        if ($level==0) {
            $li_first_class=$class_for_li_level_0;
        }
        
        $href=url("/")."/";
        if (strpos($item["level_data"]["item_slug"], 'http') !== false) {
            $href="";
        }
        if($item["level_data"]["item_slug"]=="#"){
            $href="";
        }
        
        $span='';
        if (isset($item["level_childs"])&&is_array($item["level_childs"])&&  count($item["level_childs"])){
            $span='<span class="glyphicon glyphicon-menu-right pull-right"></span>';
        }

        $html.="<li data-lavel='".$level."' class='$li_css_class $li_first_class ".$item["level_data"]["item_class"]."'>"
                . "<a ".$li_class_attrs." href='".$href.$item["level_data"]["item_slug"]."' class='".check_active_link($item["level_data"]["item_slug"])." $anchor_css_class'>".
                    $item["level_data"]["item_name"] .$span
                ."</a>";
        
        if (isset($item["level_childs"])&&is_array($item["level_childs"])&&  count($item["level_childs"])) {
            $new_level=$level+1;
            if ($just_one_level==true) {
                $add_css_class="";
            }
            $html.=generate_menu_links($item["level_childs"],$add_css_class." $use_drop_down_menu",$dropdown_child_id,"",$new_level,$li_css_class,$anchor_css_class);
        }
        
        $html.="</li>";
    }
    
    
    
    $html.="</ul>";
   
 
    return $html;
}


//special case for nile_fine



function check_active_link($url) {
    $current_url=  Illuminate\Support\Facades\Route::getCurrentRoute()->getPath();
    if ($url==$current_url) {
        return "link_active";
    }
    
    return "";
}
