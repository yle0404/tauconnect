<?php
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        session_start();
        include('connection.php');
        $con = connect();

        if(isset($_SESSION['user_id']) && isset($_FILES)){
            $userId = $_SESSION['user_id'];
            $filename = $_FILES['image']['name'];
            $tmpName = $_FILES['image']['tmp_name'];

            $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);
            $fileExtension = strtolower($fileExtension);

            $allowedExtensions = array('jpg','jpeg','png');
            $filePath = "";
            if(in_array($fileExtension, $allowedExtensions)){
                $filePath = "../../tauApi/public/".uniqid().".".$fileExtension;
                move_uploaded_file($tmpName,$filePath);

                $query = "UPDATE users SET profile_picture = '$filePath' WHERE id = '$userId'";
                $con->query($query) or die($con->error);

                echo $filePath;
            }else{
                echo 'invalid';
            }
        }
    }else{
        echo header('HTTP/1.0 403 Forbidden');
    }
?>