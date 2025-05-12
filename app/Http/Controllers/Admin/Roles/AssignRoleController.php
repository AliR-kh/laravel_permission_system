<?php

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class AssignRoleController extends Controller
{
    use ApiResponse;

    public function team(Request $request)
    {
        try {
            $validated = $this->teamValidator($request);
            if ($validated->fails()):
                return self::badRequest(message: "not ok", error: $validated->errors());
            endif;
            $validated=$validated->validated();
            $team=Team::query()->find($validated['team_id']);
            $role=Role::query()->find($validated['role_id']);
            $team->roles()->syncWithoutDetaching($role);
            return self::successResponse('ok');
        } catch (\Exception $exception) {
            return self::serverError(message: "server error", error: $exception->getMessage());
        }
    }
    public function user(Request $request)
    {
        try {
            $validated = $this->userValidator($request);
            if ($validated->fails()):
                return self::badRequest(message: "not ok", error: $validated->errors());
            endif;
            $validated=$validated->validated();
            $user=User::query()->find($validated['user_id']);
            $role=Role::query()->find($validated['role_id']);
            $user->roles()->syncWithoutDetaching($role);
            return self::successResponse('ok');
        } catch (\Exception $exception) {
            return self::serverError(message: "server error", error: $exception->getMessage());
        }
    }


    public function teamValidator($request)
    {
        return Validator::make($request->all(), [
            "role_id" => "required|exists:roles,id",
            "team_id" => "required|exists:teams,id",
        ]);
    }

    public function userValidator($request)
    {
        return Validator::make($request->all(), [
            "role_id" => "required|exists:roles,id",
            "user_id" => "required|exists:users,id",
        ]);
    }
}
