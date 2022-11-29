<?php
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        session_start();
        include('connection.php');
        include('filter.php');
        $con = connect();

        if(!isset($_SESSION['admin_id'])){
            echo 'index.html';
        }else{
            $userId = $_SESSION['admin_id'];
            $query = "SELECT * FROM posts ORDER BY id DESC";
            $post = $con->query($query) or die($con->error);
            $posts = array();

            while($row = $post->fetch_assoc()){
                $posts[] = $row;
            }

            $postsWithName = array();

            foreach($posts as $postItem){
                $posterId = $postItem['user_id'];
                $query = "SELECT * FROM users WHERE id = '$posterId'";
                $user = $con->query($query) or die($con->error);
                $row = $user->fetch_assoc();
                $name = $row['name'];
                $profilePicture = $row['profile_picture'];
                $date = date_create($postItem['created_at']);
                $id = $postItem['id'];
                $postsWithName[] = array(
                    'name' => $name,
                    'description' => filter($postItem['description'], $con),
                    'date' => date_format($date, 'M d, Y h:i A'),
                    'post_id' => $id,
                    'profile_picture' => $profilePicture
                );
            }

            echo json_encode($postsWithName);
        }
    }else{
        echo header('HTTP/1.0 403 Forbidden');
    }
?>