<?php

namespace App\models\group_workshop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class workshops_m extends Model
{
    use SoftDeletes;

    protected $table = "workshops";
    protected $primaryKey = "workshop_id";

    protected $dates = ["deleted_at"];
    protected $fillable = [
        'workshop_logo','owner_id', 'workshop_name', 'cat_id', 'workshop_visits'
    ];

}
