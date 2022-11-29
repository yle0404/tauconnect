<?php
    $hostname = "localhost";
    $username = "u568496919_tau";
    $password = "Tauconnect11";
    $database = "u568496919_tau_db";

    $con = new mysqli($hostname, $username, $password, $database);
    $query = "SELECT * FROM posts";

    $post = $con->query($query) or die($con->error);
    $posts = array();

    while($row = $post->fetch_assoc()){
        $comments = 0;
        $postId = $row['id'];

        $query = "SELECT * FROM comments WHERE post_id = '$postId'";
        $comment = $con->query($query) or die($con->error);

        while($commentRow = $comment->fetch_assoc()){
            $comments++;
        }

        $query = "UPDATE posts SET comments = '$comments' WHERE id = '$postId'";
        $con->query($query) or die($con->error);
    }
    echo 'ok';
?>