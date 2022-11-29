<?php
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        session_start();
        include('connection.php');
        $con = connect();

        if(isset($_POST) && isset($_SESSION['admin_id'])){
            $userId = $_POST['user_id'];
            $query = "DELETE FROM users WHERE id = '$userId'";
            $con->query($query) or die($con->error);

            $query = "DELETE FROM posts WHERE user_id = '$userId'";
            $con->query($query) or die($con->error);

            $query = "DELETE FROM comments WHERE user_id = '$userId'";
            $con->query($query) or die($con->error);

            $query = "DELETE FROM complaints WHERE user_id = '$userId'";
            $con->query($query) or die($con->error);
            
            $query = "DELETE FROM messages WHERE sender_id = '$userId' OR receiver_id = '$userId'";
            $con->query($query) or die($con->error);

            echo 'ok';
        }else{
            echo 'index.html';
        }
    }else{
        echo header('HTTP/1.0 403 Forbidden');
    }
?>