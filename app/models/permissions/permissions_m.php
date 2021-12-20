<?php

namespace App\models\permissions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class permissions_m extends Model
{
    protected $table = "permissions";
    protected $primaryKey = "per_id";
    public $timestamps = false;

    protected $fillable = [
        'user_id' , 'per_page_id' , 'show_action' , 'add_action' , 'edit_action' , 'delete_action', 'additional_permissions'
    ];


    public static function get_permissions($additional_cond = "")
    {
        $results = DB::select("
            
            select per.* ,per_page.*
            
            from permissions as per
            INNER JOIN permission_pages as per_page on (per.per_page_id = per_page.per_page_id)
            $additional_cond
        
        ");

        return $results;
    }
}
