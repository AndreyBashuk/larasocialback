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

        $users = factory(\App\Models\User::class,5)->create();
        $chats = factory(\App\Models\Chat::class,5)->create();

        $chats->each(function ($chat) use ($users) {
            $random_number = random_int(0,count($users));
            for ($i = 0; $i< $random_number; $i++) {
                $users[$i]->chats()->attach($chat->id);
                factory(\App\Models\Message::class, random_int(0,5))->create([
                    'user_id' => $users[$i]->id,
                    'chat_id' => $users[$i]->chats[random_int(0,count($users[$i]->chats)-1)]->id
                ]);
            }
        });

    }
}
