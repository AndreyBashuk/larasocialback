<?php

namespace App\Http\Controllers\API;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(Chat::whereHas('users', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->with(['messages' =>  function ($query) {
            $query->limit(Message::MESSAGE_PAGINATED_COUNT)->with('creator');
        }])->with('users')->get());
    }

    /**
     * Store new chat.
     *
     * @param  \Illuminate\Http\Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->only('is_conversation'))
        return response(auth()->user()->chats()->create([
            'is_conversation' => true,
            'name' => $request->name
        ]));

        return response(auth()->user()->chats()->create([
            'is_conversation' => false,
            'name' => $request->name
        ]));
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return void
     */
    public function show( User $user)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Chat $chat
     * @return void
     */
    public function update(Request $request, Chat $chat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chat $chat
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Chat $chat)
    {
        return response()->json($chat->delete());
    }
}
