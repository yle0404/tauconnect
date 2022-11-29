<?php
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        include('connection.php');
        $con = connect();
        $today = getCurrentDate();
        if(isset($_POST)){
            $email = htmlspecialchars($_POST['email']);
            $password = htmlspecialchars($_POST['password']);

            $query = "SELECT * FROM users WHERE email = '$email'";
            $user = $con->query($query) or die($con->error);
            if($userRow = $user->fetch_assoc()){
                echo 'already exists';
            }else{
                $encryptedPassword = password_hash($password, PASSWORD_DEFAULT);
                $query = "INSERT INTO users(`name`,`email`,`password`,`user_type`,`created_at`,`updated_at`)VALUES('Admin','$email','$encryptedPassword','admin','$today','$today')";
                $con->query($query) or die($con->error);
                echo 'ok';
            }
            
        }
    }else{
        echo header('HTTP/1.1 403 Forbidden');
    }
?>