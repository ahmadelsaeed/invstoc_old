<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class users_or_pages_images_m extends Model
{
    use SoftDeletes;

    protected $table="users_or_pages_images";
    protected $primaryKey = "users_or_pages_image_id";
    protected $fillable=[
        "attachment_id",
        "user_id_or_users_pages_id",
        "attachment_type","post_id"

    ];

    protected $dates = ["deleted_at"];
}
