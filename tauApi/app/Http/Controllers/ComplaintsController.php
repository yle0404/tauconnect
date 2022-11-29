<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use Laravel\Sanctum;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\User;
use App\Models\BadWord;
class ComplaintsController extends Controller
{
    public function getMyComplaints(Request $request){
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
        $token = PersonalAccessToken::findToken($request->bearerToken());
        $id = $token->tokenable->id;

        $complaints = Complaint::where('user_id', $id)->get();
        $response = array();
        foreach($complaints as $complaintItem){
            $id = $complaintItem->id;
            $userId = $complaintItem->user_id;
            $complaint = $complaintItem->complaint;
            $createdAt = $complaintItem->created_at->format('M d, Y h:i A');
            $updatedAt = $complaintItem->updated_at->format('M d, Y h:i A');
            $status = $complaintItem->status;
            $category = $complaintItem->category;
            $user = User::where('id', $userId)->first();

            $response[] = [
                'id' => $id,
                'user_id' => $userId,
                'complaint' => filterText($complaint),
                'category' => $category,
                'status' => $status,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
                'user' => $user
            ];
        }

        return response($response, 200);
    }

    public function submitComplaint(Request $request){
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
            'complaint' => 'required',
            'category' => 'required'
        ]);

        $token = PersonalAccessToken::findToken($request->bearerToken());
        $id = $token->tokenable->id;

        $complaint = Complaint::create([
            'user_id' => $id,
            'complaint' => htmlspecialchars($request['complaint']),
            'status' => 'PENDING',
            'category' => $request['category']
        ]);

        $response = [
            'id' => $complaint->id,
            'user_id' => $complaint->user_id,
            'complaint' => filterText($complaint->complaint),
            'category' => $complaint->category,
            'status' => $complaint->status,
            'created_at' => $complaint->created_at->format('M d, Y h:i A'),
            'updated_at' => $complaint->updated_at->format('M d, Y h:i A'),
        ];

        return response($response, 200);
    }
}
