<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Silber\Bouncer\BouncerFacade as Bouncer;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com.br',
            'password' => 'admin123',
        ]);

        Bouncer::allow($user)->everything();
    }
}
