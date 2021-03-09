<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    use HasFactory;

    protected $table = 'document_type';

    protected $fillable = [
        'id',
        'name',
    ];


    // Un tipo de documento tiene muchos posts
    public function posts(){
        return $this->hasMany('App/Models/Post');
    }

}
