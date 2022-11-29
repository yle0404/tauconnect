<?php
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        session_start();

        include('connection.php');
        include('filter.php');
        $con = connect();
        
        if(isset($_GET) && isset($_SESSION['admin_id'])){
            $query = "SELECT * FROM announcements ORDER BY id DESC";
            $an = $con->query($query) or die($con->error);
            $data = array();

            while($row = $an->fetch_assoc()){
                $anouncerId = $row['user_id'];
                $query = "SELECT * FROM users WHERE id = '$anouncerId'";
                $announcer = $con->query($query) or die($con->error);
                $announcerRow = $announcer->fetch_assoc();
                $name = $announcerRow['name'];

                $date = date_create($row['created_at']);
                $date = date_format($date, 'M d, Y h:i A');

                $announcementId = $row['id'];
                $announcement = $row['description'];

                $data[] = array(
                    'id' => $announcementId,
                    'name' => $name,
                    'description' => filter($announcement, $con),
                    'created_at' => $date
                );
            }
            echo json_encode($data);
        }else{
            echo 'index.html';
        }
    }else{
        echo header('HTTP/1.0 403 Forbidden');
    }
?>