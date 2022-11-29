<?php
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        session_start();
        include('connection.php');
        include('filter.php');
        $con = connect();
    
        if(isset($_GET) && isset($_SESSION['user_id'])){
            $userId = $_SESSION['user_id'];
            $query = "SELECT * FROM complaints WHERE user_id = '$userId'";
            $complaint = $con->query($query) or die($con->error);
            $complaints = array();
            while($row = $complaint->fetch_assoc()){
                $date = date_create($row['created_at']);
                $date = date_format($date, "M d, Y h:i A");
                $complaints[] = array(
                    'complaint' => filter($row['complaint'], $con),
                    'status' => $row['status'],
                    'category' => $row['category'],
                    'date' => $date
                );
            }
    
            echo json_encode($complaints);
        }else{
            echo 'index.html';
        }
    }else{
        echo header('HTTP/1.0 403 Forbidden');
    }
    
?>