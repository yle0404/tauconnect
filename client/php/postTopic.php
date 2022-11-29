<?php
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        session_start();
        include('connection.php');
        include('filter.php');
        $con = connect();
        $today = getCurrentDate();
        if(isset($_POST) && isset($_SESSION['user_id'])){
            $userId = $_SESSION['user_id'];
            $post = htmlspecialchars($_POST['topic']);
            $title = "Title";
            $stmt = $con->prepare("INSERT INTO posts(user_id,title,description,created_at,updated_at,comments)VALUES(?,?,?,?,?,?)");
            $numberOfComments = 0;
            $stmt->bind_param("issssi", $userId, $title, $post, $today, $today, $numberOfComments);
            $stmt->execute();
            $query = "SELECT * FROM posts WHERE id = LAST_INSERT_ID()";
            $forumPost = $con->query($query) or die($con->error);

            $postRow = $forumPost->fetch_assoc();


            $query = "SELECT * FROM users WHERE id = '$userId'";

            $user = $con->query($query) or die($con->error);
            $userRow = $user->fetch_assoc();

            $profilePicture = $userRow['profile_picture'];
            $name = $userRow['name'];
            $date = date_format(date_create($today), 'M d, Y h:i A');
            $description = $postRow['description'];
            $postId = $postRow['id'];
            $comments = array();
            echo json_encode(array(
                'profile_picture' => $profilePicture,
                'name' => $name,
                'date' => $date,
                'description' => filter($description, $con),
                'post_id' => $postId,
                'comments' => $comments
            ));
        }else{
            echo 0;
        }
    }else{
        echo header('HTTP/1.1 403 Forbidden');
    }
?>