<?php

namespace App\Http\Controllers\Admin\Permissions;

use App\Http\Controllers\Controller;
use App\Interfaces\Permissions\AssignPermissionRole;
use Illuminate\Http\Request;

class AssignPermissionController extends Controller implements AssignPermissionRole
{
    public function team($model,$team_id)
    {

    }

    public function user($model,$user_id)
    {

    }
}
