<?php

use Illuminate\Database\Seeder;

class FriendsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users  = \App\Models\User::all();
        $users->each(function ($user) use ($users) {
            $users = $users->except(['id' => $user->id])->shuffle();
            $users = $users->take(random_int(0,count($users)));
            foreach ($users as $friend) {
                $user->addToFriends($friend);
            }
        });
    }
}
