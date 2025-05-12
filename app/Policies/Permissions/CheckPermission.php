<?php

namespace App\Policies\Permissions;

class CheckPermission
{
    /**
     * Create a new policy instance.
     * @throws \Exception
     */
    public static function check($type,$model,$model_ids,$user,$operation_type="create")
    {
        $class=null;
        if ($type=="view"):
            $class=new View($model,$model_ids,$user);
            return $class->response();
        elseif ($type=="edit"):
            $class=new Edit($model,$model_ids,$user,$operation_type);
        endif;
        return !is_null($class) ? $class->response() : throw new \Exception("invalid role type");


    }
}
