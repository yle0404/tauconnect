<?php
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        session_start();
        include('connection.php');
        $con = connect();   
        $today = getCurrentDate();
        if(isset($_POST) && isset($_SESSION['admin_id'])){
            $complaintId = $_POST['complaint_id'];


            $query = "SELECT * FROM complaints WHERE id = '$complaintId'";
            $complaint = $con->query($query) or die($con->error);
            $complaintRow = $complaint->fetch_assoc();

            $complaintStatus = $complaintRow['status'];

            $complainantId = $complaintRow['user_id'];

            if($complaintStatus == "ACKNOWLEDGED"){
                echo 'already acknowledged';
            }else{
                $query = "UPDATE complaints SET status = 'ACKNOWLEDGED' WHERE id = '$complaintId'";
                $con->query($query) or die($con->error);

                $query = "INSERT INTO notifications(`user_id`,`title`,`message`,`created_at`,`updated_at`,`read`)VALUES('$complainantId','Complaint','One of you complaints has been ACKNOWLEDGED','$today','$today','no')";
                $con->query($query) or die($con->error);
    
                echo 'ok';
            }
            
        }else{
            echo 'index.html';
        }
    }else{
        echo header('HTTP/1.0 403 Forbidden');
    }
?>