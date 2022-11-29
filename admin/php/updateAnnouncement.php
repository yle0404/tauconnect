<?php
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        include('connection.php');
        $con = connect();

        if(isset($_POST)){
            $announcement = htmlspecialchars($_POST['announcement']);
            $announcementId = htmlspecialchars($_POST['announcement_id']);

            $query = "UPDATE announcements SET description = '$announcement' WHERE id = '$announcementId'";
            $con->query($query) or die($con->error);

            echo 'ok';
        }
    }else{
        echo header('HTTP/1.1 403 Forbidden');
    }
    
?>