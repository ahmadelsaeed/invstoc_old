<?php

namespace App\models\group_workshop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class workshops_count_m extends Model
{
    use SoftDeletes;

    protected $table = "workshops_count";
    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];
    protected $fillable = [
        'workshop_id', 'counter_date', 'counter','created_at','updated_at'
    ];

    // for total days until now
    static function get_top_workshops($limit = "limit 10")
    {
        $results = DB::select("
            
            select 
            sum(trend.counter) as trend_counter,
            workshop.*,
            workshop.created_at as 'workshop_creation_date',
            owner.*,
            profile_img.path
            
            from workshops_count as trend
            INNER JOIN workshops as workshop on (trend.workshop_id = workshop.workshop_id AND workshop.deleted_at is NULL)
            INNER JOIN users as owner on (workshop.owner_id = owner.user_id AND owner.deleted_at is NULL )
            LEFT OUTER JOIN attachments as profile_img on (owner.logo_id = profile_img.id)
            
            where trend.deleted_at is NULL
            GROUP BY trend.workshop_id
            order BY sum(trend.counter) desc
            
            $limit
                    
        ");


        return $results;
    }



}
