<?php
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        session_start();
        include('connection.php');
        $con = connect();

        if(isset($_POST) && isset($_SESSION['admin_id'])){
            $password = htmlspecialchars($_POST['password']);
            $adminId = $_SESSION['admin_id'];
            $query = "SELECT * FROM users WHERE user_type = 'admin' AND id = '$adminId'";
            $user = $con->query($query) or die($con->error);
            $userRow = $user->fetch_assoc();

            $encryptedPassword = $userRow['password'];

            if(password_verify($password, $encryptedPassword)){
                echo 'ok';
            }else{
                echo 'invalid';
            }
        }else{
            echo 'session expired';
        }
    }else{
        echo header('HTTP/1.1 403 Forbidden');
    }
?>