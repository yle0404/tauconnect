<?php
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        include('connection.php');
        $con = connect();
        $today = getCurrentDate();
        if(isset($_POST)){
            $word = htmlspecialchars($_POST['word']);

            $query = "SELECT * FROM bad_words WHERE word = '$word'";
            $wordQuery = $con->query($query) or die($con->error);
            
            if($wordRow = $wordQuery->fetch_assoc()){
                echo 'already exists';
            }else{
                $query = "INSERT INTO bad_words(`word`,`created_at`,`updated_at`)VALUES('$word','$today','$today')";
                $con->query($query) or die($con->error);

                $query = "SELECT * FROM bad_words WHERE id = LAST_INSERT_ID()";
                $badWord = $con->query($query) or die($con->error);

                $badWordRow = $badWord->fetch_assoc();

                echo json_encode($badWordRow);
            }
        }
    }else{
        echo header('HTTP/1.1 403 Forbidden');
    }
?>