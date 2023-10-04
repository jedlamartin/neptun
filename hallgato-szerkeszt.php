<?php
session_start();
if (isset($_SESSION['priv']) && $_SESSION['priv'] == 'admin') :

    include('csatlakozas.php');
    $mysqli=csatlakozas();


    if (isset($_POST['submit'])) {

        $id = mysqli_real_escape_string($mysqli, $_POST['hallgatoid']);
        $fnev = mysqli_real_escape_string($mysqli, $_POST['fnev']);
        $jelszo = mysqli_real_escape_string($mysqli, $_POST['jelszo']);
        $jelszoell = mysqli_real_escape_string($mysqli, $_POST['jelszoell']);
        $neptunkod = mysqli_real_escape_string($mysqli, $_POST['neptun']);
        $vnev = mysqli_real_escape_string($mysqli, $_POST['vnev']);
        $knev = mysqli_real_escape_string($mysqli, $_POST['knev']);
        $kepzes = mysqli_real_escape_string($mysqli, $_POST['kepzes']);
        $ev = mysqli_real_escape_string($mysqli, $_POST['ev']);

        $ellenorzes = 'SELECT * FROM hallgatok WHERE id=' . $id . ';';
        $result = mysqli_query($mysqli, $ellenorzes) or die(mysqli_error($mysqli));
        if (mysqli_num_rows($result) == 1) {

            if ($fnev && $neptunkod && $vnev && $knev && $kepzes && $ev && preg_match('/^[0-9]+$/', $ev)) {
                if (!$jelszo && !$jelszoell) {
                    $query = sprintf(
                        "UPDATE hallgatok 
                        INNER JOIN felhasznalok 
                        ON felhasznalok.neptun = hallgatok.neptun 
                        SET hallgatok.neptun='%s', felhasznalok.neptun='%s', felhasznalonev='%s', vezeteknev='%s', keresztnev='%s', kepzes='%s', felvetel_ev='%s'
                        WHERE hallgatok.id=%d",
                        $neptunkod,
                        $neptunkod,
                        $fnev,
                        $vnev,
                        $knev,
                        $kepzes,
                        $ev,
                        $id
                    );

                    mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
                    mysqli_close($mysqli);

                    $_SESSION['message'] = 'Sikeres módosítás!';
                    header('Location: hallgatok.php');
                    return;
                } else if ($jelszo == $jelszoell) {

                    $query = sprintf(
                        "UPDATE hallgatok 
                        INNER JOIN felhasznalok 
                        ON felhasznalok.neptun = hallgatok.neptun 
                        SET hallgatok.neptun='%s', felhasznalok.neptun='%s', felhasznalonev='%s', jelszo='%s', vezeteknev='%s', keresztnev='%s', kepzes='%s', felvetel_ev='%s'
                        WHERE hallgatok.id=%d",
                        $neptunkod,
                        $neptunkod,
                        $fnev,
                        $jelszo,
                        $vnev,
                        $knev,
                        $kepzes,
                        $ev,
                        $id
                    );


                    mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
                    mysqli_close($mysqli);

                    $_SESSION['message'] = 'Sikeres módosítás!';
                    header('Location: hallgatok.php');
                    return;
                } else {
                    $_SESSION['message'] = 'A megadott jelszavak nem egyeznek!';
                }
            } else {
                $_SESSION['message'] = 'Minden mező kitöltése kötelező a jelszavakon kívül!';
            }
        } else {
            $_SESSION['message'] = 'Nem található az adott id!';
            mysqli_close($mysqli);
            header("Location: hallgatok.php");
        }
    }

    if (!isset($_GET['hallgatoid'])) {
        mysqli_close($mysqli);
        header("Location: hallgatok.php");
        return;
    }
?>

    <html>

    <head>
    <link rel="stylesheet" href="css/bootstrap.min.<?= $_COOKIE['stilus'] ?>.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


        <title>Oktatók</title>
    </head>

    <body>
        <div class="container">
            <?php
            include('fejlec.php');

            ?>
            <h1>Oktató adatai</h1>

            <?php
            $id = mysqli_real_escape_string($mysqli, $_GET['hallgatoid']);
            $hallgatoid = $_GET['hallgatoid'];
            $query = 'SELECT id, hallgatok.neptun, hallgatok.vezeteknev, hallgatok.keresztnev, hallgatok.kepzes, hallgatok.felvetel_ev, felhasznalok.felhasznalonev, felhasznalok.jelszo FROM hallgatok INNER JOIN felhasznalok ON hallgatok.neptun=felhasznalok.neptun WHERE hallgatok.id=' . $hallgatoid . ';';
            $result = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
            $hallgato = mysqli_fetch_array($result);
            ?>

            <form method="post">

                <input type="hidden" name="hallgatoid" id="hallgatoid" value="<?= $id ?>" />

                <label for="fnev" class="form-label">Felhasználónév</label>
                <input class="form-control" type="text" id="fnev" name="fnev" value="<?= $hallgato['felhasznalonev'] ?>" />
                <br />

                <label for="jelszo" class="form-label">Jelszó</label>
                <input class="form-control" type="password" id="jelszo" name="jelszo" placeholder="Hagyja üresen ha nem szeretné megváltoztatni!" />
                <br />

                <label for="jelszoell" class="form-label">Jelszó újból</label>
                <input class="form-control" type="password" id="jelszoell" name="jelszoell" placeholder="Hagyja üresen ha nem szeretné megváltoztatni!" />
                <br />

                <label for="neptun" class="form-label">Neptun kód</label>
                <input class="form-control" type="text" id="neptun" name="neptun" maxlength="6" value="<?= $hallgato['neptun'] ?>" />
                <br />

                <label for="vnev" class="form-label">Vezetéknév</label>
                <input class="form-control" type="text" id="vnev" name="vnev" value="<?= $hallgato['vezeteknev'] ?>" />
                <br />

                <label for="knev" class="form-label">Keresztnév</label>
                <input class="form-control" type="text" id="knev" name="knev" value="<?= $hallgato['keresztnev'] ?>" />
                <br />

                <label for="kepzes" class="form-label">Képzés</label>
                <select class="form-select" id="kepzes" name="kepzes" value="Kérem válasszon">
                    <option value="mérnökinformatika" <?php if ($hallgato['kepzes'] == 'mérnökinformatika') echo 'selected';?>>mérnökinformatika</option>
                    <option value="villamosmérnöki" <?php if ($hallgato['kepzes'] == 'villamosmérnöki') echo 'selected';?>>villamosmérnöki</option>
                </select>
                <br />

                <label for="ev" class="form-label">Felvétel éve</label>
                <input type="text" id="ev" name="ev" class="form-control" maxlength="4" value="<?= $hallgato['felvetel_ev'] ?>" />
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