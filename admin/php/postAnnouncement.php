<?php
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        session_start();
        include('connection.php');
        $con = connect();
        $date = getCurrentDate();
        if(isset($_POST) && isset($_SESSION['admin_id'])){
            $title = htmlspecialchars($_POST['title']);
            $description = htmlspecialchars($_POST['description']);
            $query = "INSERT INTO announcements(`title`,`description`,`created_at`,`updated_at`)VALUES('$title','$description','$date','$date')";
            $con->query($query) or die($con->error);
            $query = "SELECT * FROM announcements WHERE id = LAST_INSERT_ID()";
            $announcement = $con->query($query) or die($con->error);
            $row = $announcement->fetch_assoc();


            echo json_encode(
                array(
                    'description' => filter($row['description']),
                    'id' => $row['id'],
                    'created_at' => date_format(date_create($row['created_at']), "M d, Y h:i A")
                )
            );
        }else{
            echo 'index.html';
        }
    }else{
        echo header('HTTP/1.0 403 Forbidden');
    }
?>