<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class create_super_admin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create_super_admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user=User::query()->firstOrCreate(["phone"=>"09123456789"]);
//        $user=User::query()->firstOrCreate(["phone"=>"09364882128"]);
        $role=Role::findOrCreate("Super Admin");
        $user->assignRole($role);

    }
}
