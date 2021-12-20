<?php

namespace App\models\books;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class book_pages_m extends Model
{
    use SoftDeletes;

    protected $table = "book_pages";

    protected $primaryKey = "book_page_id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'cat_id', 'book_page_number', 'book_page_order', 'book_page_visits',
        'hide_page','lang_id'
    ];

    static function get_data($additional_where = "", $order_by = "" , $limit = "",$default_lang_id=1)
    {

        $cats = DB::select("

            SELECT 
            
            book_page.*,
            book_page.lang_id as 'book_lang_id',
            book_page.created_at as 'book_page_creation_date',
            cat.*,
            cat_translate.*,
            
            lang.lang_text,
            lang.lang_title,
        
            big_img.path as 'big_img_path',
            big_img.alt as 'big_img_alt',
            big_img.title as 'big_img_title',
            
            small_img.path as 'small_img_path' ,
            small_img.alt as 'small_img_alt' ,
            small_img.title as 'small_img_title',
            
            lang_img.path as 'lang_img_path',
            lang.lang_title as 'lang_title'
    
            FROM book_pages as book_page
            INNER JOIN `category` as cat on (book_page.cat_id = cat.cat_id AND cat.deleted_at is NULL )
            inner join category_translate as cat_translate on (cat.cat_id=cat_translate.cat_id AND cat_translate.lang_id=$default_lang_id)

            INNER JOIN langs as lang on (lang.lang_id = book_page.lang_id AND lang.deleted_at IS NULL)
            
            left outer join attachments as small_img on small_img.id=cat.small_img_id
            left outer join attachments as big_img on big_img.id=cat.big_img_id
            left outer join attachments as lang_img on lang_img.id=lang.lang_img_id
    
            #where
            where book_page.deleted_at is null $additional_where

            #order by
            $order_by

            #limit
            $limit "
        );

        return $cats;

    }




}
