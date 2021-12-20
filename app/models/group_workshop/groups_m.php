<?php

namespace App\models\group_workshop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class groups_m extends Model
{
    use SoftDeletes;

    protected $table = "groups";
    protected $primaryKey = "group_id";

    protected $dates = ["deleted_at"];
    protected $fillable = [
        'group_logo','group_owner_id', 'group_name','group_post_options', 'group_visits'
    ];

    static function get_data($cond = "")
    {
        $results = DB::select("
            
            select 
            
            group_obj.*,
            group_obj.created_at as 'group_creation_date',
            owner.*,
            (
              select count(*) from group_members as members where members.group_id = group_obj.group_id
              AND members.user_role = 'admin'
            ) as 'admins_count',
            owner.*,
            (
              select count(*) from group_members as members where members.group_id = group_obj.group_id
              AND members.user_role = 'member'
            ) as 'members_count',
            attach.*,
            group_attach.path as 'group_attach_path',
            group_attach.title as 'group_attach_title',
            group_attach.alt as 'group_attach_alt'
            
            from groups as group_obj 
            INNER JOIN users as owner on (group_obj.group_owner_id = owner.user_id AND owner.deleted_at is NULL )
            LEFT OUTER JOIN attachments as attach on (owner.logo_id = attach.id)
            LEFT OUTER JOIN attachments as group_attach on (group_obj.group_logo = attach.id)
            
            where group_obj.deleted_at is NULL 
            
            $cond
                    
        ");


        return $results;
    }


}
