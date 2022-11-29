
<?php
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        include('connection.php');
        $con = connect();
        if(isset($_POST)){
            $wordId = $_POST['word_id'];

            $query = "SELECT * FROM bad_words WHERE id = '$wordId'";
            $words = $con->query($query) or die($con->error);

            if($wordRow = $words->fetch_assoc()){
                $query = "DELETE FROM bad_words WHERE id = '$wordId'";
                $con->query($query) or die($con->error);
                echo 1;
            }else{
                echo 0;
            }
        }
    }else{
        echo header('HTTP/1.1 403 Forbidden');
    }
?>