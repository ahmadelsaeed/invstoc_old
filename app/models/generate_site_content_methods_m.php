<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class generate_site_content_methods_m extends Model
{
    //

    protected $table="generate_site_content_methods";
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable=[
        'method_name', 'method_title', 'method_requirments', 'method_img_id'
    ];


    public function method_img()
    {
        return $this->hasOne('App\models\attachments_m',"id","method_img_id");
    }


}
