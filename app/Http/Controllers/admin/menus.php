<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\admin_controller;
use App\Http\Controllers\dashbaord_controller;
use App\models\category_m;
use App\models\pages_m;
use App\models\sortable_menus_m;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class menus extends admin_controller
{

    public function __construct() {
        parent::__construct();

    }

    public function index() {

        if (!check_permission($this->user_permissions,"admin/menus","show_action"))
        {
            return Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
        }

        $this->data["all_menus"]=sortable_menus_m::get();
        return view("admin.subviews.menus.show",$this->data);
    }

    public function get_menu($lang_id=1,$menu_id=null) {

        if (!check_permission($this->user_permissions,"admin/menus","show_action"))
        {
            return Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
        }

        $this->data["lang_id"]=$lang_id;

        // get menu items
        //all articles cats
        //all pages_m
        //all page_builder_m

        //get all articles cats
        $all_cats=new category_m();
        $this->data["all_article"]=$all_cats->get_all_cats($additional_where = " AND cat.cat_type='article' ", $order_by = "" , $limit = "",$make_it_hierarchical=true,$lang_id);

        $this->data["pages"]=pages_m::get_pages($additional_where = " AND page.page_type='default' ", $order_by = "" , $limit = "",$check_self_translates = true,$lang_id);



        $static_pages=[];

        $static_pages[]=[
            "name"=>"support",
            "url"=>"support"
        ];
        $static_pages[]=[
            "name"=>"statistics",
            "url"=>"statistics"
        ];
        $static_pages[]=[
            "name"=>"albums",
            "url"=>"albums"
        ];

        $static_pages[]=[
            "name"=>"files_center",
            "url"=>"files_center"
        ];
        $static_pages[]=[
            "name"=>"All Categories",
            "url"=>"all_cats"
        ];
        $static_pages[]=[
            "name"=>"directors",
            "url"=>"directors"
        ];
        $static_pages[]=[
            "name"=>"projects",
            "url"=>"projects"
        ];
        $static_pages[]=[
            "name"=>"create_society",
            "url"=>"create_society"
        ];
        $static_pages[]=[
            "name"=>"show_all_societies",
            "url"=>"all_societies"
        ];


        $static_pages[]=[
            "name"=>"rss",
            "url"=>"rss"
        ];






        $this->data["static_pages"]=$static_pages;



        // END get menu items

        $this->data["menu_data"] = "";

        if ($menu_id != null) {
            $this->data["menu_data"] = sortable_menus_m::find($menu_id);
        }


        return view("admin.subviews.menus.save",$this->data);
    }


    //sortable menus
    public function save_sortable_menu(Request $request) {

        $output=array();

        $menu_id=Input::get("menu_id");
        $menu_title=Input::get("menu_title");
        $menu_json=Input::get("menu_json");
        $lang_id=Input::get("lang_id");


        if ($menu_id==0) {
            //add

            if (!check_permission($this->user_permissions,"admin/menus","add_action"))
            {
                return Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
            }

            $returned_id=sortable_menus_m::create(array(
                "menu_title"=>"$menu_title",
                "menu_json"=>  json_encode($menu_json),
                "lang_id" => $lang_id
            ));
            $returned_id=$returned_id->menu_id;
        }
        else{

            if (!check_permission($this->user_permissions,"admin/menus","edit_action"))
            {
                return Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
            }

            //edit
            $old_row=sortable_menus_m::find($menu_id)->update([
                "menu_title"=>  "$menu_title",
                "menu_json"=>  json_encode($menu_json)
            ]);

            if($old_row==true){
                $output["success"]="edit successfully";
            }
            echo json_encode($output);
            return;
        }


        if ($returned_id>0) {
            $output["success"]="insetred successfully";
        }


        echo json_encode($output);

    }
    //END sortable menus

    public function delete_menu(Request $request){
        if (!check_permission($this->user_permissions,"admin/menus","delete_action"))
        {
            echo json_encode(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"]);
            return;
        }
        $this->general_remove_item($request,'App\models\sortable_menus_m');
    }
}
