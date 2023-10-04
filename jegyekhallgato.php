<?php
if ($_SESSION['priv'] == 'hallgato') :

    include('csatlakozas.php');
    $mysqli = csatlakozas();

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
        <div class='container'>
            <?php
            include('fejlec.php');
            ?>
            <h1>Jegyek</h1>

            <table class="table table-hover">

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
                </tr>

                <?php
                $query = 'SELECT concat(hallgatok.vezeteknev," ",hallgatok.keresztnev) AS hnev, concat(oktatok.vezeteknev," ",oktatok.keresztnev) AS onev, jegy, datum, targy.megnevezes AS targy FROM jegy INNER JOIN hallgatok ON hallgatok.id=jegy.hallgato_id INNER JOIN oktatok ON oktatok.id=jegy.oktato_id INNER JOIN targy ON targy.id=jegy.targy_id WHERE hallgatok.neptun="' . mysqli_real_escape_string($mysqli, $_SESSION['neptun']).'"';
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
                    </tr>
                <?php endwhile; ?>
            </table>


            <?php mysqli_close($mysqli); ?>
        </div>
    </body>

    </html>
<?php endif; ?>