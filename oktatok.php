<?php
session_start();

if (isset($_SESSION['priv']) && ($_SESSION['priv'] == 'admin' || $_SESSION['priv'] == 'targyadmin')) :

    include('csatlakozas.php');
    $mysqli = csatlakozas();


    if (isset($_POST['submit'])) {
        if ($_POST['fnev'] && $_POST['jelszo'] && $_POST['vnev'] && $_POST['knev'] && $_POST['tanszek'] && ($_POST['neptun'] || $_POST['gen'])) {

            if (isset($_POST['gen'])) {
                $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charlengths = strlen($characters);
                $neptunkod = '';
                for ($i = 0; $i < 6; $i++) {
                    $neptunkod .= $characters[random_int(0, $charlengths - 1)];
                }
            } else {
                $neptunkod = mysqli_real_escape_string($mysqli, $_POST['neptun']);
            }


            $fnev = mysqli_real_escape_string($mysqli, $_POST['fnev']);
            $jelszo = mysqli_real_escape_string($mysqli, $_POST['jelszo']);
            $vnev = mysqli_real_escape_string($mysqli, $_POST['vnev']);
            $knev = mysqli_real_escape_string($mysqli, $_POST['knev']);
            $tanszek = mysqli_real_escape_string($mysqli, $_POST['tanszek']);

            $query = 'SELECT * FROM felhasznalok WHERE felhasznalonev="' . $fnev . '";';
            $result = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));

            if (mysqli_num_rows($result) == 0) {
                $query = "INSERT INTO felhasznalok (neptun, felhasznalonev, jelszo, priv) VALUES ('" . $neptunkod . "','" . $fnev . "','" . $jelszo . "','oktato');";
                mysqli_query($mysqli, $query);


                $query = "INSERT INTO oktatok (neptun, vezeteknev, keresztnev, tanszek) VALUES ('" . $neptunkod . "','" . $vnev . "','" . $knev . "','" . $tanszek . "');";
                mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
            } else {
                $_SESSION['message'] = 'Válassszon másik felhasználónevet!';
            }
        } else {
            $_SESSION['message'] = 'Minden mező kitöltése kötelező!';
        }
    }

    $szempont_query = '';

    if (isset($_GET['szempont']) && isset($_GET['rendezes'])) {


        $szempont = mysqli_real_escape_string($mysqli, $_GET['szempont']);
        if ($_GET['rendezes'] == 'DESC') {
            $rendezes = 'DESC';
        } else {
            $rendezes = 'ASC';
        }
        if ($szempont == 'neptun') $szempont_query = ' ORDER BY oktatok.neptun ' . $rendezes . ';';
        else if ($szempont == 'tanszek') $szempont_query = ' ORDER BY oktatok.tanszek ' . $rendezes . ';';
        else $szempont_query = ' ORDER BY oktatok.vezeteknev ' . $rendezes . ';';
    }

