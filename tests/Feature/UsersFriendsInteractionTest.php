<?php

namespace Tests\Feature;

use App\Models\Friend;
use App\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class UsersFriendsInteractionTest
 * @package Tests\Feature
 */
class UsersFriendsInteractionTest extends TestCase
{

    use RefreshDatabase;

    protected $user;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        Passport::actingAs(
            $this->user
        );
    }

    /**
     * @test
     */
    public function user_can_add_friendship_request_to_another_user()
    {
        $requested_friend = factory(User::class)->create();
        $this->user->sendFriendshipRequest($requested_friend);
        $this->assertCount(1,$this->user->requestedFriends);
        $this->assertTrue($this->user->requestedFriends[0]->pivot->status === User::FRIENDSHIP_STATUS['REQUEST']);
    }

    /**
     * @test
     */
    public function user_can_accept_another_user_friendship_request()
    {
        $requested_friend = factory(User::class)->create();
        $this->user->sendFriendshipRequest($requested_friend);
        $requested_friend->addToFriends($this->user);
        $this->assertEquals($this->user->requestedFriends->pluck('id')->first(), $requested_friend->id);
        $this->assertEquals($requested_friend->requestedFriends->pluck('id')->first(), $this->user->id);
        $this->assertEquals($this->user->requestedFriends->first()->pivot->status, User::FRIENDSHIP_STATUS['ACCEPT']);
        $this->assertEquals($requested_friend->requestedFriends->first()->pivot->status , User::FRIENDSHIP_STATUS['ACCEPT']);
    }

    /**
     * @test
     */
    public function user_cannot_be_friend_before_he_dont_accept_friendship_request() {
        $requested_friend = factory(User::class)->create();
        $this->user->sendFriendshipRequest($requested_friend);
        $this->assertCount(0,$this->user->friends);
        $requested_friend->addToFriends($this->user);
        $this->assertCount(1,$this->user->fresh()->friends);
    }

    /**
     * @test
     */
    public function user_can_delete_another_user_from_friends_and_another_user_still_has_old_user()
    {
        $requested_friend = factory(User::class)->create();
        $this->user->sendFriendshipRequest($requested_friend);
        $requested_friend->addToFriends($this->user);
        $requested_friend->removeFromFriends($this->user);
        $this->assertCount(0, $requested_friend->fresh()->requestedFriends->pluck('id'));
        $this->assertEquals($this->user->requestedFriends->pluck('id')->first(), $requested_friend->id);
    }
}
