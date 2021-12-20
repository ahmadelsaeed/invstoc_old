<?php

namespace App\models\chat;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class chat_messages_m extends Model
{
    protected $table="chat_messages";
    protected $primaryKey="chat_msg_id";

    protected $fillable=[
        'chat_id', 'from_user_id', 'to_user_id','message'
    ];


    static function get_chat_messages($additional_where = "", $order_by = "" , $limit = "")
    {
        $chats = DB::select("
             select 
             msg.*,
             msg.created_at as 'msg_date',
             
             from_user.email as 'from_user_email',
             from_user.username as 'from_user_username',
             from_user.full_name as 'from_user_full_name',
             from_user.user_type as 'from_user_user_type',
             from_user.user_active as 'from_user_user_active',
             from_img.path as 'from_img_path',
             
             to_user.email as 'to_user_email',
             to_user.username as 'to_user_username',
             to_user.full_name as 'to_user_full_name',
             to_user.user_type as 'to_user_user_type',
             to_user.user_active as 'to_user_user_active',
             to_img.path as 'to_img_path'

             #joins
             from chat_messages as msg
             INNER JOIN users as from_user
                  on(msg.from_user_id = from_user.user_id and from_user.deleted_at is null)
                  
              left JOIN attachments as from_img
                on(from_img.id = from_user.logo_id)
                  
             INNER JOIN users as to_user
                  on(msg.to_user_id = to_user.user_id and to_user.deleted_at is null)

              left JOIN attachments as to_img
                on(to_img.id = to_user.logo_id)

             #where
             $additional_where
             
             #order by
             $order_by
             
             #limit
             $limit ");

        return $chats;

    }

}