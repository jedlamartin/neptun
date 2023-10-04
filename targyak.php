<?php
session_start();
if (isset($_SESSION['neptun'])) :

    include('csatlakozas.php');
    $mysqli = csatlakozas();


    if (isset($_POST['submit'])) {
        // echo 'asd';
        if ($_POST['tkod'] && $_POST['nev'] && $_POST['tanszek']) {
            $kod = mysqli_real_escape_string($mysqli, $_POST['tkod']);
            $nev = mysqli_real_escape_string($mysqli, $_POST['nev']);
            $kepzes = mysqli_real_escape_string($mysqli, $_POST['tanszek']);

            $query = 'SELECT * FROM targy WHERE kod="' . $kod . '";';
            $result = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));

            if (mysqli_num_rows($result) == 0) {
                $query = "INSERT INTO targy (kod, megnevezes, tanszek) VALUES ('" . $kod . "','" . $nev . "','" . $kepzes . "');";
                mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
                mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
            } else {
                $_SESSION['message'] = 'Válassszon másik tárgykódot!';
            }
        } else {
            $_SESSION['message'] = "Minden mező kitöltése kötelező!";
        }
    }
    $szempont_query = '';
    if (isset($_GET['szempont']) && isset($_GET['rendezes'])) {


        $szempont = mysqli_real_escape_string($mysqli, $_GET['szempont']);
        //$rendezes = mysqli_real_escape_string($mysqli, $_GET['rendezes']);
        if ($_GET['rendezes'] == 'DESC') {
            $rendezes = 'DESC';
        } else {
            $rendezes = 'ASC';
        }

        if ($szempont == 'targykod') $szempont_query = ' ORDER BY targy.kod ' . $rendezes . ';';
        else if ($szempont == 'tanszek') $szempont_query = ' ORDER BY targy.tanszek ' . $rendezes . ';';
        else $szempont_query = ' ORDER BY targy.megnevezes ' . $rendezes . ';';
    }
?>
    <html>

    <head>
        <link rel="stylesheet" href="css/bootstrap.min.<?= $_COOKIE['stilus'] ?>.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
        <link rel="stylesheet" href="css/style.css">
        <title>Tantárgyak</title>
    </head>

    <body>
        <div class="container">

            <?php
            include('fejlec.php');
            ?>
            <h1>Tárgyak</h1>

            <form method="post">
                <div class="card border-secondary mb-3" style="width: 60%">
                    <div class="card-body">

                        <label for="keresettszoveg" class="form-label">Keresés</label>

                        <div style="display: flex; flex-direction: row;">
                            <input class="form-control" type="text" id="keresettszoveg" <?php if (isset($_POST['kereses'])) {
                                                                                            echo 'value="' . $_POST['keresettszoveg'] . '"';
                                                                                        } ?> name="keresettszoveg" style="margin-right: 10px " />
                            <input class="btn btn-primary" type="submit" name="kereses" value="Küldés" />
                        </div>

                    </div>
                </div>
            </form>

            <table class="table table-hover">
                <tr>
                    <?php if (!isset($_GET['rendezes']) || !isset($_GET['szempont']) || $szempont != 'targykod' || ($szempont == 'targykod' && $rendezes == 'DESC')) : ?>
                        <th><a style="color: inherit; text-decoration: none;" href="targyak.php?szempont=targykod&rendezes=ASC">Tárgykód</a></th>

                    <?php else : ?>
                        <th><a style="color: inherit; text-decoration: none;" href="targyak.php?szempont=targykod&rendezes=DESC">Tárgykód</a></th>

                    <?php endif; ?>

                    <?php if (!isset($_GET['rendezes']) || !isset($_GET['szempont']) || $szempont != 'nev' || ($szempont == 'nev' && $rendezes == 'DESC')) : ?>
                        <th><a style="color: inherit; text-decoration: none;" href="targyak.php?szempont=nev&rendezes=ASC">Megnevezés</a></th>

                    <?php else : ?>
                        <th><a style="color: inherit; text-decoration: none;" href="targyak.php?szempont=nev&rendezes=DESC">Tanszék</a></th>

                    <?php endif; ?>

                    <?php if (!isset($_GET['rendezes']) || !isset($_GET['szempont']) || $szempont != 'tanszek' || ($szempont == 'tanszek' && $rendezes == 'DESC')) : ?>
                        <th><a style="color: inherit; text-decoration: none;" href="targyak.php?szempont=tanszek&rendezes=ASC">Tárgykód</a></th>

                    <?php else : ?>
                        <th><a style="color: inherit; text-decoration: none;" href="targyak.php?szempont=tanszek&rendezes=DESC">Tárgykód</a></th>

                    <?php endif; ?>


                    <?php if ($_SESSION['priv'] == 'admin' || $_SESSION['priv'] == 'targyadmin') : ?>
                        <th>Szerkesztés</th>
                        <!--<td>Törlés</td>-->
                    <?php endif; ?>
                </tr>

                <?php
                $query = "SELECT * FROM targy";
                if (isset($_POST['kereses'])) {
                    $query = $query . ' WHERE targy.megnevezes LIKE "%' . mysqli_real_escape_string($mysqli, $_POST['keresettszoveg']) . '%"';
                }
                $query .= $szempont_query;
                $result = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
                while ($row = mysqli_fetch_array($result)) :
                ?>
                    <tr>
                        <td><?= $row['kod'] ?></td>
                        <td><?= $row['megnevezes'] ?></td>
                        <td><?= $row['tanszek'] ?></td>
                        <?php if ($_SESSION['priv'] == 'admin' || $_SESSION['priv'] == 'targyadmin') : ?>
                            <td><a href="targy-szerkeszt.php?targyid=<?= $row['id'] ?>">Szerkesztés</a></td>
                            <!--<td><a href="deletetargy.php?targyid=<?= $row['id'] ?>">Törlés</a></td>-->
                        <?php endif; ?>


                    </tr>
                <?php endwhile; ?>
            </table>
            <?php if ($_SESSION['priv'] == 'admin' || $_SESSION['priv'] == 'targyadmin') : ?>
                <form method="post">


                    <div class="card border-secondary mb-3">
                        <div class="card-header">Új tárgy</div>
                        <div class="card-body">

                            <label for="tkod" class="form-label">Tárgykód</label>
                            <input class="form-control" type="text" id="tkod" name="tkod" />
                            <br />

                            <label for="nev" class="form-label">Megnevezés</label>
                            <input class="form-control" type="text" id="nev" name="nev" />
                            <br />

                            <label for="tanszek" class="form-label">Tanszék</label>
                            <select class="form-select" id="tanszek" name="tanszek" value="Kérem válasszon">
                                <option value="AUT">AUT</option>
                                <option value="MIT">MIT</option>
                                <option value="HVT">HVT</option>
                                <option value="IIT">IIT</option>
                            </select>

                        </div>

                        <div class="card-header"><input class="btn btn-primary" type="submit" name="submit" value="Küldés" /></div>
                    </div>

                </form>
            <?php endif; ?>

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