<?php
    session_start();

    if(isset($_GET['jegyid']) && isset($_SESSION['priv']) && ($_SESSION['priv']=='admin' || $_SESSION['priv']=='oktato')){
        include('csatlakozas.php');
        $mysqli=csatlakozas();
        $id=mysqli_real_escape_string($mysqli, $_GET['jegyid']);
        $ellenorzes='SELECT * FROM jegy WHERE id='.$id.';';
        $row=mysqli_query($mysqli, $ellenorzes) or die(mysqli_error($mysqli));
        if(mysqli_num_rows($row)==1){
            $query='DELETE FROM jegy WHERE id='.$id.';';
            mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
            mysqli_close($mysqli);
            $_SESSION['message']='Sikeres törlés!';
        }
        else{
            $_SESSION['message']='Nem található az adott id!';
        }
        
        header("Location: jegyek.php");
        exit;
    }

?>