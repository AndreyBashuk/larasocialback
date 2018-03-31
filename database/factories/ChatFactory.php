<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
*/


$factory->define(\App\Models\Chat::class, function (Faker $faker) {
   return [
       'is_conversation' => $faker->randomElement([true,false])
   ];
});