<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Notifications\NewFriendRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FriendController extends Controller
{
    public function index()
    {
        return response(auth()->user()->friends);
    }

    public function store()
    {
        $user = User::findOrFail(request('friend_id'));
        auth()->user()->sendFriendshipRequest($user);

        $user->notify((new NewFriendRequest(
            auth()->user()
        ))->onQueue('NewFriendRequest'));
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {
        //
    }

    public function confirm()
    {
        $user = User::findOrFail(request('friend_id'));
        try {
            auth()->user()->addToFriends($user);
        }
        catch (\Illuminate\Database\QueryException $e) {
            abort(422, 'You already have this user as friend!');
        }

        return response($user);
    }
}
