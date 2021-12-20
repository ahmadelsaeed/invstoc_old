<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

class change_book_language extends Controller
{

    public function __construct()
    {
        parent::__construct();

    }

    public function index(Request $request)
    {

        $output = [];
        $output["data"] = "";

        $lang_id = intval(clean($request->get('lang_id',0)));
        if ($lang_id > 0)
        {
            \session([
                'book_lang_id' => $lang_id
            ]);
        }

        echo json_encode($output);
        return;
    }

}
