<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $friends_id = auth()->user()->requestedFriends->pluck('id')
            ->push(auth()->user()->id);

        return response(User::filter($request->all())->whereNotIn('id',$friends_id)->paginate(User::USERS_PAGINATED_COUNT));
    }

    public function store(Request $request)
    {
        //
    }

    public function show()
    {
        return response(auth()->user());
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
