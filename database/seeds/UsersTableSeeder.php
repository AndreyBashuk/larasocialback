<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\User::class, 5)->create()->each(function ($user) {
            $user->chats()
                ->save(factory(\App\Models\Chat::class)
                ->create());
            factory(\App\Models\Message::class, random_int(0,10))->create([
               'user_id' => $user->id,
               'chat_id' => $user->chats->first()->id
            ]);
        });
    }
}
