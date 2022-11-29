<?php
    if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
        session_start();
        include('connection.php');
        $con = connect();
        $today = getCurrentDate();
    
        if(isset($_POST) && isset($_SESSION['user_id'])){
            $userId = $_SESSION['user_id'];
            $receiverId = $_POST['receiver_id'];
            $message = htmlspecialchars($_POST['message']);
            

            

            $query = "INSERT INTO messages(`sender_id`,`receiver_id`,`message`,`read`,`created_at`,`updated_at`)VALUES('$userId','$receiverId','$message','no','$today','$today')";
            $con->query($query) or die($con->query);

            $query = "SELECT * FROM messages WHERE id = LAST_INSERT_ID()";
            $message = $con->query($query) or die($con->error);
            $messageRow = $message->fetch_assoc();

            $query = "SELECT * FROM users WHERE id = '$userId'";
            $user = $con->query($query) or die($con->error);
            $userRow = $user->fetch_assoc();

            $senderName = $userRow['name'];

            $notificationMessage = $senderName . " sent you a message";
            $query = "INSERT INTO notifications(`user_id`,`title`,`message`,`read`,`created_at`,`updated_at`)VALUES('$receiverId','New Message','$notificationMessage','no','$today','$today')";
            $con->query($query) or die($con->error);
    
            
            echo json_encode($messageRow);
        }else{
            echo 'index.html';
        }
    }else{
        echo header('HTTP/1.0 403 Forbidden');
    }
    
?>