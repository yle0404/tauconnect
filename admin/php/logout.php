<?php
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        session_start();
        if(isset($_SESSION['admin_id'])){
            session_unset();
        }
        echo 'index.html';
    }else{
        echo header('HTTP/1.0 403 Forbidden');
    }
?>