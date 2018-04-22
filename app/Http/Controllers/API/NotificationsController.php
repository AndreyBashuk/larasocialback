<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationsController extends Controller
{
    public function index()
    {
        return response(auth()->user()->notifications()->simplePaginate(User::USER_NOTIFICATIONS_COUNT));
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function update()
    {
        auth()->user()->unreadNotifications()->whereIn('id',request('notification_ids'))->update(['read_at' => now()]);
    }


    public function destroy($id)
    {
        //
    }
}
