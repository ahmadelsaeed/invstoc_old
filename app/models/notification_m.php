<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class notification_m extends Model
{

    protected $table = "notification";
    protected $primaryKey = "not_id";
    public $timestamps = false;

    protected $fillable = [
        'not_title', 'not_type', 'not_link', 'not_from_user_id', 'not_to_user_id',"created_at","updated_at"
    ];


    static function get_notifications($additional_where = "")
    {
        $results = DB::select("
            select 
            note.*,
            from_user.full_name as 'from_user_full_name',
            from_user_att.path as 'from_user_logo_path',
            note.created_at as 'note_created_at'
                 
            from notification as note
             
            INNER JOIN users as from_user on from_user.user_id=note.not_from_user_id
            LEFT OUTER JOIN attachments as from_user_att on from_user_att.id=from_user.logo_id
            where note.deleted_at is null 
            $additional_where 
         ");

        return $results;

    }

}
