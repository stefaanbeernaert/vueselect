<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    public $fillable = ['address','user_id'];

    public function users(){
        return $this->hasMany(User::class,'user_id');
    }
}
