<?php

namespace App\models\books;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class book_page_blocks_m extends Model
{
    use SoftDeletes;

    protected $table = "book_page_blocks";

    protected $primaryKey = "book_page_block_id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'book_page_id', 'block_body', 'block_img', 'block_img_position',
        'created_at','updated_at'
    ];

}
