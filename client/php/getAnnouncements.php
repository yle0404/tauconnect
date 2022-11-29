<?php
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        session_start();
        include('connection.php');
        include('filter.php');
        $con = connect();

        if(isset($_GET) && isset($_SESSION['user_id'])){
            $userId = $_SESSION['user_id'];

            $query = "SELECT * FROM users WHERE id = '$userId'";
            $user = $con->query($query) or die($con->error);
            $userRow = $user->fetch_assoc();
            $userType = $userRow['user_type'];

            $query = "SELECT * FROM announcements ORDER BY id DESC";
            $announcement = $con->query($query) or die($con->error);
            $announcements = array();

            while($row = $announcement->fetch_assoc()){
                $announcerId = $row['user_id'];
                $query = "SELECT * FROM users WHERE id = '$announcerId'";
                $announcer = $con->query($query) or die($con->error);
                $announcerRow = $announcer->fetch_assoc();
                $name = $announcerRow['name'];
                $description = $row['description'];
                $createdAt = date_format(date_create($row['created_at']), "M d, Y h:i A");
                $announcements[] = array(
                    'name' => $name,
                    'announcement' => filter($description, $con),
                    'date' => $createdAt
                );
            }

            echo json_encode(array(
                'user_type' => $userType,
                'announcements' => $announcements
            ));
        }
    }else{
        echo header('HTTP/1.0 403 Forbidden');
    }
?>