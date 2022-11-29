
<?php
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        include('connection.php');
        include('filter.php');
        $con = connect();

        if(isset($_POST)){
            $complaintId = $_POST['complaint_id'];

            $query = "SELECT * FROM complaints WHERE id = '$complaintId'";
            $complaint = $con->query($query) or die($con->error);

            $complaintRow = $complaint->fetch_assoc();
            $userId = $complaintRow['user_id'];
            $query = "SELECT * FROM users WHERE id = '$userId'";
            $user = $con->query($query) or die($con->error);
            $userRow = $user->fetch_assoc();
            $response = array(
                'name' => $userRow['name'],
                'complaint' => filter($complaintRow['complaint'], $con),
                'date' => date_format(date_create($complaintRow['created_at']), "M d, Y")
            );

            echo json_encode($response);
        }
    }else{
        echo header('HTTP/1.1 403 Forbidden');
    }
?>