<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $fillable=[
      "user_id",
        "code",
        "attempts",
        "status"
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
