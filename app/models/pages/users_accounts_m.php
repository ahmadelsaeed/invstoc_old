<?php

namespace App\models\pages;

use File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class users_accounts_m extends Model
{
    use SoftDeletes;

    protected $table = "users_accounts";

    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'page_id','user_id','ref_user_id','account_number','account_balance'
    ];


    static function get_top_brokers_accounts($default_lang_id=1)
    {
        $results = DB::select("
            
            select 
            count(account.page_id) as broker_accounts,
            page.*,
            page_trans.*,

            #small_img
            small_page_img.path as small_img_path, small_page_img.title as small_img_title, small_page_img.alt as small_img_alt
             
            #big_img
            ,big_page_img.path as big_img_path, big_page_img.title as big_img_title, big_page_img.alt as big_img_alt
             
            
            FROM users_accounts as account
            INNER JOIN pages as page on (account.page_id = page.page_id AND page.deleted_at IS NULL)
            inner join pages_translate as page_trans on (page.page_id = page_trans.page_id and page_trans.lang_id = $default_lang_id)

            LEFT OUTER JOIN attachments small_page_img on (page.small_img_id = small_page_img.id)
            LEFT OUTER JOIN attachments big_page_img on (page.big_img_id = big_page_img.id)
    
            #where
            where account.deleted_at is null  
            GROUP BY account.page_id
            order BY count(account.page_id) desc
            
            limit 10
        
        ");

        return $results;
    }


}
