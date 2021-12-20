<?php

namespace App\models\posts;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class posts_m extends Model
{
    use SoftDeletes;

    protected $table = "posts";
    protected $primaryKey = "post_id";

    protected $dates = ["deleted_at"];
    protected $fillable = [
        'user_id',
        'post_where','post_where_id',
        'users_pages_id',
        'post_share_id', 'post_or_recommendation',
        'post_type', 'cat_id', 'pair_currency_id','sell_or_buy',
        'post_body','post_orders_ids', 'post_files', 'post_is_approved',
        'post_is_blocked',

        'post_likes_count', 'post_comments_count',
        'post_shares_count', 'post_seen_count', 'post_reports_count',

        'post_privacy', 'hide_post','updated_by','posts_youtube_links',
        'expected_price', 'closed_price','recommendation_status',
        'recommendation_end_date','order_is_not_closed','is_not_editable',
        'take_profit','stop_loss'
    ];


    public static function check_unclosed_orders($user_id){

        $unclosed_orders_number=self::
        where("post_or_recommendation","recommendation")->
        where("user_id",$user_id)->
        where("post_share_id",0)->
        where("closed_price","0.00")->
        where("recommendation_end_date","<",date("Y-m-d"))->
        count();

        if($unclosed_orders_number==0)return true;

        return false;
    }

}
