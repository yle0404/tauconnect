<?php

    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        session_start();
        include('connection.php');
        $con = connect();
        if(isset($_POST['email']) && isset($_POST['password'])){
            $email = htmlspecialchars($_POST['email']);
            $password = htmlspecialchars($_POST['password']);
    
            $query = "SELECT * FROM users WHERE (email = '$email' OR student_teacher_id = '$email') AND user_type != 'admin'";
            $user = $con->query($query) or die($con->error);
            if($row = $user->fetch_assoc()){
                if(password_verify($password, $row['password'])){
                    $_SESSION['user_id'] = $row['id'];
                    echo 'panel.html';
                }else{
                    echo 'invalid';
                }
            }else{
                echo 'invalid';
            }
        }else{
            echo header('HTTP/1.0 401 Unauthorized');
        }
    }else{
        echo header('HTTP/1.0 403 Forbidden');
    }
    
?>