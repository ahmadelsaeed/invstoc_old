<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class users_actions_count_m extends Model
{
    use SoftDeletes;

    protected $table = "users_actions_count";
    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];
    protected $fillable = [
        'user_id', 'counter_date', 'counter','created_at','updated_at'
    ];

    // for total days until now
    static function get_top_users()
    {
        $results = DB::select("
            
            select 
            sum(trend.counter) as trend_counter,
            u.*,
            profile_img.path
            
            from users_actions_count as trend
            INNER JOIN users as u on (trend.user_id = u.user_id AND u.deleted_at is NULL )
            LEFT OUTER JOIN attachments as profile_img on (u.logo_id = profile_img.id)
            
            where trend.deleted_at is NULL
            GROUP BY trend.user_id
            
            HAVING sum(trend.counter) > 0
            
            order BY sum(trend.counter) desc
            
            limit 10
                    
        ");


        return $results;
    }



}
