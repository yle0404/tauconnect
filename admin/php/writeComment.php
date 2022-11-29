<?php
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        session_start();
        include('connection.php');
        $con = connect();
        $date = getCurrentDate();

        if(isset($_POST) && $_SESSION['admin_id']){
            $adminId = $_SESSION['admin_id'];
            $postId = htmlspecialchars($_POST['post_id']);
            $comment = htmlspecialchars($_POST['comment']);
            $query = "INSERT INTO comments(`user_id`,`post_id`,`comment`,`created_at`,`updated_at`)VALUES('$adminId','$postId','$comment','$date','$date')";
            $con->query($query) or die($con->error);


            $query = "SELECT * FROM posts WHERE id = '$postId'";
            $post = $con->query($query) or die($con->error);

            $postRow = $post->fetch_assoc();
            $postComments = $postRow['comments'];

            $postComments++;

            $query = "UPDATE posts SET comments = '$postComments' WHERE id = '$postId'";
            $con->query($query) or die($con->error);

            $query = "SELECT * FROM comments WHERE id = LAST_INSERT_ID()";
            $comment = $con->query($query) or die($con->error);
            $row = $comment->fetch_assoc();
            $userId = $row['user_id'];

            $query = "SELECT * FROM users WHERE id = '$userId'";
            $user = $con->query($query) or die($con->error);

            $userRow = $user->fetch_assoc();

            $name = $userRow['name'];
            $comment = $row['comment'];

            $response = array(
                'name' => $name,
                'comment' => $comment
            );
            echo json_encode($response);
        }else{
            echo 'index.html';
        }
    }else{
        echo header('HTTP/1.0 403 Forbidden');
    }
?>