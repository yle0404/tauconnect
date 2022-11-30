<?php
    $con = new mysqli("localhost","u655901399_yle0404","Tau@connect1","u655901399_tauconnect");
    $password = password_hash('password', PASSWORD_DEFAULT);
    $query = "INSERT INTO users(`name`,`email`,`password`,`user_type`)VALUES('Admin','tauAdmin@gmail.com','$password','admin')";
    $con->query($query) or die($con->error);
    echo 'ok';
?>