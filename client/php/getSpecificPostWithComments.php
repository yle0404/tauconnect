<?php
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        include('connection.php');
        include('filter.php');
        $con = connect();
        if(isset($_POST)){
            $postId = $_POST['post_id'];
            $comments = array();
            $query = "SELECT * FROM posts WHERE id = '$postId'";

            $post = $con->query($query) or die($con->error);

            $postRow = $post->fetch_assoc();

            $userId = $postRow['user_id'];

            $query = "SELECT * FROM users WHERE id = '$userId'";
            $user = $con->query($query) or die($con->error);
            $userRow = $user->fetch_assoc();
            $name = $userRow['name'];
            $date = date_format(date_create($postRow['created_at']), "M d, Y h:i A");
            $image = $userRow['profile_picture'];
            $description = $postRow['description'];


            $query = "SELECT * FROM comments WHERE post_id = '$postId'";
            $comment = $con->query($query) or die($con->error);

            while($commentRow = $comment->fetch_assoc()){

                $commenterId = $commentRow['user_id'];

                $query = "SELECT * FROM users WHERE id = '$commenterId'";
                $commenter = $con->query($query) or die($con->error);
                $commenterRow = $commenter->fetch_assoc();
                $commenterName = $commenterRow['name'];
                $comments[] = array(
                    'name' => $commenterName,
                    'comment' => filter($commentRow['comment'], $con)
                );
            }
            

            $response = array(
                'name' => $name,
                'date' => $date,
                'profile_picture' => $image,
                'description' => $description,
                'comments' => $comments
            );

            echo json_encode($response);
        }
    }else{
        echo header('HTTP/1.1 403 Forbidden');
    }
?>