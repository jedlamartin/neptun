<?php
    session_start();

    if(isset($_GET['oktatoid']) && isset($_SESSION['priv']) && $_SESSION['priv']=='admin'){
        include('csatlakozas.php');
        $mysqli=csatlakozas();
        $id=mysqli_real_escape_string($mysqli, $_GET['oktatoid']);
        $ellenorzes='SELECT * FROM oktatok WHERE id='.$id.';';
        $result=mysqli_query($mysqli, $ellenorzes) or die(mysqli_error($mysqli));
        if(mysqli_num_rows($result)==1){
            $row=mysqli_fetch_array($result);
            $neptun=$row['neptun'];
            
            $query='DELETE FROM oktatok WHERE id='.$id.';';
            mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
            
            $query='DELETE FROM felhasznalok WHERE neptun="'.$neptun.'";';
            mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));

            $_SESSION['message']='Sikeres törlés!';
        }
        else{
            $_SESSION['message']='Nem található az adott id!';
        }
        mysqli_close($mysqli);
        header("Location: oktatok.php");
        exit;
    }

?>