<?php

namespace Tests\Feature;

use App\Models\Friend;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersFriendsInteractionTest extends TestCase
{
    /**
     * A basic test example.
     * @test
     * @return void
     */
    public function user_can_add_another_user_to_friends()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $user1->friends()->attach($user2->id);
        $user2->friends()->attach($user1->id);
        $this->assertEquals($user1->friends->pluck('id')->first(), $user2->id);
        $this->assertEquals($user2->friends->pluck('id')->first(), $user1->id);
    }

    /**
     * @test
     */
    public function user_can_delete_another_user_from_friends_and_another_user_still_has_old_user()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $user1->addToFriends($user2);
        $this->assertEquals($user1->friends->pluck('id')->first(), $user2->id);
        $this->assertEquals($user2->friends->pluck('id')->first(), $user1->id);
        $user1->friends()->detach($user2->id);
        $this->assertCount(0,$user1->fresh()->friends->pluck('id'));
        $this->assertEquals($user2->friends->pluck('id')->first(), $user1->id);
    }
}
