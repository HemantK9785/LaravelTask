<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultations extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','name', 'phone_no','email','consultation_schedule','status','specialty'
    ];

    //Name Set UpperCash
    public function getNameAttribute($value){
        return ucwords($value);
    }

    //Status Set UpperCash
    public function getStatusAttribute($value){
        return ucwords($value);
    }

    //Specialty Set UpperCash
    public function getSpecialtyAttribute($value){
        return ucwords($value);
    }
}
