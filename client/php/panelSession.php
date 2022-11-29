<?php

    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        session_start();
        if(!isset($_SESSION['user_id'])){
            echo '';
        }else{
            echo 'ok';
        }
    }else{
        echo header('HTTP/1.0 403 Forbidden');
    }
    
?>