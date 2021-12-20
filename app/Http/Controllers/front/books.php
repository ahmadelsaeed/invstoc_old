<?php

namespace App\Http\Controllers\front;

use App\models\books\book_page_blocks_m;
use App\models\books\book_pages_m;
use App\models\category_m;
use App\models\group_workshop\workshops_m;
use App\models\pages\pages_m;
use App\models\pages\pages_translate_m;
use DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;


class books extends Controller
{
    //
    public $lang_seg_1=false;
    public function __construct(){
        parent::__construct();
        $this->middleware("check_user");
        $url_seg_1=\Request::segment(1);
        $all_langs_titles=convert_inside_obj_to_arr($this->data["all_langs"],"lang_title");

        if(in_array($url_seg_1,$all_langs_titles)) {
            $this->lang_seg_1=true;
        }

    }

    public function index($lang_title=""){

        $slug_segment=4;

        if($this->lang_seg_1){
            $slug_segment=5;
        }

        $cat_slug=\Request::segment($slug_segment);
        $cat_slug=urldecode($cat_slug);
        $cat_slug=clean($cat_slug);

        $cat_data=category_m::get_all_cats(
            $additional_where = "
                AND cat_translate.cat_slug='$cat_slug'
                AND cat.hide_cat=0
                AND cat.cat_type='activity'
            ",
            $order_by = "" ,
            $limit = "",
            $make_it_hierarchical=false,
            $default_lang_id=$this->lang_id
        );


        if(is_array($cat_data)&&count($cat_data)){
            $cat_data=$cat_data[0];
        }
        else{
            return abort(404);
        }

        $this->data["cat_data"]=$cat_data;

        $this->data["meta_title"]=$cat_data->cat_meta_title;
        $this->data["meta_desc"]=$cat_data->cat_meta_desc;
        $this->data["meta_keywords"]=$cat_data->cat_meta_keywords;


        //get this cat books
        $child_books=category_m::get_all_cats(
            $additional_where = "
                AND cat.parent_id=$cat_data->cat_id
                AND cat.hide_cat=0
                AND cat.cat_type='book'
            ",
            $order_by = " order by cat_order " ,
            $limit = " limit 8 ",
            $make_it_hierarchical=false,
            $default_lang_id=$this->lang_id
        );


        $this->data["books"]=$child_books;

        #region get workshops

            $this->data["workshops"] = workshops_m::join("users","workshops.owner_id","=","users.user_id")
                ->leftJoin("attachments","workshops.workshop_logo","=","attachments.id")
                ->where("workshops.cat_id",$cat_data->cat_id)
                ->orderBy("workshops.workshop_id","desc")->get()->all();

        #endregion

        return view('front.subviews.activity.books.index',$this->data);
    }

    public function load_more_books(Request $request)
    {

        $output["msg"] = "";
        $output["items"] = "";

        $offset = intval(clean($request->get('offset')));
        $cat_id = intval(clean($request->get('cat_id')));
        $target_search = clean($request->get('target_search',''));

        if (!empty($target_search))
        {
            $cond = " AND cat_translate.cat_name like '%$target_search%' ";
        }
        else{
            $cond = " AND cat.parent_id=$cat_id ";
        }

        $child_books = category_m::get_all_cats(
            $additional_where = "
                $cond
                AND cat.hide_cat=0
                AND cat.cat_type='book'
            ",
            $order_by = " order by cat_order " ,
            $limit = " limit 8 offset $offset ",
            $make_it_hierarchical=false,
            $default_lang_id=$this->lang_id
        );

        foreach($child_books as $key => $book)
        {
            $this_item = \View::make('blocks.book_block',[
                "book" => $book
            ])->render();

            $output["items"] .= '<div class="col col-xl-4 col-lg-3 col-md-4 col-sm-4 col-12">'.$this_item.'</div>';

        }

        echo json_encode($output);
        return;
    }

