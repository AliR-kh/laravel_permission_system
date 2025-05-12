<?php

namespace App\Interfaces\Permissions;

interface AssignPermissionRole
{

    public function team($model,$team_id);
    public function user($model,$user_id);
}
