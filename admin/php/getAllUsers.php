<?php
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        session_start();
        include('connection.php');
        $con = connect();

        if(isset($_GET) && isset($_SESSION['admin_id'])){
            $query = "SELECT * FROM users WHERE user_type != 'admin'";
            $user = $con->query($query) or die($con->error);
            $users = array();
            while($row = $user->fetch_assoc()){
                $users[] = $row;
            }
            echo json_encode($users);
        }else{
            echo 'index.html';
        }
    }else{
        echo header('HTTP/1.0 403 Forbidden');
    }
?>