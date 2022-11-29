<?php
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        include('connection.php');
        $con = connect();

        $query = "SELECT * FROM posts";
        $post = $con->query($query) or die($con->error);
        $numberOfPosts = 0;
        $numberOfCommentingUsers = 0;
        $previousUserId = 0;
        $numberOfUsers = 0;
        while($postRow = $post->fetch_assoc()){
            $numberOfPosts++;
        }
        $query = "SELECT * FROM comments ORDER BY user_id ASC";
        $comment = $con->query($query) or die($con->error);

        while($commentRow = $comment->fetch_assoc()){
            $commentUserId = $commentRow['user_id'];
            $currentUserId = $commentUserId;

            if($currentUserId != $previousUserId){
                $numberOfCommentingUsers++;
                $previousUserId = $currentUserId;
            }
        }

        $query = "SELECT * FROM users";
        $users = $con->query($query) or die($con->error);
        while($userRow = $users->fetch_assoc()){
            $numberOfUsers++;
        }
        $numberOfComplaints = 0;
        $query = "SELECT * FROM complaints";
        $complaint = $con->query($query) or die($con->error);
        while($complaintRow = $complaint->fetch_assoc()){
            $numberOfComplaints++;
        }

        $nonparticipating = $numberOfUsers - $numberOfCommentingUsers;

        echo json_encode(
            array(
                'posts' => $numberOfPosts,
                'filed_complaints' => $numberOfComplaints,
                'users' => array(
                    'participating' => $numberOfCommentingUsers,
                    'not_participating' => $nonparticipating
                )
            )
        );
    }else{
        echo header('HTTP/1.1 403 Forbidden');
    }
?>