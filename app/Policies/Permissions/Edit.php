<?php

namespace App\Policies\Permissions;

use App\Models\User;
use Spatie\Permission\Models\Role;
use function PHPUnit\Framework\isEmpty;

class Edit
{
    public $model, $model_ids, $user, $operation_type;

    /**
     * Create a new policy instance.
     */
    public function __construct($model, $model_ids, $user, $operation_type)
    {
        $this->model = $model;
        $this->model_ids = $model_ids;
        $this->user = User::query()->find($user->id);
        $this->operation_type = $operation_type;
    }

    public function response()
    {
        $permissions = [];
        $model = "App\\Models\\" . $this->model;
        if (class_exists($model)):
            $teams = $this->user->teams;
            foreach ($teams as $team):
                if ($this->user->hasAnyRole(Role::query()
                    ->where('team_id', $team->id)
                    ->where(function ($query) {
                        $query->where('name', 'admin')
                            ->orWhere('name', 'editor');
                    })
                    ->get())):
                    $permissions[strval($team->id)] = $team->getAllPermissions()->where('model',  $model)->pluck('model_id')->toArray();
                endif;
            endforeach;
            $result = [];
            if ($this->operation_type == "create"):
                foreach ($permissions as $key => $permission):
                    if (!empty($permissions)):
                        $result[] = $key;
                    endif;
                endforeach;
            elseif ($this->operation_type == "update"):
                foreach ($permissions as $key => $permission):
                    if (array_intersect($permission, $this->model_ids)):
                        $result[] = $key;
                    endif;
                endforeach;
            endif;
          return  $this->user->hasRole("Super Admin") ?  ["0"=>1] :  $result;
        endif;


    }
}
