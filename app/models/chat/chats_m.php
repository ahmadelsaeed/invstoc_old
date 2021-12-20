<?php

namespace App\models\chat;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class chats_m extends Model
{

    use SoftDeletes;

    protected $table="chats";
    protected $primaryKey="chat_id";
    protected $dates = ["deleted_at"];

    protected $fillable=[
        'chat_name', 'from_user_id', 'to_user_id'
    ];

    static function get_chats($additional_where = "", $order_by = "" , $limit = "")
    {
        $chats = DB::select("
             select 
             chat.*,
             
             from_user.email as 'from_user_email',
             from_user.username as 'from_user_username',
             from_user.full_name as 'from_user_full_name',
             from_user.user_type as 'from_user_user_type',
             from_user.user_active as 'from_user_user_active',
             
             to_user.email as 'to_user_email',
             to_user.username as 'to_user_username',
             to_user.full_name as 'to_user_full_name',
             to_user.user_type as 'to_user_user_type',
             to_user.user_active as 'to_user_user_active'
             
             #joins
             from chats as chat
             INNER JOIN users as from_user
                  on(chat.from_user_id = from_user.user_id and from_user.deleted_at is null)
                  
             INNER JOIN users as to_user
                  on(chat.to_user_id = to_user.user_id and to_user.deleted_at is null)

             #where
             where chat.deleted_at is null $additional_where
             
             #order by
             $order_by
             
             #limit
             $limit ");

        return $chats;

    }

}