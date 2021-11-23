<?php

use Illuminate\Database\Seeder;
use App\Laravue\Models\Permission;

class NotificationChannelPermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::findOrCreate("view notification channels", "api");
        Permission::findOrCreate("manage notification channels", "api");
        Permission::findOrCreate("view menu notification channels");

        $adminRole =  App\Laravue\Models\Role::findByName(App\Laravue\Acl::ROLE_ADMIN);
        $adminRole->givePermissionTo(['view notification channels', 'manage notification channels', "view menu notification channels"]);
    }
}
