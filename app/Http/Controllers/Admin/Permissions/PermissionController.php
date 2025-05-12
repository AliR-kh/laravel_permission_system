<?php

namespace App\Http\Controllers\Admin\Permissions;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use phpDocumentor\Reflection\Types\Self_;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {
            $permissions=Permission::all()->select(['id','name'])->all();
            return self::successResponse(message:"OK",data:$permissions);
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
            $permission=Permission::create($validate->validated());

            return self::successResponse(message:"OK",data:$permission->attributesToArray());
        }catch (\Exception $e){
            return self::badRequest(message: "Not Ok" , error: $e->getMessage());
        }
    }

    public function update(Request $request,Permission $permission)
    {
        try {
            $validate=$this->updateValidator($request,$permission);

            if($validate->fails()):
                return self::badRequest(message:"Not Ok", error: $validate->errors());
            endif;
            $permission->update($validate->validated());
            return self::successResponse(message:"OK",data:$permission->attributesToArray());
        }catch (\Exception $e){
            return self::badRequest(message: "Not Ok" , error: $e->getMessage());
        }
    }

    public function storeValidator($request)
    {
        return Validator::make($request->all(),[
            "name"=>"required|unique:permissions,name",
            "model"=>["nullable"],
            "model_id"=>["nullable"],
        ]);
    }
    public function updateValidator($request,$permission)
    {
        return Validator::make($request->all(),[
            "name"=>["required",Rule::unique('permissions','name')->ignore($permission->id)],
            "model"=>["nullable"],
            "model_id"=>["nullable"],
        ]);
    }
}
