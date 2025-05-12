<?php

namespace App\Http\Controllers\Main;

use App\Classes\Permission\AssignPermissionToModel;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Team;
use App\Models\User;
use App\Policies\Permissions\CheckPermission;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = CheckPermission::check('view', "Product", null, auth()->user());
        if (empty($result)):
            return self::forbidden('not permission');
        endif;
        $products = Product::query()->whereIn('id', $result)->get()->toArray();
        return self::successResponse(message: "ok", data: $products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $result = CheckPermission::check('edit', "Product", null, auth()->user(), 'create');
            if (empty($result)):
                return self::forbidden('not permission');
            endif;
            $validator = $this->storeValidator($request);
            if ($validator->fails()):
                return self::badRequest('not Ok', error: $validator->errors());
            endif;
            $product = Product::query()->create($validator->validated());
            $permission=AssignPermissionToModel::assignPermission($product->title,"Product",$product->id);
            $team=Team::query()->find($result[0]);
            $team->permissions()->attach($permission);
            return self::successResponse(message: "ok", data: $product->toArray());
        } catch (\Exception $exception) {
            return self::serverError($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $result = CheckPermission::check('view', "Product", [$product->id], auth()->user());
        if (empty($result)):
            return self::forbidden('not permission');
        endif;
        return self::successResponse(message: "ok", data: $product->attributesToArray());
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        try {
            $result = CheckPermission::check('edit', "Product", [$product->id], auth()->user(), 'update');
            if (empty($result)):
                return self::forbidden('not permission');
            endif;
            $validator = $this->updateValidator($request,$product);
            if ($validator->fails()):
                return self::badRequest('not Ok', error: $validator->errors());
            endif;
            $product->update($validator->validated());
            return self::successResponse(message: "ok", data: $product->attributesToArray());
        } catch (\Exception $exception) {
            return self::serverError($exception->getMessage());
        }

    }
    /**
     * Remove the specified resource from storage.
     */

    public function storeValidator($request)
    {
        return Validator::make($request->all(), [
            "title" => ["required", "string", "max:255"],
            "slug" => ["nullable", "string", "max:255", "unique:products,slug"],
            "price" => ["nullable", "numeric", "min:0"],
        ]);
    }

    public function updateValidator($request, $product)
    {
        return Validator::make($request->all(), [
            "title" => ["required", "string", "max:255"],
            "slug" => ["nullable", "string", "max:255", Rule::unique('products', 'slug')->ignore($product->id)],
            "price" => ["nullable", "numeric", "min:0"],
        ]);
    }

    public function destroy(string $id)
    {
        //
    }
}
