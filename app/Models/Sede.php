<?php


namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    use HasFactory;

    protected $table = 'sede';

    protected $fillable = [
        'id',
        'name',
    ];

    public function users(){
        return $this->hasMany('App/Models/User');
    }

}