    public function view_book_old($book_id)
    {

        $this->data["cat_data"] = "";
        $this->data["page_data"] = "";
        $this->data["other_pages"] = "";

        $cat_data=category_m::get_all_cats(
            $additional_where = "
                AND cat.cat_id=$book_id
                AND cat.hide_cat=0
                AND cat.cat_type='book'
            ",
            $order_by = " order by cat_order " ,
            $limit = "",
            $make_it_hierarchical=false,
            $default_lang_id=$this->lang_id
        );

        if (!count($cat_data))
        {
            return abort(404);
        }

        $this->data["cat_data"] = $cat_data[0];


        $this->data["meta_title"]=$this->data["cat_data"]->cat_name;
        $this->data["meta_desc"]=$this->data["cat_data"]->cat_name;
        $this->data["meta_keywords"]=$this->data["cat_data"]->cat_name;

        $book_pages = pages_m::where("cat_id",$book_id)->get()->all();

        if (count($book_pages))
        {
            $book_pages_ids = convert_inside_obj_to_arr($book_pages,"page_id");

            $pages_without_pagination = pages_translate_m::whereIn("page_id",$book_pages_ids)
                ->where("lang_id",$this->lang_id)
                ->orderBy("trans_order");

            $pages = $pages_without_pagination->paginate(1);
            $this->data["other_pages"] = $pages;

            $get_page_data = $pages_without_pagination->get()->first();
            if (is_object($get_page_data))
            {
                $this->data["page_data"] = $get_page_data;
            }

            $get_book = category_m::findOrFail($book_id);
            $get_book->update([
                "cat_views" => ($get_book->cat_views + 1)
            ]);

        }

        return view('front.subviews.activity.books.view_book',$this->data);
    }

    public function view_book(Request $request, $book_id)
    {
        $book_id = $request->book_id;
        $this->data["cat_data"] = "";
        $this->data["pages_pagination"] = "";
        $this->data["page_data"] = [];

        $cat_data=category_m::get_all_cats(
            $additional_where = "
                AND cat_translate.cat_slug='$book_id'
                AND cat.hide_cat=0
                AND cat.cat_type='book'
            ",
            $order_by = " order by cat_order " ,
            $limit = "",
            $make_it_hierarchical=false,
            $default_lang_id=$this->lang_id
        );

        if (!count($cat_data))
        {
            return abort(404);
        }

        $cat_data = $cat_data[0];
        $this->data["cat_data"] = $cat_data;


        $this->data["meta_title"]=$this->data["cat_data"]->cat_name;
        $this->data["meta_desc"]=$this->data["cat_data"]->cat_name;
        $this->data["meta_keywords"]=$this->data["cat_data"]->cat_name;

        $book_pages = book_pages_m::
            where("cat_id",$cat_data->cat_id)
            ->where("lang_id",$this->book_lang_id)
            ->where("hide_page",0)
            ->orderBy("book_page_number")
            ->paginate(1);
        $this->data["pages_pagination"] = $book_pages;



        if (!empty($book_pages) && isset($book_pages[0]))
        {

            $book_page_id = $book_pages[0]->book_page_id;

            $this->data["page_data"] = book_page_blocks_m::
                leftJoin("attachments","attachments.id","=","book_page_blocks.block_img")
                ->where("book_page_id",$book_page_id)->get()->all();

            $book_page_obj = book_pages_m::findOrFail($book_page_id);

            $book_page_obj->update([
                "book_page_visits" => ($book_page_obj->book_page_visits+1)
            ]);

            $get_book = category_m::findOrFail($cat_data->cat_id)->update([
                "cat_views" => ($cat_data->cat_views + 1)
            ]);

        }


        return view('front.subviews.activity.books.view_book',$this->data);
    }


    public function move_books()
    {

        die();
        // get books
        $get_books = category_m::where("cat_type","book")->get()->all();

        foreach($get_books as $key => $book)
        {

            // get pages for this book
            $get_pages = pages_translate_m::
                join("pages","pages.page_id","=","pages_translate.page_id")
                ->where("pages.cat_id",$book->cat_id)->get()->all();

            $page_index = 1;
            foreach($get_pages as $key2 => $page)
            {
                if (empty($page->page_body))
                continue;

                // create new page
                $new_page_obj = book_pages_m::create([
                    "cat_id" => $book->cat_id,
                    "book_page_number" => $page_index,
                    "lang_id" => $page->lang_id,
                ]);

                book_page_blocks_m::create([
                    "book_page_id" => $new_page_obj->book_page_id,
                    "block_body" => $page->page_body
                ]);

                $page_index ++;
            }

        }

    }

}
