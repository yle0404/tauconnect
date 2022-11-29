<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use Laravel\Sanctum;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\User;
use App\Models\BadWord;
class AnnouncementController extends Controller
{

    
    public function getAnnouncements(){
        function filterText($text){
            $filteredWord = "";
                $wordsArray = explode(" ", $text);
                foreach($wordsArray as $word){
                    
                    $badWord = BadWord::where('word', $word)->first();
    
    
                    if($badWord){
                        $filteredWord .= "***** ";
                    }else{
                        $filteredWord .= $word . " ";
                    }
                }
            return $filteredWord;
        }
        $announcements = Announcement::orderBy('id', 'desc')->get();
        $response = array();

        foreach($announcements as $announcementsItem){
            $id = $announcementsItem->id;
            $title = $announcementsItem->title;
            $description = $announcementsItem->description;
            $createdAt = $announcementsItem->created_at->format('M d, Y h:i A');
            $updatedAt = $announcementsItem->updated_at->format('M d, Y h:i A');
            $userId = $announcementsItem->user_id;
            $user = User::where('id', $userId)->first();
            $response[] = [
                'id' => $id,
                'title' => $title,
                'description' => filterText($description),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
                'user_id' => $userId,
                'user' => $user,
            ];
        }

        return response($response, 200);
    }

    public function postAnnouncement(Request $request){
        function filterText($text){
            $filteredWord = "";
                $wordsArray = explode(" ", $text);
                foreach($wordsArray as $word){
                    
                    $badWord = BadWord::where('word', $word)->first();
    
    
                    if($badWord){
                        $filteredWord .= "***** ";
                    }else{
                        $filteredWord .= $word . " ";
                    }
                }
            return $filteredWord;
        }
        $request->validate([
            'announcement' => 'required',
        ]);

        $token = PersonalAccessToken::findToken($request->bearerToken());
        $id = $token->tokenable->id;

        
        $user = User::where('id', $id)->first();
        $userType = $user->user_type;
        if($userType == "Student"){
            return response([
                'message' => 'unauthorized'
            ], 401);
        }
        $announcement = Announcement::create([
            'title' => 'Title',
            'description' => $request['announcement'],
            'user_id' => $id,
        ]);
        $userId = $announcement->user_id;
        

        $response = [
            'id' => $announcement->id,
            'title' => $announcement->title,
            'description' => filterText($announcement->description),
            'created_at' => $announcement->created_at->format('M d, Y h:i A'),
            'updated_at' => $announcement->updated_at->format('M d, Y h:i A'),
            'user_id' => $announcement->user_id,
            'user' => $user
        ];

        return response($response, 200);
    }
}
