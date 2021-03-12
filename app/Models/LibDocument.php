<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibDocument extends Model
{
    use HasFactory;

    protected $table = "lib_document";

    protected $fillable = [
        'user_id',
        'file_lib',
        'title',
        'section',
        'document_type_id',
        'category_id'
    ];

    public function category()
    {
        return $this->belongsTo("App\Models\Category", "category_id");
    }

    public function user(){
        return $this->belongsTo("App\Models\User", "user_id");
    }

    public function documentType(){
        return $this->belongsTo("App\Models\DocumentType","document_type_id");
    }

    public function resPost(){
        return $this->hasMany("App\Models\ResPost");
    }


}
