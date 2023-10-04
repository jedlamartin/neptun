<?php
    function csatlakozas(){
        $mysqli=mysqli_connect('localhost','root','');
        if(!$mysqli){
            echo 'Connection failed';
            echo mysqli_connect_error().'<br />';
            die();
        }
        mysqli_select_db($mysqli, "neptun");
        //echo 'Connected';
        return $mysqli;
    }
?>