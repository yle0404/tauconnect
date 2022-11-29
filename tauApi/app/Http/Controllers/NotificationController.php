<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Sanctum;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Notification;
class NotificationController extends Controller
{
    public function getNotifications(Request $request){
        $token = PersonalAccessToken::findToken($request->bearerToken());
        $id = $token->tokenable->id;

        $notifications = Notification::where('user_id', $id)->where('read','no')->get();

        foreach($notifications as $notification){
            $notification->update([
                'read' => 'yes'
            ]);
        }

        return response($notifications, 200);
    }
}
