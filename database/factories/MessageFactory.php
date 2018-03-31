<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Message::class, function (Faker $faker) {
   return [
       'user_id' => function () {
            return \App\Models\User::find(1)->id || factory(\App\Models\User::class)->create()->id;
       },
       'chat_id' => function () {
            return factory(\App\Models\Chat::class)->create()->id;
       },
       'message' => $faker->sentence
   ];
});