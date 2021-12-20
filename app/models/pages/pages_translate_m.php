<?php

namespace App\models\pages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class pages_translate_m extends Model
{
    use SoftDeletes;

    protected $table = "pages_translate";

    protected $primaryKey = "id";

    protected $fillable = [
        'page_id','trans_order', 'page_title','page_broker_third_link_title',
        'page_url','page_url2','page_url3',
        'page_city', 'page_country', 'page_period',
        'page_short_desc', 'page_body', 'page_itinerary', 'page_inclusions',
        'page_exclusions', 'page_meta_title', 'page_meta_desc', 'page_meta_keywords',
        'page_slug', 'lang_id'
    ];

    protected $dates = ["deleted_at"];

}
