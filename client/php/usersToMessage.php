<?php
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        session_start();
        include('connection.php');
        $con = connect();

        if(isset($_SESSION['user_id'])){
            $myId = $_SESSION['user_id'];
            $query = "SELECT * FROM users WHERE user_type != 'admin' and id != '$myId'";

            $users = $con->query($query) or die($con->error);
            $userArray = array();
            while($userRow = $users->fetch_assoc()){
                $userId = $userRow['id'];
                $query = "SELECT * FROM messages WHERE sender_id = '$myId' AND receiver_id = '$userId'";
                $message = $con->query($query) or die($con->error);

                if(!($messageRow = $message->fetch_assoc())){
                    $userArray[] = array(
                        'id' => $userRow['id'],
                        'name' => $userRow['name']
                    );
                }
            }
            echo json_encode($userArray);
        }
        
    }else{
        echo header('HTTP/1.1 403 Forbidden');
    }
?>