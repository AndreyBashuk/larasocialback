<?php

namespace App\Http\Controllers\API;

use App\Events\NewMessage;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class MessageController extends Controller
{

    /**
     * @param Chat $chat
     * @return \Illuminate\Http\Response
     */
    public function index(Chat $chat)
    {
        if(Gate::denies('message.view', $chat->id)) {
            abort(403,"Forbidden");
        };

        return response(Message::where([
            'chat_id' => $chat->id
        ])->latest()->with('creator')->simplePaginate(Message::MESSAGE_PAGINATED_COUNT));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Gate::denies('message.create', $request->chat_id)) {
            abort(403,"Forbidden");
        };

        $message = Message::create([
            'user_id' => auth()->user()->id,
            'chat_id' => $request->chat_id,
            'message' => $request->message
        ])->load('creator');

        broadcast(new NewMessage($message))->toOthers();

        return response($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        //
    }
}
