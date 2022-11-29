<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Sanctum;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Message;
use App\Models\User;
use App\Models\Notification;
class MessageController extends Controller
{
    //

    public function getConversation(Request $request){
        $request->validate([
            'user_id' => 'required'
        ]);

        $token = PersonalAccessToken::findToken($request->bearerToken());
        $id = $token->tokenable->id;

        $messages = Message::where('sender_id', $id)->where('receiver_id', $request['user_id'])->orWhere('sender_id', $request['user_id'])->where('receiver_id', $id)->get();

        $response = array();
        foreach($messages as $messageItem){
            $messageId = $messageItem->id;
            $senderId = $messageItem->sender_id;
            $receiverId = $messageItem->receiver_id;
            $message = $messageItem->message;
            $read = $messageItem->read;
            $createdAt = $messageItem->created_at->format('M d, Y h:i A');
            $updatedAt = $messageItem->updated_at->format('M d, Y h:i A');

            if($senderId == $id){
                $mine = true;
            }else{
                $mine = false;
            }

            $response[] = [
                'id' => $messageId,
                'sender_id' => $senderId,
                'receiver_id' => $receiverId,
                'message' => $message,
                'read' => $read,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
                'mine' => $mine
            ];
        }

        return response($response, 200);
    }

    public function getUsers(Request $request){
        $token = PersonalAccessToken::findToken($request->bearerToken());
        $id = $token->tokenable->id;

        $users = User::where('id','<>',$id)->where('user_type','<>','admin')->get();
        $usersToMessage = array();
        foreach($users as $user){
            $userId = $user->id;
            $message = Message::where('sender_id', $userId)->where('receiver_id', $id)->orWhere('receiver_id', $userId)->where('sender_id', $id)->first();

            if($message){
                $usersToMessage[] = $user;
            }
        }

        return response($usersToMessage, 200);
    }

    public function sendMessage(Request $request){
        $request->validate([
            'message' => 'required',
            'receiver_id' => 'required'
        ]);


        $token = PersonalAccessToken::findToken($request->bearerToken());
        $id = $token->tokenable->id;
        $sender = User::where('id', $id)->first();
        $senderName = $sender->name;
        $message = Message::create([
            'sender_id' => $id,
            'receiver_id' => $request['receiver_id'],
            'message' => htmlspecialchars($request['message']),
            'read' => 'no',
        ]);
        
        $notificationMessage = $senderName . " sent you a message.";
        $notification = Notification::create([
            'title' => 'New Message',
            'message' => $notificationMessage,
            'user_id' => $request['receiver_id'],
            'read' => 'no'
        ]);

        $mine = true;

        return response([
            'id' => $message->id,
            'sender_id' => $message->sender_id,
            'receiver_id' => $message->receiver_id,
            'message' => $message->message,
            'read' => $message->read,
            'mine' => $mine
        ], 200);
    }

    public function sendNewMessage(Request $request){
        $request->validate([
            'message' => 'required',
            'receiver_id' => 'required'
        ]);


        $token = PersonalAccessToken::findToken($request->bearerToken());
        $id = $token->tokenable->id;

        $sender = User::where('id', $id)->first();
        $senderName = $sender->name;

        $notificationMessage = $senderName . " sent you a message.";
        $notification = Notification::create([
            'title' => 'New Message',
            'message' => $notificationMessage,
            'user_id' => $request['receiver_id'],
            'read' => 'no'
        ]);

        $message = Message::create([
            'sender_id' => $id,
            'receiver_id' => $request['receiver_id'],
            'message' => htmlspecialchars($request['message']),
            'read' => 'no',
        ]);

        $receiverId = $message->receiver_id;

        $receiver = User::where('id', $receiverId)->first();

        return response($receiver, 200);
    }

    public function getUsersToMessage(Request $request){
        $token = PersonalAccessToken::findToken($request->bearerToken());
        $id = $token->tokenable->id;
        $users = User::where('user_type','<>','admin')->where('id','<>',$id)->get();
        $usersToMessage = array();
        foreach($users as $user){
            $userId = $user->id;

            $message = Message::where('sender_id', $userId)->where('receiver_id', $id)->orWhere('receiver_id', $userId)->where('sender_id', $id)->first();

            if(!$message){
                $usersToMessage[] = $user;
            }
        }

        return response($usersToMessage, 200);
    }
}
