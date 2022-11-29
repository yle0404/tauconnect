<?php
     if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        function filter($text, $con){
            $filteredWord = "";
            $wordsArray = explode(" ", $text);
            foreach($wordsArray as $word){
                $query = "SELECT * FROM bad_words WHERE word = '$word'";
                $wordQuery = $con->query($query) or die($con->error);
                if($wordRow = $wordQuery->fetch_assoc()){
                    $filteredWord .= "***** ";
                }else{
                    $filteredWord .= $word . " ";
                }
            }

            return $filteredWord;
        }
     }else{
        echo header('HTTP/1.1 403 Forbidden');
     }
?>