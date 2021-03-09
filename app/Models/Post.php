<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $table = "posts";

    protected $fillable = [
        'title',
        'status',
        'category_id',
        'user_id',
        'document_type_id',
        'section'
    ];

    public function user(){
        return $this->belongsTo("App\Models\User", "user_id");
    }

    public function category()
    {
        return $this->belongsTo("App\Models\Category", "category_id");
    }

    public function documentType(){
        return $this->belongsTo("App\Models\Category","document_type_id");
    }

    public function resPost(){
        return $this->hasMany("App\Models\ResPost");
    }

}
