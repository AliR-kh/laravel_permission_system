<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class Team extends Model
{
    use HasRoles,HasApiTokens;
    protected $fillable=[
      'name'
    ];
    protected $guarded='sanctum';

    public function users()
    {
        return $this->belongsToMany(User::class,'team_user');
    }
}
