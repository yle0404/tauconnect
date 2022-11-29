<?php
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        include('connection.php');
        $con = connect();

        $query = "SELECT * FROM bad_words";
        $word = $con->query($query) or die($con->error);
        $words = array();
        while($wordRow = $word->fetch_assoc()){
            $words[] = $wordRow;
        }

        echo json_encode($words);
    }else{
        echo header('HTTP/1.1 403 Forbidden');
    }
?>