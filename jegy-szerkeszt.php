<?php
session_start();

if (isset($_SESSION['priv']) && ($_SESSION['priv'] == 'oktato' || $_SESSION['priv'] == 'admin')) :

    include('csatlakozas.php');
    $mysqli=csatlakozas();


    if (isset($_POST['modosit'])) {
        $id = mysqli_real_escape_string($mysqli, $_POST['jegyid']);
        $oid = mysqli_real_escape_string($mysqli, $_POST['oid']);
        $hid = mysqli_real_escape_string($mysqli, $_POST['hid']);
        $targy = mysqli_real_escape_string($mysqli, $_POST['targy']);
        $jegy = mysqli_real_escape_string($mysqli, $_POST['jegy']);
        $datum = mysqli_real_escape_string($mysqli, $_POST['datum']);



        $query = 'UPDATE jegy SET oktato_id=' . $oid . ', hallgato_id=' . $hid . ', targy_id=' . $targy . ', jegy=' . $jegy . ', datum="' . $datum . '" WHERE id=' . $id . ';';
        mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
        mysqli_close($mysqli);
        header("Location: jegyek.php");
        return;
    }

    if (!isset($_GET['jegyid'])) {
        mysqli_close($mysqli);
        header("Location: jegyek.php");
        return;
    }

    $query = 'SELECT jegy.id AS id FROM jegy INNER JOIN oktatok ON oktatok.id=jegy.oktato_id WHERE oktatok.neptun="' . mysqli_real_escape_string($mysqli, $_SESSION['neptun']) . '" AND jegy.id=' . mysqli_real_escape_string($mysqli, $_GET['jegyid']) . ';';
    $result = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));

    if (mysqli_num_rows($result) == 1 || $_SESSION['priv'] == 'admin') :

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
                <h1>Jegy adatai</h1>

                <?php
                $id = mysqli_real_escape_string($mysqli, $_GET['jegyid']);
                $jegyid = $_GET['jegyid'];
                $query = 'SELECT jegy.id AS id, hallgatok.id AS hid, oktatok.id AS oid, jegy, targy.id AS targyid, jegy.datum AS datum FROM jegy INNER JOIN hallgatok ON hallgatok.id=jegy.hallgato_id INNER JOIN oktatok ON oktatok.id=jegy.oktato_id INNER JOIN targy ON targy.id=jegy.targy_id WHERE jegy.id=' . $jegyid . ';';
                $result = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
                $jegy = mysqli_fetch_array($result);
                ?>

                <form method="post">

                    <input type="hidden" name="jegyid" id="jegyid" value="<?= $id ?>" />


                    <?php if ($_SESSION['priv'] == 'admin') : ?>
                        <label for="oid" class="form-label">Oktató</label>
                        <select class="form-select" id="oid" name="oid" value="Kérem válasszon">
                            <?php
                            $query = "SELECT oktatok.id AS id, concat(oktatok.vezeteknev,' ',oktatok.keresztnev) AS onev FROM oktatok";
                            $result = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
                            while ($row = mysqli_fetch_array($result)) :
                            ?>
                                <option value=<?= $row['id'] ?> <?php if ($jegy['oid'] == $row['id']) echo 'selected'; ?>><?= $row['onev'] ?></option>
                            <?php endwhile; ?>
                        </select>
                        <br />


                    <?php elseif ($_SESSION['priv'] == 'oktato') : ?>
                        <label>Oktató</label>
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
                        $query = "SELECT hallgatok.id AS id, concat(hallgatok.vezeteknev,' ',hallgatok.keresztnev) AS hnev FROM hallgatok";
                        $result = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
                        while ($row = mysqli_fetch_array($result)) :
                        ?>
                            <option value=<?= $row['id'] ?> <?php if ($jegy['hid'] == $row['id']) echo 'selected'; ?>><?= $row['hnev'] ?></option>
                        <?php endwhile; ?>
                    </select>
                    <br />


                    <label for="targy" class="form-label">Tantárgy</label>
                    <select class="form-select" id="targy" name="targy" value="Kérem válasszon">
                        <?php
                        $query = "SELECT targy.id AS id, concat(targy.kod,'  - ',targy.megnevezes) AS nev FROM targy";
                        $result = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
                        while ($row = mysqli_fetch_array($result)) :
                        ?>
                            <option value=<?= $row['id'] ?> <?php if ($jegy['targyid'] == $row['id']) echo 'selected'; ?>><?= $row['nev'] ?></option>
                        <?php endwhile; ?>
                    </select>
                    <br />

                    <label for="jegy" class="form-label">Jegy</label>
                    <select class="form-select" id="jegy" name="jegy" value="Kérem válasszon">
                        <option value="1" <?php if ($jegy['jegy'] == 1) echo 'selected'; ?>>1</option>
                        <option value="2" <?php if ($jegy['jegy'] == 2) echo 'selected'; ?>>2</option>
                        <option value="3" <?php if ($jegy['jegy'] == 3) echo 'selected'; ?>>3</option>
                        <option value="4" <?php if ($jegy['jegy'] == 4) echo 'selected'; ?>>4</option>
                        <option value="5" <?php if ($jegy['jegy'] == 5) echo 'selected'; ?>>5</option>
                    </select>
                    <br />

                    <label for="datum" class="form-label">Dátum</label>
                    <br />
                    <input class="btn btn-primary" type="date" id="datum" name="datum" value="<?= $jegy['datum'] ?>" />
                    <br />
                    <br />

                    <input class="btn btn-success" type="submit" name="modosit" value="Módosítás" />
                    <a class="btn btn-danger" href="jegy-torol.php?jegyid=<?= $id ?>">Törlés</a>
                </form>




            </div>

        </body>

        </html>
    <?php
        mysqli_close($mysqli);
    else :
        $_SESSION['message'] = 'Ehhez nincs jogosultsága!';
        header("Location: jegyek.php");
        return;

    endif; ?>
<?php endif; ?>