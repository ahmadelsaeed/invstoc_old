<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class support_messages_m extends Model
{
    use SoftDeletes;

    protected $table = "support_messages";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'user_id','msg_type','name','full_name', 'tel', 'email','msg_title' ,
        'message', 'current_url', 'source','other_data','img_id'

    ];

    static function get_data($additional_where = "", $order_by = "" , $limit = "")
    {
        $results = DB::select("
             select 
  
             support.*,
              u.username
              
             #joins
             from support_messages as support
             LEFT JOIN users as u on (u.user_id = support.user_id AND u.deleted_at is NULL )
          
             #where
             where support.deleted_at is null $additional_where
             
             #order by
             $order_by
             
             #limit
             $limit ");

        foreach($results as $key => $support_obj)
        {

            $support_obj->imgs_data = [];
            $img_id = json_decode($support_obj->img_id);
            if ( is_array($img_id) && count($img_id))
            {
                $support_obj->imgs_data = attachments_m::whereIn("id",$img_id)->get()->all();
            }

        }

        return $results;

    }



}
