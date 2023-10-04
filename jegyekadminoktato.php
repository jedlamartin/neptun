<?php
if (isset($_SESSION['neptun']) && ($_SESSION['priv'] == 'admin' || $_SESSION['priv'] == 'oktato' || $_SESSION['priv'] == 'targyadmin')) :

    include('csatlakozas.php');
    $mysqli = csatlakozas();



    if (isset($_POST['add'])) {
        //echo 'asd';
        $hid = mysqli_real_escape_string($mysqli, $_POST['hid']);
        $oid = mysqli_real_escape_string($mysqli, $_POST['oid']);
        $targy = mysqli_real_escape_string($mysqli, $_POST['targy']);
        $jegy = mysqli_real_escape_string($mysqli, $_POST['jegy']);
        $datum = mysqli_real_escape_string($mysqli, $_POST['datum']);
        $query = "INSERT INTO jegy (hallgato_id, oktato_id, targy_id, jegy, datum) VALUES ('" . $hid . "','" . $oid . "','" . $targy . "','" . $jegy . "', '" . $datum . "');";
        mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
    }

    $szempont_query = '';

    if (isset($_GET['szempont']) && isset($_GET['rendezes'])) {


        $szempont = mysqli_real_escape_string($mysqli, $_GET['szempont']);
        if ($_GET['rendezes'] == 'DESC') {
            $rendezes = 'DESC';
        } else {
            $rendezes = 'ASC';
        }
        if ($szempont == 'hallgato') $szempont_query = ' ORDER BY hallgatok.vezeteknev ' . $rendezes . ';';
        else if ($szempont == 'oktato') $szempont_query = ' ORDER BY oktatok.vezeteknev ' . $rendezes . ';';
        else if ($szempont == 'jegy') $szempont_query = ' ORDER BY jegy.jegy ' . $rendezes . ';';
        else if ($szempont == 'targy') $szempont_query = ' ORDER BY targy.megnevezes ' . $rendezes . ';';
        else $szempont_query = ' ORDER BY jegy.datum ' . $rendezes . ';';
    }

