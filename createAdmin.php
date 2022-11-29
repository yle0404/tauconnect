<?php
    $con = new mysqli("localhost","root","","tau_db");
    $password = password_hash('password', PASSWORD_DEFAULT);
    $query = "INSERT INTO users(`name`,`email`,`password`,`user_type`)VALUES('Admin','tauAdmin@gmail.com','$password','admin')";
    $con->query($query) or die($con->error);
    echo 'ok';
?>