<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var User $user */
        $user = User::where('email', 'rjacobsen2009@gmail.com')->first();

        if (!$user) {
            $user = User::create([
                'first_name' => 'Richard',
                'last_name' => 'Jacobsen',
                'email' => 'rjacobsen2009@gmail.com',
                'password' => bcrypt('secret')
            ]);
        }

        $user->roles()->sync(Role::admin()->first()->id);
    }
}
