<?php

use App\Models\Balance;
use App\Models\User;
use Illuminate\Database\Seeder;

class BalancesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (User::all() as $user) {
            factory(Balance::class)->create(['date' => \Carbon\Carbon::today(), 'user_id' => $user->id]);
            factory(Balance::class, 2)->create(['date' => \Carbon\Carbon::yesterday(), 'user_id' => $user->id]);
            factory(Balance::class, 150)->create(['user_id' => $user->id]);
        }
    }
}
