<?php

namespace Tests\Feature;

use App\Events\NewMessage;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersChatsInteractionTest extends TestCase
{
    use RefreshDatabase, WithFaker;
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

        $this->json('post', route('api.chat.post'), [
            'is_conversation' => true,
            'name' => 'New chat'
        ])->assertStatus(200);

        $this->assertCount(1, $this->user->chats);
    }


    /**
     * @test
     */
    public function user_cannot_create_chat_if_another_user_is_not_user_friend()
    {
        $userFriend = factory(User::class)->create();
        $this->json('post', route('api.chat.post', [
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
        $chat = $this->json('post', route('api.chat.post', [
            'is_conversation' => false,
            'friend_id' => $userFriend->id,
            'name' => 'New chat'
        ]))->assertStatus(200);
        $this->assertCount(1, $userFriend->chats);
        $this->assertCount(1, $this->user->chats);
        $this->assertEquals($this->user->chats()->first()->pivot->user_id, $this->user->id);
        $this->assertEquals($userFriend->chats()->first()->pivot->user_id, $userFriend->id);
    }

    /**
     * @test
     */
    public function user_can_store_the_message_if_user_exists_in_chat()
    {
        Event::fake();
        $this->user->chats()->create([
            'is_conversation' => true,
            'name' => 'test_chat'
        ]);

        $this->assertCount(0, $this->user->messages);
        $this->post(route('api.message.post'), [
            'chat_id' => $this->user->chats->first()->id,
            'message' => 'New message'
        ])->assertStatus(200);

        $this->assertCount(1, $this->user->fresh()->messages);

        Event::assertDispatched(NewMessage::class);

    }

    /**
     * @test
     */
    public function user_cannot_store_message_if_user_is_not_exists_in_chat()
    {
        Event::fake();
        $someUser = factory(User::class)->create();
        $someUser->chats()->create([
            'is_conversation' => true,
            'name' => 'test_chat'
        ]);
        $this->assertCount(0, $this->user->messages);
        $this->post(route('api.message.post'), [
            'chat_id' => $someUser->chats->first()->id,
            'message' => 'New message'
        ])->assertStatus(403);

        $this->assertCount(0, $this->user->fresh()->messages);

        Event::assertNotDispatched(NewMessage::class);
    }

    /**
     * @test
     */
    public function user_can_fetch_message_by_chat_id_if_user_exists_in_chat()
    {
        $COUNT = 10;
        $this->user->chats()->create([
            'is_conversation' => true,
            'name' => 'test_chat'
        ]);
        $messages = factory(Message::class, $COUNT)->create([
            'chat_id' => $this->user->chats->first()->id,
            'user_id' => $this->user->id
        ]);

        $result = $this->json('get', route('api.message.get', ['chat' => $this->user->chats->first()->id]))->assertStatus(200);
        $this->assertCount($COUNT, $result->json()['data']);
    }

    /**
     * @test
     */
    public function user_cannot_fetch_message_by_chat_id_if_user_is_not_exists_in_chat()
    {
        $someUser = factory(User::class)->create();
        $someUser->chats()->create([
            'is_conversation' => true,
            'name' => 'test_chat'
        ]);

        $this->json('get', route('api.message.get', ['chat' => $someUser->chats->first()->id]))
            ->assertStatus(403);
    }

    public function user_can_delete_message_by_chat_id_if_user_is_exists_in_chat()
    {
        $COUNT = 10;
        $this->user->chats()->create([
            'is_conversation' => true,
            'name' => 'test_chat'
        ]);
        $messages = factory(Message::class, $COUNT)->create([
            'chat_id' => $this->user->chats->first()->id,
            'user_id' => $this->user->id
        ]);

        $this->assertCount($COUNT, $this->user->messages);
    }
}
