<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FriendController extends Controller
{
    public function index()
    {
        return response (auth()->user()->friends);
    }

    public function store(Request $request)
    {
        $friend = User::findOrFail(request('friend_id'));
        auth()->user()->addToFriends($friend);
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
