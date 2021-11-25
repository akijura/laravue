<?php

use App\Laravue\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Laravue\Models\Role;
use App\Laravue\Models\BasicStatus;
use App\Laravue\Models\ProjectLevel;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'Akbarjon Juraev',
            'email' => 'admin@hamkorbank.uz',
            'password' => Hash::make('akki9696'),
        ]);
        $moderator = User::create([
            'name' => 'Moderator',
            'email' => 'moderator@laravue.dev',
            'password' => Hash::make('laravue'),
        ]);

        $user = User::create([
            'name' => 'User',
            'email' => 'user@laravue.dev',
            'password' => Hash::make('laravue'),
        ]);
        $todo = BasicStatus::create([
            'basic_status_name' => 'todo',
            'basic_status_description' => 'To do',
            'author_id' => 1,
        ]);
        $working = BasicStatus::create([
            'basic_status_name' => 'working',
            'basic_status_description' => 'In prossecc',
            'author_id' => 1,
        ]);
        $done = BasicStatus::create([
            'basic_status_name' => 'done',
            'basic_status_description' => 'Done',
            'author_id' => 1,
        ]);
        $cancelled = BasicStatus::create([
            'basic_status_name' => 'cancelled',
            'basic_status_description' => 'Cancelled',
            'author_id' => 1,
        ]);
        $low = ProjectLevel::create([
            'name' => 'low',
            'description' => 'Project level is low',
        ]);
        $medium = ProjectLevel::create([
            'name' => 'medium',
            'description' => 'Project level is medium',
        ]);
        $high = ProjectLevel::create([
            'name' => 'high',
            'description' => 'Project level is high',
        ]);
        $adminRole = Role::findByName(\App\Laravue\Acl::ROLE_ADMIN);
        $moderatorRole = Role::findByName(\App\Laravue\Acl::ROLE_MODERATOR);
        $admin->syncRoles($adminRole);
        $moderator->syncRoles($moderatorRole);
      
    }
}
