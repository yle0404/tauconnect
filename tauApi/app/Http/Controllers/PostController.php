<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\BadWord;
use Laravel\Sanctum;
use Laravel\Sanctum\PersonalAccessToken;
class PostController extends Controller
{
    public function writePost(Request $request){
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
            'description' => 'required',
        ]);

        $token = PersonalAccessToken::findToken($request->bearerToken());
        $id = $token->tokenable->id;

        $user = User::where('id', $id)->first();

        $post = Post::create([
            'user_id' => $id,
            'title' => 'Title',
            'description' => htmlspecialchars($request['description']),
            'comments' => 0,
        ]);

        return response([
            'post_id' => $post->id,
            'name' => $user->name,
            'profile_picture' => $user->profile_picture,
            'date' => $post->created_at->format('M d, Y h:i A'),
            'description' => filterText($post->description),
            'comments' => array()
        ], 200);
    }
}
