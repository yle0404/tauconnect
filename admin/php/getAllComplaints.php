<?php
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        session_start();
        include('connection.php');
        include('filter.php');
        $con = connect();

        if(isset($_GET) && isset($_SESSION['admin_id'])){
            $query = "SELECT * FROM complaints";
            $complaint = $con->query($query) or die($con->error);
            $complaints = array();
            while($row = $complaint->fetch_assoc()){
                //get student name
                $userId = $row['user_id'];
                $query = "SELECT * FROM users WHERE id = '$userId'";
                $user = $con->query($query) or die($con->error);
                $userRow = $user->fetch_assoc();
                $studentName = $userRow['name'];
                $thisComplaint = $row['complaint'];
                $status = $row['status'];
                $date = date_create($row['created_at']);
                $date = date_format($date, "M d, Y h:i A");

                $complaints[] = array(
                    'id' => $row['id'],
                    'student_name' => $studentName,
                    'complaint' => filter($thisComplaint, $con),
                    'category' => $row['category'],
                    'date' => $date,
                    'status' => $status
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