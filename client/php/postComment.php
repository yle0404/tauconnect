<?php

    if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
        session_start();
        include('connection.php');
        include('filter.php');
        $con = connect();
        $today = getCurrentDate();
        if(isset($_SESSION['user_id']) && isset($_POST)){
            
            $userId = $_SESSION['user_id'];
            $comment = htmlspecialchars($_POST['comment']);
            $postId = htmlspecialchars($_POST['post_id']);
    
            $query = "INSERT INTO comments(`user_id`,`post_id`,`comment`,`created_at`,`updated_at`)VALUES('$userId','$postId','$comment','$today','$today')";
            $con->query($query) or die($con->error);

            $query = "SELECT * FROM posts WHERE id = '$postId'";
            $selectPostQuery = $con->query($query) or die($con->error);
            $postRow = $selectPostQuery->fetch_assoc();
            $numberOfComments = $postRow['comments'];
            $numberOfComments++;
            $query = "UPDATE posts SET comments = '$numberOfComments' WHERE id = '$postId'";
            $con->query($query) or die($con->error);
    
            $query = "SELECT * FROM comments WHERE id = LAST_INSERT_ID()";
            $comment = $con->query($query) or die($con->error);
            $commentRow = $comment->fetch_assoc();
            $commentUserId = $commentRow['user_id'];
    
            $query = "SELECT * FROM users WHERE id = '$userId'";
            $user = $con->query($query) or die($con->error);
            $userRow = $user->fetch_assoc();
            $commentName = $userRow['name'];
            $comment = $commentRow['comment'];
            $response = array(
                'name' => $commentName,
                'comment' => filter($comment, $con),
            );
            echo json_encode($response);
        }else{
            echo 'index.html';
        }
    }else{
        echo header('HTTP/1.0 403 Forbidden');
    }
    
?>