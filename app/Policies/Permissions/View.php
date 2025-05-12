<?php

namespace App\Policies\Permissions;

use App\Models\Team;
use App\Models\User;
use Spatie\Permission\Models\Role;

class View
{
    public $model, $model_ids, $user;

    /**
     * Create a new policy instance.
     */
    public function __construct($model, $model_ids, $user)
    {
        $this->model = $model;
        $this->model_ids = $model_ids;
        $this->user = User::query()->find($user->id);
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
                            ->orWhere('name', 'viewer')
                            ->orWhere('name', 'editor');
                    })
                    ->get())):
                    $permissions = array_unique(array_merge($permissions, $team->getAllPermissions()->where('model', str_replace('\\', '/', $model))->pluck('model_id')->toArray()));
                endif;
            endforeach;
            $userPermissions = $this->user->getAllPermissions()->where('model', str_replace('\\', '/', $model))->pluck('model_id')->toArray();
            $permissions = array_unique(array_merge($permissions, $userPermissions));
            $this->model_ids = is_null($this->model_ids) ? $model::all()->pluck('id')->toArray() : $this->model_ids;
            if ($this->user->hasRole("Super Admin")):
                return $this->model_ids;
            endif;
            return array_intersect($permissions, $this->model_ids);
        endif;


    }
}
