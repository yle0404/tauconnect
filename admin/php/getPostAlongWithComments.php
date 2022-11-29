<?php
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        session_start();
        include('connection.php');
        include('filter.php');
        $con = connect();

        if(!isset($_SESSION['admin_id'])){
            echo 'index.html';
        }else{
            $postId = $_POST['post_id'];

            $query = "SELECT * FROM comments WHERE post_id = '$postId' ORDER BY created_at DESC LIMIT 3";
            $post = $con->query($query) or die($con->error);
            $posts = array();
            while($row = $post->fetch_assoc()){
                $posts[] = $row;
            }
            $response = array();
            foreach($posts as $commentItem){
                $userId = $commentItem['user_id'];
                $query = "SELECT * FROM users WHERE id = '$userId'";
                $user = $con->query($query) or die($con->error);
                $row = $user->fetch_assoc();
                $name = $row['name'];
                $response[] = array(
                    'name' => $name,
                    'comment' => filter($commentItem['comment'], $con)
                );
            }

            echo json_encode($response);
        }
    }else{
        echo header('HTTP/1.0 403 Forbidden');
    }
?>