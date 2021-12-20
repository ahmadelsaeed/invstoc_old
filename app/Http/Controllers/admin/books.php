<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\admin_controller;
use App\models\attachments_m;
use App\models\books\book_page_blocks_m;
use App\models\books\book_pages_m;
use App\models\currency_rates_m;
use App\models\pages\page_select_currency_m;
use App\models\pages\pages_m;
use App\models\pages\pages_translate_m;
use App\models\pair_currency_m;
use File;
use Illuminate\Http\Request;
use App\models\category_m;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class books extends admin_controller
{

    public function __construct()
    {
        parent::__construct();

    }

    public function index($book_id)
    {

        if (!check_permission($this->user_permissions,"admin/category","show_action"))
        {
            return  Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
        }


        $this->data["book_data"] = category_m::get_all_cats("
            AND cat.cat_id = $book_id
            AND cat.cat_type = 'book'
        ");

        abort_if((!count($this->data["book_data"])),404);

        $this->data["book_data"] = $this->data["book_data"][0];

        $allowed_lang_ids = $this->data["current_user"]->allowed_lang_ids;
        $allowed_lang_ids = json_decode($allowed_lang_ids);
        $allowed_lang_ids = implode(',',$allowed_lang_ids);

        $this->data["book_pages"] = book_pages_m::get_data("
            AND book_page.cat_id = $book_id 
            AND book_page.lang_id in ($allowed_lang_ids)
        ", $order_by = "
            order by book_page.book_page_number asc
        ");


        return view("admin.subviews.books.show")->with($this->data);
    }

    public function save_book_page(Request $request ,$book_id,$book_page_id = null)
    {

        if (!check_permission($this->user_permissions,"admin/category","show_action"))
        {
            return  Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
        }

        if (isset($_GET["book_page_number"]))
        {
            $book_page_number = intval($_GET["book_page_number"]);
            return  Redirect::to("admin/books/save_book_page/$book_id/$book_page_number")->with(
                ["msg"=>"<div class='alert alert-success'>Page is Here</div>"])->send();
        }

        $this->data["book_data"] = category_m::get_all_cats("
            AND cat.cat_id = $book_id
            AND cat.cat_type = 'book'
        ");

        abort_if((!count($this->data["book_data"])),404);

        $this->data["book_data"] = $this->data["book_data"][0];

        $allowed_lang_ids = $this->data["current_user"]->allowed_lang_ids;
        $allowed_lang_ids = json_decode($allowed_lang_ids);
        $allowed_lang_ids = implode(',',$allowed_lang_ids);


        $this->data["page_data"] = "";
        $this->data["page_blocks"] = [];

        if ($book_page_id != null)
        {
            $this->data["page_data"] = book_pages_m::get_data("
                AND book_page.book_page_id = $book_page_id 
            ");
            abort_if((!count($this->data["page_data"])),404);

            $this->data["page_data"] = $this->data["page_data"][0];
            $page_data = $this->data["page_data"];

            $this->data["page_blocks"] = book_page_blocks_m::
                leftJoin("attachments","attachments.id","=","book_page_blocks.block_img")
                ->where("book_page_id",$book_page_id)->get();
            abort_if((!count($this->data["page_blocks"])),404);

            $page_blocks = $this->data["page_blocks"];

            $get_all_book_pages = book_pages_m::
            where("cat_id",$book_id)
                ->where("lang_id",$page_data->book_lang_id)
                ->orderBy("book_page_number")->get()->all();
            $this->data["get_all_book_pages"] = $get_all_book_pages;

        }
        else{

            $get_all_book_pages = book_pages_m::
            where("cat_id",$book_id)
                ->where("lang_id",$this->lang_id)
                ->orderBy("book_page_number")->get()->all();
            $this->data["get_all_book_pages"] = $get_all_book_pages;
        }

//        dump($this->data["get_all_book_pages"]);

        if ($request->method() == "POST")
        {

            $this->validate($request,
                [
                    "lang_id" => "required"
                ],
                [
                    "lang_id.required" => "Language is required"
                ]
            );

            $block_bodys = $request->get('block_body');
            $block_img_positions = $request->get('block_img_position');

            $redirect_page_type = $request->get('redirect_page_type','exit');
            $book_page_number = $request->get("book_page_number",1);
            $lang_id = $request->get("lang_id",1);

            if ($book_page_id == null)
            {

                $get_count_book_pages = book_pages_m::
                    where("cat_id",$book_id)
                    ->where("book_page_number",">=",$book_page_number)
                    ->where("lang_id",$lang_id)
                    ->get()->all();

                foreach($get_count_book_pages as $key => $value)
                {
                    book_pages_m::findOrFail($value->book_page_id)->update([
                        "book_page_number" => ($value->book_page_number+1)
                    ]);
                }

                // add new page
                $page_obj = book_pages_m::create([
                    "cat_id" => $book_id,
                    "book_page_number" => $book_page_number,
                    "lang_id" => $request->get("lang_id"),
                ]);

                if (!is_object($page_obj))
                {
                    return  Redirect::to("admin/books/save_book_page/$book_id")->with(["msg"=>"<div class='alert alert-danger'>Can not Save Book Page !!</div>"])->send();
                }

                $book_page_blocks_arr = [];
                foreach($block_bodys as $key => $block_body)
                {
                    $block_img = 0;
                    $block_img_file = $request->get("block_img_$key","");

                    if (isset($request["block_img_$key"]))
                    {
                        $block_img = $this->general_save_img(
                            $request ,
                            $item_id=null,
                            "block_img_$key",
                            $new_title = "",
                            $new_alt = "",
                            $upload_new_img_check = "on",
                            $upload_file_path = "/books/pages",
                            $width = 0,
                            $height = 0,
                            $photo_id_for_edit = 0
                        );
                    }

                    $book_page_block_item = [
                        "book_page_id" => $page_obj->book_page_id,
                        "block_body" => $block_body,
                        "block_img" => $block_img,
                        "block_img_position" => array_shift($block_img_positions),
                        "created_at" => date("Y-m-d H:i:s"),
                        "updated_at" => date("Y-m-d H:i:s")
                    ];

                    $book_page_blocks_arr[] = $book_page_block_item;

                }
                book_page_blocks_m::insert($book_page_blocks_arr);

                if ($redirect_page_type == 'exit')
                {
                    return  Redirect::to("admin/books/pages/$book_id/")->with(
                        ["msg"=>"<div class='alert alert-success'>Page is Saved Successfully ..</div>"])->send();
                }

                return  Redirect::to("admin/books/save_book_page/$book_id/")->with(
                    ["msg"=>"<div class='alert alert-success'>Page is Saved Successfully ..</div>"])->send();


            }
            else{

                // edit page blocks
                $edited_ids = [];
                foreach($block_bodys as $key => $block_body)
                {

                    if (isset($request['book_page_block_id'][$key]))
                    {
                        $book_page_block_id = $request['book_page_block_id'][$key];

                        $book_page_block_id = intval($book_page_block_id);
                        $edited_ids[] = $book_page_block_id;
                    }
                    else{
                        $book_page_block_id = 0;
                    }

                    if ($book_page_block_id > 0)
                    {
                        $block_img = $page_blocks->where("book_page_block_id",$book_page_block_id)->first()->block_img;
                    }
                    else{
                        $block_img = 0;
                    }

                    if (isset($request["block_img_$key"]))
                    {
                        $block_img = $this->general_save_img(
                            $request ,
                            $item_id=$book_page_block_id,
                            "block_img_$key",
                            $new_title = "",
                            $new_alt = "",
                            $upload_new_img_check = "on",
                            $upload_file_path = "/books/pages",
                            $width = 0,
                            $height = 0,
                            $photo_id_for_edit = $block_img
                        );
                    }
                    else if (!isset($request["old_block_img_id_$book_page_block_id"]))
                    {
                        $block_img = 0;
                    }

                    $book_page_block_item = [
                        "book_page_id" => $book_page_id,
                        "block_body" => $block_body,
                        "block_img" => $block_img,
                        "block_img_position" => array_shift($block_img_positions)
                    ];

                    if ($book_page_block_id > 0)
                    {
                        book_page_blocks_m::findOrFail($book_page_block_id)->update($book_page_block_item);
                    }
                    else{
                        book_page_blocks_m::create($book_page_block_item);
                    }

                }


                if ($page_data->book_page_number != $book_page_number)
                {

                    if ($book_page_number > $page_data->book_page_number)
                    {
                        $get_count_book_pages = book_pages_m::
                            where("cat_id",$book_id)
                            ->where("book_page_number",">=",($page_data->book_page_number+1))
                            ->where("book_page_number","<=",$book_page_number)
                            ->where("lang_id",$lang_id)
                            ->get()->all();

                        foreach($get_count_book_pages as $key => $value)
                        {
                            book_pages_m::findOrFail($value->book_page_id)->update([
                                "book_page_number" => ($value->book_page_number-1)
                            ]);
                        }
                    }
                    else{
                        $get_count_book_pages = book_pages_m::where("cat_id",$book_id)
                            ->where("book_page_number",">=",$book_page_number)
                            ->where("book_page_number","<=",($page_data->book_page_number-1))
                            ->where("lang_id",$lang_id)
                            ->get()->all();

                        foreach($get_count_book_pages as $key => $value)
                        {
                            book_pages_m::findOrFail($value->book_page_id)->update([
                                "book_page_number" => ($value->book_page_number+1)
                            ]);
                        }
                    }

                }


                book_pages_m::findOrFail($book_page_id)->update([
                    "book_page_number" => $book_page_number
                ]);

                $old_ids = $page_blocks->pluck('book_page_block_id')->all();
                $diff_ids = array_diff($old_ids,$edited_ids);
                if (count($diff_ids))
                {
                    book_page_blocks_m::whereIn('book_page_block_id',$diff_ids)->delete();
                }


                if ($redirect_page_type == 'exit')
                {
                    return  Redirect::to("admin/books/pages/$book_id/")->with(
                        ["msg"=>"<div class='alert alert-success'>Page is Saved Successfully ..</div>"])->send();
                }

                return  Redirect::to("admin/books/save_book_page/$book_id/")->with(
                    ["msg"=>"<div class='alert alert-success'>Page is Saved Successfully ..</div>"])->send();
            }


        }


        return view("admin.subviews.books.save_book_page")->with($this->data);
    }

    public function show_book_pages(Request $request)
    {

        $output = [];
        $output["options"] = "";

        $book_id = $request->get('book_id',0);
        $lang_id = $request->get('lang_id',0);
        $book_page_number = $request->get('book_page_number',0);
        $book_page_id = $request->get('book_page_id',0);

        $book_page_number = intval($book_page_number);
        $book_page_id = intval($book_page_id);
        $book_id = intval($book_id);
        $lang_id = intval($lang_id);

        $get_all_book_pages = book_pages_m::
            where("cat_id",$book_id)
            ->where("lang_id",$lang_id)
            ->orderBy("book_page_number")->get()->all();

        $options = "";
        if (!count($get_all_book_pages))
        {
            $options = "<option value='1'>First Page</option>";
        }
        else{
            foreach($get_all_book_pages as $key =>$page)
            {
                $selected_attr = "";
                if ($book_page_number == $page->book_page_number)
                {
                    $selected_attr = "selected";
                }
                $options .= "<option value='$page->book_page_number' $selected_attr>Page N.$page->book_page_number</option>";
            }
            if ($book_page_id == 0)
            {
                $options .= "<option value='".(count($get_all_book_pages)+1)."'>Last Page</option>";
            }

        }


        $output["options"] = $options;

        echo json_encode($output);
        return;
    }

    public function remove_book_page(Request $request)
    {
        $item_id = (int)$request->get("item_id");

        $book_obj = book_pages_m::findOrFail($item_id);

        $this->general_remove_item($request,'App\models\books\book_pages_m');

        // reorder this book pages
        $get_book_all_other_pages = book_pages_m::
        where("cat_id",$book_obj->cat_id)
            ->where("lang_id",$book_obj->lang_id)
            ->where("book_page_id","<>",$item_id)
            ->orderBy("book_page_order","asc")->get()->all();


        if (count($get_book_all_other_pages))
        {
            foreach($get_book_all_other_pages as $key => $book_page)
            {
                book_pages_m::findOrFail($book_page->book_page_id)->update([
                    "book_page_number" => ($key+1)
                ]);
            }
        }

        // remove all page blocks
        book_page_blocks_m::where("book_page_id",$item_id)->delete();

        return;
    }

}
