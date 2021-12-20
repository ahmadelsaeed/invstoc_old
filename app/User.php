<?php

namespace App;

use App\models\attachments_m;
use App\models\chat\chat_messages_m;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\actions\posts\comments;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    use SoftDeletes;

    protected $fillable = [
        'logo_id', 'cover_id', 'email', 'username', 'first_name', 'last_name',
        'full_name', 'cat_id', 'birthdate', 'gender', 'country', 'city',
        'user_bio','user_interests', 'followers_count','ads_balance','referrer_count','referrer_balance',
        'followering_count', 'role', 'user_type', 'related_id', 'password', 'remember_token',
        'user_verified', 'user_active', 'user_is_blocked', 'user_can_login', 'is_privet_account',
        'request_referrer_link','referrer_link',
        'contacts', 'mobile', 'telephone', 'fax', 'address', 'work_on', 'faculty', 'allowed_lang_ids','timezone',
        'birthdate_privacy','country_privacy','city_privacy','is_logged_before',
        'not_seen_all_notifications','not_seen_messages','not_seen_followers_notifications',
        'verification_code','email_is_verified','email_verification_date'
    ];

    protected $primaryKey = 'user_id';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    protected $dates = ["deleted_at"];

    static function get_users($additional_where = "", $order_by = "" , $limit = "")
    {
        $users = DB::select("
             select 
              u.*,
              
              logo.path as 'logo_path',
              logo.path as 'path',
              logo.alt as 'logo_alt',
              logo.title as 'logo_title',
              
              cover.path as 'cover_path',
              cover.alt as 'cover_alt',
              cover.title as 'cover_title'
                         
             #joins
             from users as u
             LEFT OUTER JOIN attachments as logo on (u.logo_id = logo.id)
             LEFT OUTER JOIN attachments as cover on (u.cover_id = cover.id)

             #where
             where u.deleted_at is null $additional_where
             
             #order by
             $order_by
             
             #limit
             $limit ");

        return $users;
    }

    public function chats() {
        return $this->hasMany(chat_messages_m::class, 'from_user_id', 'user_id');
    }

    public function getContactsAttribute($value)
    {
        if (json_decode($value) != false) {
            return json_decode($value);
        }
        return [];
    }
    
    public function comments()
    {
        return $this->hasMany(comments::class);    
    }

}
