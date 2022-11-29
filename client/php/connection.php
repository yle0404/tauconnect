<?php
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        function connect(){
            // $hostname = "localhost";
            // $username = "root";
            // $password = "";
            // $database = "tau_db";

            $hostname = "localhost";
            $username = "u568496919_tau";
            $password = "TauPassword11";
            $database = "u568496919_tau_db";

            $con = new mysqli($hostname, $username, $password, $database);

            if($con->connect_error){
                echo $con->connect_error;
            }

            return $con;
        }

        function getCurrentDate(){
            date_default_timezone_set('Asia/Manila');
            $date = date('Y-m-d H:i:s');

            return $date;
        }
    }else{
        echo header('HTTP/1.0 403 Forbidden');
    }
?>