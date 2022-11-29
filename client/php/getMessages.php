<?php

    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        session_start();
        include('connection.php');
        $con = connect();

        if(isset($_GET) && isset($_SESSION['user_id'])){
            $userIdOne = $_SESSION['user_id'];
            $userIdTwo = $_GET['user_id'];


            $query = "SELECT * FROM messages WHERE sender_id = '$userIdOne' AND receiver_id = '$userIdTwo' OR sender_id = '$userIdTwo' AND receiver_id = '$userIdOne'";
            $message = $con->query($query) or die($con->error);
            $messages = array();
            $mine = false;
            while($row = $message->fetch_assoc()){

                if($row['sender_id'] == $_SESSION['user_id']){
                    $mine = true;
                }else{
                    $mine = false;
                }
                $messages[] = array(
                    'message' => $row,
                    'mine' => $mine
                );
            }
            echo json_encode($messages);
            
        }else{
            echo 'index.html';
        }
    }else{
        echo header('HTTP/1.0 403 Forbidden');
    }
?>