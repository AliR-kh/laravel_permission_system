<?php

namespace App\Classes\Permission;

use App\Models\Permission;

class AssignPermissionToModel
{
    /**
     * Create a new class instance.
     */
    public static function assignPermission($name,$model,$id)
    {
        $model="App\\Models\\".$model;
        $name="permission of ".$name.strval($id);
        while (Permission::query()->where("name",$name)->exists()):
            $name.=strval(rand(0,999));
        endwhile;
        return Permission::query()->create([
            "name"=>$name,
            "model"=>$model,
            "model_id"=>$id,
            "guard_name"=>"sanctum"
        ]);

    }
}
