<?php
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        session_start();
        include('connection.php');
        $con = connect();

        if(isset($_POST) && isset($_SESSION['admin_id'])){
            $postId = $_POST['post_id'];
            $comments = array();
            $query = "SELECT * FROM posts WHERE id = '$postId'";
            $post = $con->query($query) or die($con->error);
            $postRow = $post->fetch_assoc();

            $userId = $postRow['user_id'];
            $query = "SELECT * FROM users WHERE id = '$userId'";
            $user = $con->query($query) or die($con->error);
            $userRow = $user->fetch_assoc();
            $profilePicture = $userRow['profile_picture'];
            $name = $userRow['name'];
            $date = date_format(date_create($postRow['created_at']), "M d, Y h:i A");
            $description = $postRow['description'];

            $query = "SELECT * FROM comments WHERE post_id = '$postId'";
            $comment = $con->query($query) or die($con->error);

            while($commentRow = $comment->fetch_assoc()){
                $commenterId = $commentRow['user_id'];
                $query = "SELECT * FROM users WHERE id = '$commenterId'";
                $commenter = $con->query($query) or die($con->error);
                $commenterRow = $commenter->fetch_assoc();
                $commenterName = $commenterRow['name'];
                $commentDetails = $commentRow['comment'];
                $comments[] = array(
                    'name' => $commenterName,
                    'comment' => $commentDetails
                );
            }




            $response = array(
                'profile_picture' => $profilePicture,
                'name' => $name,
                'date' => $date,
                'description' => $description,
                'comments' => $comments
            );

            echo json_encode($response);
        }else{
            echo 'unauthorized';
        }
    }else{
        echo header('HTTP/1.1 403 Forbidden');
    }
?>