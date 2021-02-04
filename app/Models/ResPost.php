<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResPost extends Model
{
    use HasFactory;

    protected $table = "res_post";

    protected $filliable = [
        "id",
        "file_res",
        "user_id_res",
        "post_id_res"
    ];

    public function user(){
        return $this->belongsTo("App\Models\User","user_id_res");
    }

    public function post(){
        return $this->belongsTo("App\Models\Post","post_id_res");
    }




}
