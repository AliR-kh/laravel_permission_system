<?php

namespace App\Classes\Permission;

use App\Models\Permission;
use Spatie\Permission\Models\Role;

class AssignRoleToTeam
{
    public static function assignRole($name,$team_id)
    {
        $role=Role::query()->where("name",$name)->where("team_id",$team_id)->first();
        if (!is_null($role)):
            return $role;
        endif;
        return Role::query()->create([
            "name"=>$name,
            "team_id"=>$team_id
        ]);
    }
}
