<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Laravue\Models\BasicStatus;

class BasicStatusTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
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
        
    }
}
