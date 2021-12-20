<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\models\langs_m;
use Illuminate\Http\Request;

class change_language extends Controller
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
        $lang_title = strtolower($request->get('lang_title','en'));

        if ($lang_id > 0)
        {
            \session([
                'current_lang_id' => $lang_id,
                'language_locale' => $lang_title
            ]);
        }

        echo json_encode($output);
        return;
    }

}