?>
    <html>

    <head>
        <link rel="stylesheet" href="css/bootstrap.min.<?= $_COOKIE['stilus'] ?>.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
        <link rel="stylesheet" href="css/style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


        <title>Jegyek</title>
    </head>

    <body>

        <div class="container">
            <?php
            include('fejlec.php');
            ?>
            <h1>Jegyek</h1>
            <table class='table table-hover'>

                <tr>
                    <?php if (!isset($_GET['rendezes']) || !isset($_GET['szempont']) || $szempont != 'hallgato' || ($szempont == 'hallgato' && $rendezes == 'DESC')) : ?>
                        <th><a style="color: inherit; text-decoration: none;" href="jegyek.php?szempont=hallgato&rendezes=ASC">Hallgató neve</a></th>

                    <?php else : ?>
                        <th><a style="color: inherit; text-decoration: none;" href="jegyek.php?szempont=hallgato&rendezes=DESC">Hallgató neve</a></th>

                    <?php endif; ?>

                    <?php if (!isset($_GET['rendezes']) || !isset($_GET['szempont']) || $szempont != 'oktato' || ($szempont == 'oktato' && $rendezes == 'DESC')) : ?>
                        <th><a style="color: inherit; text-decoration: none;" href="jegyek.php?szempont=oktato&rendezes=ASC">Oktató neve</a></th>

                    <?php else : ?>
                        <th><a style="color: inherit; text-decoration: none;" href="jegyek.php?szempont=oktato&rendezes=DESC">Oktató neve</a></th>

                    <?php endif; ?>

                    <?php if (!isset($_GET['rendezes']) || !isset($_GET['szempont']) || $szempont != 'jegy' || ($szempont == 'jegy' && $rendezes == 'DESC')) : ?>
                        <th><a style="color: inherit; text-decoration: none;" href="jegyek.php?szempont=jegy&rendezes=ASC">Jegy</a></th>

                    <?php else : ?>
                        <th><a style="color: inherit; text-decoration: none;" href="jegyek.php?szempont=jegy&rendezes=DESC">Jegy</a></th>

                    <?php endif; ?>

                    <?php if (!isset($_GET['rendezes']) || !isset($_GET['szempont']) || $szempont != 'targy' || ($szempont == 'targy' && $rendezes == 'DESC')) : ?>
                        <th><a style="color: inherit; text-decoration: none;" href="jegyek.php?szempont=targy&rendezes=ASC">Tárgy</a></th>

                    <?php else : ?>
                        <th><a style="color: inherit; text-decoration: none;" href="jegyek.php?szempont=targy&rendezes=DESC">Tárgy</a></th>

                    <?php endif; ?>

                    <?php if (!isset($_GET['rendezes']) || !isset($_GET['szempont']) || $szempont != 'datum' || ($szempont == 'datum' && $rendezes == 'DESC')) : ?>
                        <th><a style="color: inherit; text-decoration: none;" href="jegyek.php?szempont=datum&rendezes=ASC">Dátum</a></th>

                    <?php else : ?>
                        <th><a style="color: inherit; text-decoration: none;" href="jegyek.php?szempont=datum&rendezes=DESC">Dátum</a></th>

                    <?php endif; ?>



                    <?php if ($_SESSION['priv'] == 'admin' || $_SESSION['priv'] == 'oktato') : ?>

                        <th>Szerkesztés</th>
                        <th>Törlés</th>
                    <?php endif; ?>

                </tr>

                <?php
                if ($_SESSION['priv'] == 'admin' || $_SESSION['priv'] == 'targyadmin') {
                    $query = 'SELECT jegy.id AS id, concat(hallgatok.vezeteknev," ",hallgatok.keresztnev) AS hnev, concat(oktatok.vezeteknev," ",oktatok.keresztnev) AS onev, jegy, datum, targy.megnevezes AS targy FROM jegy INNER JOIN hallgatok ON hallgatok.id=jegy.hallgato_id INNER JOIN oktatok ON oktatok.id=jegy.oktato_id INNER JOIN targy ON targy.id=jegy.targy_id';
                } else if ($_SESSION['priv'] == 'oktato') {
                    $query = 'SELECT jegy.id AS id, concat(hallgatok.vezeteknev," ",hallgatok.keresztnev) AS hnev, concat(oktatok.vezeteknev," ",oktatok.keresztnev) AS onev, jegy, datum, targy.megnevezes AS targy FROM jegy INNER JOIN hallgatok ON hallgatok.id=jegy.hallgato_id INNER JOIN oktatok ON oktatok.id=jegy.oktato_id INNER JOIN targy ON targy.id=jegy.targy_id WHERE oktatok.neptun="' . mysqli_real_escape_string($mysqli, $_SESSION['neptun']) . '"';
                }
                $query .= $szempont_query;

                $result = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
                while ($row = mysqli_fetch_array($result)) :
                ?>

                    <tr>
                        <td><?= $row['hnev'] ?></td>
                        <td><?= $row['onev'] ?></td>
                        <td><?= $row['jegy'] ?></td>
                        <td><?= $row['targy'] ?></td>
                        <td><?= $row['datum'] ?></td>
                        <?php if ($_SESSION['priv'] == 'admin' || $_SESSION['priv'] == 'oktato') : ?>
                            <td>
                                <a href="jegy-szerkeszt.php?jegyid=<?= $row['id'] ?>">Szerkesztés</a>
                            </td>
                            <td>
                                <a href="jegy-torol.php?jegyid=<?= $row['id'] ?>">Törlés</a>
                            </td>
                        <?php endif; ?>

                    </tr>
                <?php endwhile; ?>
            </table>

            <?php if ($_SESSION['priv'] == 'admin' || $_SESSION['priv'] == 'oktato') : ?>
                <form method="post">

                    <div class="card border-secondary mb-3">
                        <div class="card-header">Új jegy</div>
                        <div class="card-body">

                            <label for="oid" class="form-label">Oktató</label>


                            <?php if ($_SESSION['priv'] == 'admin') : ?>
                                <select class="form-select" id="oid" name="oid" value="Kérem válasszon">
                                    <?php
                                    $query = "SELECT oktatok.id AS id, concat(oktatok.vezeteknev,' ',oktatok.keresztnev) AS onev FROM oktatok ORDER BY oktatok.vezeteknev ASC";
                                    $result = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
                                    while ($row = mysqli_fetch_array($result)) :
                                    ?>
                                        <option value=<?= $row['id'] ?>><?= $row['onev'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                                <br />
                            <?php elseif ($_SESSION['priv'] == 'oktato') : ?>

                                <br />
                                <?php
                                $query = 'SELECT oktatok.id AS id, concat(oktatok.vezeteknev," ",oktatok.keresztnev) AS onev FROM oktatok WHERE oktatok.neptun="' . mysqli_real_escape_string($mysqli, $_SESSION['neptun']) . '";';
                                $result = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
                                $row = mysqli_fetch_array($result);
                                ?>
                                <?= $row['onev'] ?>
                                <input type="hidden" name="oid" id="oid" value="<?= $row['id'] ?>" />
                                <br />
                                <br />


                            <?php endif; ?>




                            <label for="hid" class="form-label">Hallgató</label>
                            <select class="form-select" id="hid" name="hid" value="Kérem válasszon">
                                <?php
                                $query = "SELECT hallgatok.id AS id, concat(hallgatok.vezeteknev,' ',hallgatok.keresztnev) AS hnev FROM hallgatok ORDER BY hallgatok.vezeteknev ASC";
                                $result = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
                                while ($row = mysqli_fetch_array($result)) :
                                ?>
                                    <option value=<?= $row['id'] ?>><?= $row['hnev'] ?></option>
                                <?php endwhile; ?>
                            </select>
                            <br />

                            <label for="targy" class="form-label">Tantárgy</label>
                            <select class="form-select" id="targy" name="targy" value="Kérem válasszon">
                                <?php
                                $query = "SELECT targy.id AS id, concat(targy.kod,'  - ',targy.megnevezes) AS nev FROM targy ORDER BY targy.megnevezes ASC";
                                $result = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
                                while ($row = mysqli_fetch_array($result)) :
                                ?>
                                    <option value=<?= $row['id'] ?>><?= $row['nev'] ?></option>
                                <?php endwhile; ?>
                            </select>
                            <br />


                            <label for="jegy" class="form-label">Jegy</label>
                            <select class="form-select" id="jegy" name="jegy" value="Kérem válasszon">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                            <br />

                            <label for="datum" class="form-label">Dátum</label>
                            <br />
                            <input class="btn btn-primary" type="date" id="datum" name="datum" value="<?= date('Y-m-d'); ?>" />

                        </div>
                        <div class="card-header"><input class="btn btn-primary" type="submit" name="add" value="Küldés" /></div>
                </form>
        </div>
    <?php endif; ?>

    </div>
    </body>

    </html>
<?php
    mysqli_close($mysqli);
endif;
?>