<?php
session_start();
if (isset($_SESSION['priv']) && $_SESSION['priv'] == 'admin') :

    include('csatlakozas.php');
    $mysqli=csatlakozas();



    if (isset($_POST['submit'])) {

        $id = mysqli_real_escape_string($mysqli, $_POST['oktatoid']);
        $fnev = mysqli_real_escape_string($mysqli, $_POST['fnev']);
        $jelszo = mysqli_real_escape_string($mysqli, $_POST['jelszo']);
        $jelszoell = mysqli_real_escape_string($mysqli, $_POST['jelszoell']);
        $neptunkod = mysqli_real_escape_string($mysqli, $_POST['neptun']);
        $vnev = mysqli_real_escape_string($mysqli, $_POST['vnev']);
        $knev = mysqli_real_escape_string($mysqli, $_POST['knev']);
        $tanszek = mysqli_real_escape_string($mysqli, $_POST['tanszek']);

        $ellenorzes = 'SELECT * FROM oktatok WHERE id=' . $id . ';';
        $result = mysqli_query($mysqli, $ellenorzes) or die(mysqli_error($mysqli));
        if (mysqli_num_rows($result) == 1) {

            if ($fnev && $neptunkod && $vnev && $knev && $tanszek) {
                if (!$jelszo && !$jelszoell) {
                    $query = sprintf(
                        "UPDATE oktatok 
                        INNER JOIN felhasznalok 
                        ON felhasznalok.neptun = oktatok.neptun 
                        SET oktatok.neptun='%s', felhasznalok.neptun='%s', felhasznalonev='%s', vezeteknev='%s', keresztnev='%s', tanszek='%s' 
                        WHERE oktatok.id=%d",
                        $neptunkod,
                        $neptunkod,
                        $fnev,
                        $vnev,
                        $knev,
                        $tanszek,
                        $id
                    );

                    mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
                    mysqli_close($mysqli);

                    $_SESSION['message'] = 'Sikeres módosítás!';
                    header('Location: oktatok.php');
                    return;
                } else if ($jelszo == $jelszoell) {

                    $query = sprintf(
                        "UPDATE oktatok 
                        INNER JOIN felhasznalok 
                        ON felhasznalok.neptun = oktatok.neptun 
                        SET oktatok.neptun='%s', felhasznalok.neptun='%s', felhasznalonev='%s', jelszo='%s', vezeteknev='%s', keresztnev='%s', tanszek='%s' 
                        WHERE oktatok.id=%d",
                        $neptunkod,
                        $neptunkod,
                        $fnev,
                        $jelszo,
                        $vnev,
                        $knev,
                        $tanszek,
                        $id
                    );


                    mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
                    mysqli_close($mysqli);

                    $_SESSION['message'] = 'Sikeres módosítás!';
                    header('Location: oktatok.php');
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
            header("Location: oktatok.php");
        }
    }

    if (!isset($_GET['oktatoid'])) {
        mysqli_close($mysqli);
        header("Location: oktatok.php");
        return;
    }
?>

    <html>

    <head>
        <link rel="stylesheet" href="css/bootstrap.min.<?= $_COOKIE['stilus'] ?>.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
        <link rel="stylesheet" href="css/style.css">
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
            $id = mysqli_real_escape_string($mysqli, $_GET['oktatoid']);
            $oktatoid = $_GET['oktatoid'];
            $query = 'SELECT id, oktatok.neptun, oktatok.vezeteknev, oktatok.keresztnev, oktatok.tanszek, felhasznalok.felhasznalonev, felhasznalok.jelszo FROM oktatok INNER JOIN felhasznalok ON oktatok.neptun=felhasznalok.neptun WHERE oktatok.id=' . $oktatoid . ';';
            $result = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
            $oktato = mysqli_fetch_array($result);
            ?>

            <form method="post">

                <input type="hidden" name="oktatoid" id="oktatoid" value="<?= $id ?>" />

                <label for="fnev" class="form-label">Felhasználónév</label>
                <input class="form-control" type="text" id="fnev" name="fnev" value="<?= $oktato['felhasznalonev'] ?>" />
                <br />

                <label for="jelszo" class="form-label">Jelszó</label>
                <input class="form-control" type="password" id="jelszo" name="jelszo" placeholder="Hagyja üresen ha nem szeretné megváltoztatni!" />
                <br />

                <label for="jelszoell" class="form-label">Jelszó újból</label>
                <input class="form-control" type="password" id="jelszoell" name="jelszoell" placeholder="Hagyja üresen ha nem szeretné megváltoztatni!" />
                <br />

                <label for="neptun" class="form-label">Neptun kód</label>
                <input class="form-control" type="text" id="neptun" name="neptun" maxlength="6" value="<?= $oktato['neptun'] ?>" />
                <br />

                <label for="vnev" class="form-label">Vezetéknév</label>
                <input class="form-control" type="text" id="vnev" name="vnev" value="<?= $oktato['vezeteknev'] ?>" />
                <br />

                <label for="knev" class="form-label">Keresztnév</label>
                <input class="form-control" type="text" id="knev" name="knev" value="<?= $oktato['keresztnev'] ?>" />
                <br />

                <label for="tanszek" class="form-label">Tanszék</label>
                <select class="form-select" id="tanszek" name="tanszek" value="Kérem válasszon">
                    <option value="AUT" <?php if ($oktato['tanszek'] == 'AUT') echo 'selected';?>>AUT</option>
                    <option value="MIT" <?php if ($oktato['tanszek'] == 'MIT') echo 'selected';?>>MIT</option>
                    <option value="HVT" <?php if ($oktato['tanszek'] == 'HVT') echo 'selected';?>>HVT</option>
                    <option value="IIT" <?php if ($oktato['tanszek'] == 'IIT') echo 'selected';?>>IIT</option>
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