<?php
session_start();

if(!isset($_COOKIE['stilus'])){
    setcookie('stilus', 'vilagos');
   header("Refresh:0");

}

require 'csatlakozas.php';
$mysqli=csatlakozas();

$message='';

if (isset($_POST['login'])) {
    $felhasznalonev = $_POST['felhasznalonev'];
    $password = $_POST['password'];

    $hashed_password=hash('sha256', $password);

    $query = 'SELECT * FROM felhasznalok WHERE felhasznalonev="' . $felhasznalonev . '" AND jelszo="' . $hashed_password . '";';
    $result = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $_SESSION['felhasznalonev'] = $row['felhasznalonev'];
        $_SESSION['neptun'] = $row['neptun'];
        $_SESSION['priv'] = $row['priv'];
        setcookie('stilus', $row['stilus']);
        mysqli_close($mysqli);
        header("Location: index.php");
        exit;
    } else {
        $message.= "Helytelen felhasználónév vagy jelszó!";
    }
}
?>

<html>

<head>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.<?= $_COOKIE['stilus'] ?>.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Login</title>
</head>

<body>
    <div class="container">
        <form method="post">

            <div class="card border-secondary mb-3" style="width: 50%; display: flex; justify-content: center; top: 50%; left: 50%; transform: translate(-50%, +50%);">
                <!--<div class="card-header">Bejelentkezés</div>-->
                <div class="card-body">
                    <label for="felhasznalonev" class="form-label">Felhasználónév</label>
                    <input class="form-control" type="text" id="felhasznalonev" name="felhasznalonev" />
                    <br />
                    
                    <label for="password" class="form-label">Jelszó</label>
                    <input class="form-control" type="password" id="password" name="password" />

                </div>
                <div class="card-header"><input class="btn btn-primary" type="submit" name="login" value="Bejelentkezés" /></div>
            </div>


        </form>
    </div>
<?php
    echo '<p style="position: absolute; bottom: 0px;">'.$message.'</p>';
    $message='';
?>

</body>

</html>