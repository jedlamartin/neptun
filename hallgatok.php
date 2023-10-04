<?php
session_start();

if (isset($_SESSION['priv']) && ($_SESSION['priv'] == 'oktato' || $_SESSION['priv'] == 'admin' || $_SESSION['priv'] == 'targyadmin')) :

    include('csatlakozas.php');
    $mysqli = csatlakozas();


    if (isset($_POST['submit'])) {
        //echo 'asd';
        if ($_POST['fnev'] && $_POST['jelszo'] && $_POST['vnev'] && $_POST['knev'] && $_POST['kepzes'] && $_POST['ev'] && ($_POST['neptun'] || $_POST['gen'])) {

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
            $kepzes = mysqli_real_escape_string($mysqli, $_POST['kepzes']);
            $ev = mysqli_real_escape_string($mysqli, $_POST['ev']);

            $query = 'SELECT * FROM felhasznalok WHERE felhasznalonev="' . $fnev . '";';
            $result = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));

            if (mysqli_num_rows($result) == 0) {
                $query = "INSERT INTO felhasznalok (neptun, felhasznalonev, jelszo, priv) VALUES ('" . $neptunkod . "','" . $fnev . "','" . $jelszo . "','oktato');";
                mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));

                $query = "INSERT INTO hallgatok (neptun, vezeteknev, keresztnev, kepzes, felvetel_ev) VALUES ('" . $neptunkod . "','" . $vnev . "','" . $knev . "','" . $kepzes . "','" . $ev . "');";
                mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
            }
            else{
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
        if ($szempont == 'neptun') $szempont_query = ' ORDER BY hallgatok.neptun ' . $rendezes . ';';
        else if ($szempont == 'ev') $szempont_query = ' ORDER BY hallgatok.felvetel_ev ' . $rendezes . ';';
        else if ($szempont == 'kepzes') $szempont_query = ' ORDER BY hallgatok.kepzes ' . $rendezes . ';';
        else $szempont_query = ' ORDER BY hallgatok.vezeteknev ' . $rendezes . ';';
    }
?>
    <html>

    <head>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/bootstrap.min.<?= $_COOKIE['stilus'] ?>.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>Hallgatók</title>
    </head>

    <body>
        <div class="container">

            <?php
            include('fejlec.php');
            ?>

            <h1>Hallgatók</h1>

            <table class='table table-hover'>
                <tr>

                    <?php if (!isset($_GET['rendezes']) || !isset($_GET['szempont']) || $szempont != 'neptun' || ($szempont == 'neptun' && $rendezes == 'DESC')) : ?>
                        <th><a style="color: inherit; text-decoration: none;" href="hallgatok.php?szempont=neptun&rendezes=ASC">Neptun kód</a></th>

                    <?php else : ?>
                        <th><a style="color: inherit; text-decoration: none;" href="hallgatok.php?szempont=neptun&rendezes=DESC">Neptun kód</a></th>

                    <?php endif; ?>

                    <?php if (!isset($_GET['rendezes']) || !isset($_GET['szempont']) || $szempont != 'nev' || ($szempont == 'nev' && $rendezes == 'DESC')) : ?>
                        <th><a style="color: inherit; text-decoration: none;" href="hallgatok.php?szempont=nev&rendezes=ASC">Név</a></th>

                    <?php else : ?>
                        <th><a style="color: inherit; text-decoration: none;" href="hallgatok.php?szempont=nev&rendezes=DESC">Név</a></th>

                    <?php endif; ?>

                    <?php if (!isset($_GET['rendezes']) || !isset($_GET['szempont']) || $szempont != 'kepzes' || ($szempont == 'kepzes' && $rendezes == 'DESC')) : ?>
                        <th><a style="color: inherit; text-decoration: none;" href="hallgatok.php?szempont=kepzes&rendezes=ASC">Képzés</a></th>

                    <?php else : ?>
                        <th><a style="color: inherit; text-decoration: none;" style="color: inherit; text-decoration: none;" href="hallgatok.php?szempont=kepzes&rendezes=DESC">Képzés</a></th>

                    <?php endif; ?>

                    <?php if (!isset($_GET['rendezes']) || !isset($_GET['szempont']) || $szempont != 'ev' || ($szempont == 'ev' && $rendezes == 'DESC')) : ?>
                        <th><a style="color: inherit; text-decoration: none;" href="hallgatok.php?szempont=ev&rendezes=ASC">Felvétel éve</a></th>

                    <?php else : ?>
                        <th><a style="color: inherit; text-decoration: none;" href="hallgatok.php?szempont=ev&rendezes=DESC">Felvétel éve</a></th>

                    <?php endif; ?>

                    <?php if ($_SESSION['priv'] == 'admin') : ?>
                        <th>Szerkesztés</th>
                    <?php endif; ?>

                </tr>

                <?php
                $query = "SELECT id, neptun, concat(vezeteknev,' ',keresztnev) AS nev, kepzes, felvetel_ev AS ev FROM hallgatok";
                $query .= $szempont_query;
                $result = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
                while ($row = mysqli_fetch_array($result)) :
                ?>

                    <tr>
                        <td><?= $row['neptun'] ?></td>
                        <td><?= $row['nev'] ?></td>
                        <td><?= $row['kepzes'] ?></td>
                        <td><?= $row['ev'] ?></td>
                        <?php if ($_SESSION['priv'] == 'admin') : ?>
                            <td><a href="hallgato-szerkeszt.php?hallgatoid=<?= $row['id'] ?>">Szerkesztés</a></td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </table>



            <?php if ($_SESSION['priv'] == 'admin') : ?>

                <form method="post">

                    <div class="card border-secondary mb-3">
                        <div class="card-header">Új hallgató</div>
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
                            <input type="text" id="vnev" name="vnev" class="form-control" />

                            <br />


                            <label for="knev" class="form-label">Keresztnév</label>
                            <input type="text" id="knev" name="knev" class="form-control" />

                            <br />

                            <label for="kepzes" class="form-label">Képzés</label>
                            <select class="form-select" id="kepzes" name="kepzes" value="Kérem válasszon">
                                <option value="mérnökinformatika">mérnökinformatika</option>
                                <option value="villamosmérnöki">villamosmérnöki</option>
                            </select>
                            <br />

                            <label for="ev" class="form-label">Felvétel éve</label>
                            <input type="text" id="ev" name="ev" class="form-control" maxlength="4" />

                        </div>
                        <div class="card-header"><input class="btn btn-primary" type="submit" name="submit" value="Küldés" /></div>
                    </div>
                </form>





        </div>
    <?php endif; ?>

    <?php mysqli_close($mysqli); ?>


    <?php
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
    ?>

    </body>

    </html>
<?php endif; ?>