?>

    <html>

    <head>
        <link rel="stylesheet" href="css/bootstrap.min.<?= $_COOKIE['stilus'] ?>.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
        <link rel="stylesheet" href="css/style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>Hallgatók</title>
    </head>

    <body>
        <div class="container">
            <?php
            include('fejlec.php');
            ?>

            <h1>Oktatók</h1>
            <?php
            $mysqli = csatlakozas();
            ?>
            <table class="table table-hover">
                <tr>

                <tr>
                    <?php if (!isset($_GET['rendezes']) || !isset($_GET['szempont']) || $szempont != 'neptun' || ($szempont == 'neptun' && $rendezes == 'DESC')) : ?>
                        <th><a style="color: inherit; text-decoration: none;" href="oktatok.php?szempont=neptun&rendezes=ASC">Neptun kód</a></th>

                    <?php else : ?>
                        <th><a style="color: inherit; text-decoration: none;" href="oktatok.php?szempont=neptun&rendezes=DESC">Neptun kód</a></th>

                    <?php endif; ?>

                    <?php if (!isset($_GET['rendezes']) || !isset($_GET['szempont']) || $szempont != 'nev' || ($szempont == 'nev' && $rendezes == 'DESC')) : ?>
                        <th><a style="color: inherit; text-decoration: none;" href="oktatok.php?szempont=nev&rendezes=ASC">Név</a></th>

                    <?php else : ?>
                        <th><a style="color: inherit; text-decoration: none;" href="oktatok.php?szempont=nev&rendezes=DESC">Név</a></th>

                    <?php endif; ?>

                    <?php if (!isset($_GET['rendezes']) || !isset($_GET['szempont']) || $szempont != 'tanszek' || ($szempont == 'tanszek' && $rendezes == 'DESC')) : ?>
                        <th><a style="color: inherit; text-decoration: none;" href="oktatok.php?szempont=tanszek&rendezes=ASC">Tanszék</a></th>

                    <?php else : ?>
                        <th><a style="color: inherit; text-decoration: none;" href="oktatok.php?szempont=tanszek&rendezes=DESC">Tanszék</a></th>

                    <?php endif; ?>



                    <?php if ($_SESSION['priv'] == 'admin') : ?>
                        <th>Szerkesztés</th>
                    <?php endif; ?>
                    <!--<th>Törlés</th>-->
                </tr>

                <?php
                $query = 'SELECT id, neptun, concat(vezeteknev," ",keresztnev) AS nev, tanszek  FROM oktatok';
                $query .= $szempont_query;
                $result = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
                while ($row = mysqli_fetch_array($result)) :
                ?>
                    <tr>
                        <td><?= $row['neptun'] ?></td>
                        <td><?= $row['nev'] ?></td>
                        <td><?= $row['tanszek'] ?></td>
                        <?php if ($_SESSION['priv'] == 'admin') : ?>

                            <td><a href="oktato-szerkeszt.php?oktatoid=<?= $row['id'] ?>">Szerkesztés</a></td>
                            <!--<td><a href="deleteoktato.php?oktatoid=<?= $row['id'] ?>">Törlés</a></td>-->
                        <?php endif; ?>

                    </tr>
                <?php endwhile; ?>
            </table>
            <?php if ($_SESSION['priv'] == 'admin') : ?>
                <form method="post">

                    <div class="card border-secondary mb-3">
                        <div class="card-header">Új oktató</div>

                        <div class="card-body">
                            <label for="fnev" class="form-label">Felhasználónév</label>
                            <input class="form-control" type="text" id="fnev" name="fnev" />
                            <br />


                            <label for="jelszo" class="form-label">Jelszó</label>
                            <input class="form-control" type="password" id="jelszo" name="jelszo" />
                            <br />


                            <label for="neptun" class="form-label">Neptun kód</label>
                            <input class="form-control" type="text" id="neptun" name="neptun" maxlength="6" />

                            <label for="gen" class="form-label">
                                <input class="form-check-input" type="checkbox" name="gen" value="gen" />
                                Generálás
                            </label>
                            <br />

                            <label for="vnev" class="form-label">Vezetéknév</label>
                            <input class="form-control" type="text" id="vnev" name="vnev" />
                            <br />

                            <label for="knev" class="form-label">Keresztnév</label>
                            <input class="form-control" type="text" id="knev" name="knev" />
                            <br />

                            <label for="tanszek" class="form-label">Tanszék</label>
                            <select class="form-select" id="tanszek" name="tanszek" value="Kérem válasszon">
                                <option value="AUT">AUT</option>
                                <option value="MIT">MIT</option>
                                <option value="HVT">HVT</option>
                                <option value="IIT">IIT</option>
                            </select>
                            <br />
                        </div>
                        <div class="card-header"><input class="btn btn-primary" type="submit" name="submit" value="Küldés" /></div>
                    </div>
                </form>
            <?php endif; ?>

            <?php mysqli_close($mysqli); ?>
        </div>

        <?php
        if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            unset($_SESSION['message']);
        }
        ?>
    </body>

    </html>
<?php endif; ?>