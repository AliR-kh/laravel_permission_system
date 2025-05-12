<?php

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {
            $roles=Role::all()->select(['id','name','team_id'])->all();
            return self::successResponse(message:"OK",data:$roles);
        }catch (\Exception $e){
            return self::badRequest(message: "Not Ok" , error: $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validate=$this->storeValidator($request);
            if($validate->fails()):
                return self::badRequest(message: "Not Ok", error: $validate->errors());
            endif;
            $role=Role::create($validate->validated());
            return self::successResponse(message:"OK",data:$role->attributesToArray());
        }catch (\Exception $e){
            return self::badRequest(message: "Not Ok" , error: $e->getMessage());
        }
    }

    public function update(Request $request,Role $role)
    {
        try {
            $validate=$this->updateValidator($request,$role);

            if($validate->fails()):
                return self::badRequest(message:"Not Ok", error: $validate->errors());
            endif;
            $role->update($validate->validated());
            return self::successResponse(message:"OK",data:$role->attributesToArray());
        }catch (\Exception $e){
            return self::badRequest(message: "Not Ok" , error: $e->getMessage());
        }
    }

    public function storeValidator($request)
    {
        return Validator::make($request->all(),[
            "name"=>"required|unique:roles,name",
        ]);
    }
    public function updateValidator($request,$role)
    {
        return Validator::make($request->all(),[
            "name"=>["required",Rule::unique('roles','name')->ignore($role->id)],
        ]);
    }
}
