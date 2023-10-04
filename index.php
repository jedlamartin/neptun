<?php
    session_start();

    if(isset($_SESSION['neptun'])){
        if($_SESSION['priv']=='admin' || $_SESSION['priv']=='targyadmin'){
            include('indexadmin.php');
        }
        else if($_SESSION['priv']=='oktato'){
            include('indexoktato.php');
        }
        else if($_SESSION['priv']=='hallgato'){
            include('indexhallgato.php');
        }
    }
    else include('indexuafelhasznalo.php');
?>