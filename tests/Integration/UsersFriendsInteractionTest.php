<?php

namespace Tests\Integration;

use App\Models\Friend;
use App\Models\User;
use App\Notifications\NewFriendRequest;
use Illuminate\Support\Facades\Notification;
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
    public function user_can_fetch_their_friends()
    {
        $user2 = factory(User::class)->create();
        $user2->sendFriendshipRequest($this->user);
        $this->user->addToFriends($user2);
        $this->assertCount(1, $this->user->fresh()->friends()->get());
        $result = $this->json('get', route('api.friends.get'))->assertStatus(200);
        $this->assertCount(1, $result->json());
    }

    /**
     * @test
     */
    public function user_can_add_to_friends_another_user()
    {
        Notification::fake();
        $user2 = factory(User::class)->create();
        $this->post(route('api.friends.post'), [
            'friend_id' => $user2->id
        ])->assertStatus(200);
        $this->assertEquals($user2->name, $this->user->requestedFriends->first()->name);

        Notification::assertSentTo(
            [$user2], NewFriendRequest::class
        );
    }

    /**
     * @test
     */
    public function user_can_fetch_all_users()
    {
        $this->get(route('api.users.get'))->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_can_confirm_friendship()
    {
        $requested_friend = factory(User::class)->create();
        $requested_friend->sendFriendshipRequest($this->user);

        $this->assertCount(0, $this->user->friends);

        $this->post(route('api.friends.confirm'), [
            'friend_id' => $requested_friend->id
        ])->assertStatus(200);

        $this->assertCount(1, $this->user->fresh()->friends);
    }
}
