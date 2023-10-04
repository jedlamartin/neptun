<?php
    session_start();


    if(isset($_SESSION['neptun'])){
        if($_SESSION['priv']=='admin' || $_SESSION['priv']=='oktato' || $_SESSION['priv']=='targyadmin'){
            include('jegyekadminoktato.php');
        }
        else if($_SESSION['priv']=='hallgato'){
            include('jegyekhallgato.php');
        }
        if(isset($_SESSION['message'])){
            echo $_SESSION['message'];
            unset($_SESSION['message']);
        }
    }
    else header('Location: bejelentkezes.php');
?>