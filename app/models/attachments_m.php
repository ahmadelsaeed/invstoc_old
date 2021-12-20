<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class attachments_m extends Model
{
    //
    protected $table="attachments";
    protected $fillable=[

        "title",
        "alt",
        "path"

    ];

    static function get_imgs_from_arr($ids){
         return DB::table('attachments')
            ->whereIn('id', $ids)
            ->get();
    }

    static function save_img($id = null , $title = "" , $alt = "" , $path = ""){

        // for insert
        if ($id == null)
        {
            $img = attachments_m::create([
                "title" =>  $title,
                "alt" =>  $alt,
                "path" =>  $path
            ]);

            // get last inserted id
            return $img->id;
        }

        // for edit if path exist
        if ($path != "")
        {
            $last_img_data = attachments_m::find($id);

            if(is_object($last_img_data)){

                if (is_file(public_path($last_img_data->path))){
                    unlink(public_path($last_img_data->path));
                }

                attachments_m::where('id',$id)->update([
                    "title" =>  $title,
                    "alt" =>  $alt,
                    "path" =>  $path
                ]);
            }else{
                //add
                $img = attachments_m::create([
                    "title" =>  $title,
                    "alt" =>  $alt,
                    "path" =>  $path
                ]);

                // get last inserted id
                return $img->id;
            }


        }
        // for edit if path not exist
        else{

            attachments_m::where('id',$id)->update([
                "title" =>  $title,
                "alt" =>  $alt
            ]);
        }

        return $id;

    }

}
