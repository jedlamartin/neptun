<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="css/bootstrap.min.<?= $_COOKIE['stilus'] ?>.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
        <link rel="stylesheet" href="css/style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Neptun</title>
    </head>
    <body>
    <div class="container">
        <?php
        include("csatlakozas.php");

        include('fejlec.php');
        ?>
        <h1 style="margin-bottom: 50px;">Neptun nyilvántartási rendszer</h1>
        <h3>Adatok:</h3>
        <?php
        $mysqli=csatlakozas();
        $neptun = mysqli_real_escape_string($mysqli, $_SESSION['neptun']);
        $priv = mysqli_real_escape_string($mysqli, $_SESSION['priv']);
        $query = 'SELECT neptun, concat(vezeteknev, " ", keresztnev) AS nev, kepzes, felvetel_ev  FROM hallgatok WHERE neptun="' . $neptun . '";';
        $result = mysqli_query($mysqli, $query) or die(mysqli_errno($mysqli));
        $row = mysqli_fetch_array($result);
        ?>
        <p>Neptun kód: <?= $row['neptun'] ?></p>
        <p>Név: <?= $row['nev'] ?></p>
        <p>Képzés: <?= $row['kepzes'] ?></p>
        <p>Felvétel éve: <?= $row['felvetel_ev'] ?></p>
    </div>
    </body>
</html>

