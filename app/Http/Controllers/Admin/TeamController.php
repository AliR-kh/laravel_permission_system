<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Permission\AssignRoleToTeam;
use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class TeamController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $teams = Team::all()->all();
            return self::successResponse(message: "ok", data: $teams);
        } catch (\Exception $exception) {
            return self::badRequest(message: "Not Ok", error: $exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = $this->storeValidator($request);
            if ($validator->fails()):
                return self::badRequest(message: 'not Ok',error: $validator->errors());
            endif;
            $team=Team::query()->create($validator->validated());
            AssignRoleToTeam::assignRole('admin',$team->id);
            AssignRoleToTeam::assignRole('editor',$team->id);
            AssignRoleToTeam::assignRole('viewer',$team->id);
            return self::successResponse(message: "ok", data: $team->attributesToArray());
        } catch (\Exception $exception) {
            return self::badRequest(message: "Not Ok", error: $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        try {
            $validator = $this->updateValidator($request,$team);
            if ($validator->fails()):
                return self::badRequest(message: 'not Ok',error: $validator->errors());
            endif;
            $team->update($validator->validated());
            return self::successResponse(message: "ok", data: $team);
        } catch (\Exception $exception) {
            return self::badRequest(message: "Not Ok", error: $exception->getMessage());
        }
    }


    public function assignUser()
    {

    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function storeValidator($request)
    {
        return Validator::make($request->all(), [
            "name" => "required|unique:teams,name",
        ]);
    }

    public function updateValidator($request, $team)
    {
        return Validator::make($request->all(), [
            "name" => ["required", Rule::unique('teams')->ignore($team->id)],
        ]);
    }
}
