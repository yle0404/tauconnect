<?php
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        session_start();
        include('connection.php');
        $con = connect();
        $date = getCurrentDate();


        if(isset($_POST) && isset($_SESSION['admin_id'])){
            $email = htmlspecialchars($_POST['email']);
            $name = htmlspecialchars($_POST['name']);
            $userType = htmlspecialchars($_POST['user_type']);
            $studentTeacherId = htmlspecialchars($_POST['student_teacher_id']);
            //check if email already exists
            $query = "SELECT * FROM users WHERE email = '$email'";
            $user = $con->query($query) or die($con->error);
            $users = array();
            while($row = $user->fetch_assoc()){
                $users[] = $row;
            }

            if(count($users) == 1){
                echo 'email exists';
            }else{
                $password = password_hash('password', PASSWORD_DEFAULT);
                $query = "INSERT INTO users(`name`,`email`,`user_type`,`password`,`created_at`,`updated_at`, `student_teacher_id`)VALUES('$name','$email','$userType','$password','$date','$date','$studentTeacherId')";
                $con->query($query) or die($con->error);
                $query = "SELECT * FROM users WHERE id = LAST_INSERT_ID()";
                $user = $con->query($query) or die($con->error);
                $data = $user->fetch_assoc();

                echo json_encode($data);
            }
        }else{
            echo 'index.html';
        }
    }else{
        echo header('HTTP/1.0 403 Forbidden');
    }
?>