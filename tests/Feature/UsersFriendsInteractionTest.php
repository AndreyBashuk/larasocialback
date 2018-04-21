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
    public function user_can_add_another_user_to_friends()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $user1->addToFriends($user2);
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
        $user1->removeFromFriends($user2);
        $this->assertCount(0, $user1->fresh()->friends->pluck('id'));
        $this->assertEquals($user2->friends->pluck('id')->first(), $user1->id);
    }

    /**
     * @test
     */
    public function user_can_fetch_their_friends()
    {
        $user2 = factory(User::class)->create();
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
        $user2 = factory(User::class)->create();
        $this->post(route('api.friends.post'), [
            'friend_id' => $user2->id
        ])->assertStatus(200);
        $this->assertEquals($user2->name, $this->user->friends->first()->name);
    }

    /**
     * @test
     */
    public function user_can_fetch_all_users()
    {
        $this->get(route('api.users.get'))->assertStatus(200);
    }
}
