<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersChatsInteractionTest extends TestCase
{
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
    public function user_can_create_chat_as_conversation()
    {

        $this->json('post', route('api.create_chat'), [
            'is_conversation' => true,
            'name' => 'New chat'
        ])->assertStatus(200);

        $this->assertCount(1, $this->user->chats);
    }


    /**
     * @test
     */
    public function user_cannot_create_chat_if_another_user_is_not_user_friend() {
        $userFriend = factory(User::class)->create();
        $this->json('post', route('api.create_chat', [
            'is_conversation' => false,
            'friend_id' => $userFriend->id,
            'name' => 'New chat'
        ]))->assertStatus(403);
    }

    /**
     * @test
     */
    public function user_can_create_chat_with_friend()
    {

        $userFriend = factory(User::class)->create();
        $this->user->friends()->attach($userFriend->id);
        $chat = $this->json('post', route('api.create_chat', [
            'is_conversation' => false,
            'friend_id' => $userFriend->id,
            'name' => 'New chat'
        ]))->assertStatus(200);
        $this->assertCount(1, $userFriend->chats);
        $this->assertCount(1, $this->user->chats);
        $this->assertEquals($this->user->chats()->first()->pivot->user_id, $this->user->id);
        $this->assertEquals($userFriend->chats()->first()->pivot->user_id, $userFriend->id);
    }
}
