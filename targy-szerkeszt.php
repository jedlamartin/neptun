<?php
session_start();
if (isset($_SESSION['priv']) && ($_SESSION['priv'] == 'admin' || $_SESSION['priv'] == 'targyadmin') ):

    include('csatlakozas.php');
    $mysqli=csatlakozas();



    if (isset($_POST['submit'])) {

        $id = mysqli_real_escape_string($mysqli, $_POST['targyid']);
        $kod = mysqli_real_escape_string($mysqli, $_POST['tkod']);
        $nev = mysqli_real_escape_string($mysqli, $_POST['nev']);
        $tanszek = mysqli_real_escape_string($mysqli, $_POST['tanszek']);

        $ellenorzes = 'SELECT * FROM targy WHERE id=' . $id . ';';
        $result = mysqli_query($mysqli, $ellenorzes) or die(mysqli_error($mysqli));
        if (mysqli_num_rows($result) == 1) {

            if ($kod && $nev && $tanszek) {
                    $query = sprintf(
                        "UPDATE targy 
                        SET targy.kod='%s', targy.megnevezes='%s', targy.tanszek='%s' 
                        WHERE targy.id=%d",
                        $kod,
                        $nev,
                        $tanszek,
                        $id
                    );

                    mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
                    mysqli_close($mysqli);

                    $_SESSION['message'] = 'Sikeres módosítás!';
                    header('Location: targyak.php');
                    return;
                
            } else {
                $_SESSION['message'] = 'Minden mező kitöltése kötelező a jelszavakon kívül!';
            }
        } else {
            $_SESSION['message'] = 'Nem található az adott id!';
            mysqli_close($mysqli);
            header("Location: targyak.php");
        }
    }

    if (!isset($_GET['targyid'])) {
        mysqli_close($mysqli);
        header("Location: targyak.php");
        return;
    }
?>

    <html>

    <head>
        <link rel="stylesheet" href="css/bootstrap.min.<?=$_COOKIE['stilus']?>.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

        <link rel="stylesheet" href="css/style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


        <title>Tárgyak</title>
    </head>

    <body>
        <div class="container">
            <?php
            include('fejlec.php');

            ?>
            <h1>Oktató adatai</h1>

            <?php
            $targyid = mysqli_real_escape_string($mysqli, $_GET['targyid']);
            $query = 'SELECT * FROM targy WHERE targy.id=' . $targyid . ';';
            $result = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
            $targy = mysqli_fetch_array($result);
            ?>

            <form method="post">

                <input type="hidden" name="targyid" id="targyid" value="<?= $targyid ?>" />

                <label for="fnev" class="form-label">Tárgykód</label>
                <input class="form-control" type="text" id="tkod" name="tkod" value="<?= $targy['kod'] ?>" />
                <br />

                <label for="neptun" class="form-label">Név</label>
                <input class="form-control" type="text" id="nev" name="nev" value="<?= $targy['megnevezes'] ?>" />
                <br />

                <label for="tanszek" class="form-label">Tanszék</label>
                <select class="form-select" id="tanszek" name="tanszek" value="Kérem válasszon">
                    <option value="AUT" <?php if ($targy['tanszek'] == 'AUT') echo 'selected';?>>AUT</option>
                    <option value="MIT" <?php if ($targy['tanszek'] == 'MIT') echo 'selected';?>>MIT</option>
                    <option value="HVT" <?php if ($targy['tanszek'] == 'HVT') echo 'selected';?>>HVT</option>
                    <option value="IIT" <?php if ($targy['tanszek'] == 'IIT') echo 'selected';?>>IIT</option>
                </select>
                <br />

                <input class="btn btn-primary" type="submit" name="submit" value="Küldés" />
        </div>
        </form>

        </div>

        <?php
        if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            unset($_SESSION['message']);
        }
        ?>

    </body>

    </html>
<?php
    mysqli_close($mysqli);
endif;
?>