<?php


    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        session_start();
        include('connection.php');
        include('filter.php');
        $con = connect();
    
        if(!isset($_SESSION['user_id'])){
            echo 'index.html';
        }else if(isset($_SESSION['user_id']) && isset($_GET)){
            $query = "SELECT * FROM posts ORDER BY created_at DESC";
            $post = $con->query($query) or die($con->error);
            $posts = array();
            while($row = $post->fetch_assoc()){
                $posts[] = $row;
            }
            $response = array();
            foreach($posts as $postItem){
                $userId = $postItem['user_id'];
                $query = "SELECT * FROM users WHERE id = '$userId'";
                $user = $con->query($query) or die($con->error);
                $userRow = $user->fetch_assoc();
                $name = $userRow['name'];
                $date = date_create($postItem['created_at']);
                $date = date_format($date, 'M d, Y h:i A');
                $description = $postItem['description'];
                $profilePicture = $userRow['profile_picture'];
                $postId = $postItem['id'];
    
                $comments = array();
                //get latest comment
                $query = "SELECT * FROM comments WHERE post_id = '$postId' ORDER BY created_at DESC LIMIT 3";
                $comment = $con->query($query) or die($con->error);
                while($commentRow = $comment->fetch_assoc()){
                    $commentComment = $commentRow['comment'];
                    $commentUserId = $commentRow['user_id'];
                    $query = "SELECT * FROM users WHERE id = '$commentUserId'";
                    $commentUser = $con->query($query) or die($con->error);
                    $commentUserRow = $commentUser->fetch_assoc();
                    $commentUserName = $commentUserRow['name'];
                    $comments[] = array(
                        'name' => $commentUserName,
                        'comment' => filter($commentComment, $con)
                    );
                }
                $response[] = array(
                    'profile_picture' => $profilePicture,
                    'name' => $name,
                    'date' => $date,
                    'description' => filter($description, $con),
                    'post_id' => $postId,
                    'comments' => $comments
                );
                
            }

            $query = "SELECT * FROM posts ORDER BY comments DESC LIMIT 3";
            $trending = $con->query($query) or die($con->error);
            $trendingTopics = array();
            while($trendingRow = $trending->fetch_assoc()){
                $trendingTopics[] = array(
                    'post_id' => $trendingRow['id'],
                    'description' => filter($trendingRow['description'], $con),
                );
            }
            echo json_encode(array(
                'posts' => $response,
                'trending_topics' => $trendingTopics
            ));
        }
    }else{
        echo header('HTTP/1.0 403 Forbidden');
    }
    
?>