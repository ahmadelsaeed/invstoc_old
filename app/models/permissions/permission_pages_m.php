<?php

namespace App\models\permissions;

use Illuminate\Database\Eloquent\Model;

class permission_pages_m extends Model
{
    protected $table = "permission_pages";
    protected $primaryKey = "per_page_id";
    public $timestamps = false;

    protected $fillable = [
        'page_name','sub_sys','show_in_admin_panel','all_additional_permissions'
    ];
}
