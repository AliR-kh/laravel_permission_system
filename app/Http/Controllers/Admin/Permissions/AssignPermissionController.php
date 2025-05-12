<?php

namespace App\Http\Controllers\Admin\Permissions;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Team;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class AssignPermissionController extends Controller
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
            $permission=Permission::query()->find($validated['permission_id']);
            $team->permissions()->syncWithoutDetaching($permission);
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
            $permission=Permission::query()->find($validated['permission_id']);
            $user->permissions()->syncWithoutDetaching($permission);
            return self::successResponse('ok');
        } catch (\Exception $exception) {
            return self::serverError(message: "server error", error: $exception->getMessage());
        }
    }


    public function teamValidator($request)
    {
        return Validator::make($request->all(), [
            "permission_id" => "required|exists:permissions,id",
            "team_id" => "required|exists:teams,id",
        ]);
    }

    public function userValidator($request)
    {
        return Validator::make($request->all(), [
            "permission_id" => "required|exists:permissions,id",
            "user_id" => "required|exists:users,id",
        ]);
    }
}
