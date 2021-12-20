<?php

namespace App\Http\Controllers\front;

//use Illuminate\Http\Request;
use App\models\langs_m;
use App\models\pages\page_select_currency_m;
use App\models\pages\pages_m;
use Carbon\Carbon;
use Redirect;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class analytic_page extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware("check_user");
    }

    public function index()
    {

        $this->data["meta_title"]=show_content($this->data["pages_seo"],"analytics_meta_title");
        $this->data["meta_desc"]=show_content($this->data["pages_seo"],"analytics_meta_description");
        $this->data["meta_keywords"]=show_content($this->data["pages_seo"],"analytics_meta_keywords");

        return view('front.subviews.analytic_page', $this->data);
    }

}
