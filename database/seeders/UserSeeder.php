<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $isTestUserExist = User::where('email', 'test@example.com')->count();
        if (!$isTestUserExist) {
            User::create([
                'name' => 'test',
                'email' => 'test@example.com',
                'password' => Hash::make('testPassword'),
            ]);
        }
        User::factory()->count(10)->create();
    }
}
