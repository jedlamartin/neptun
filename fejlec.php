<?php
if (isset($_POST['end'])) {
    $mysqli=csatlakozas();
    $query = sprintf("UPDATE felhasznalok SET felhasznalok.stilus='%s' WHERE felhasznalok.neptun='%s'", mysqli_real_escape_string($mysqli, $_COOKIE['stilus']), $_SESSION['neptun']);
    mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
    mysqli_close($mysqli);
    session_destroy();
    header("Location: index.php");
    exit;
}
if (isset($_POST['sotet'])) {
    setcookie('stilus', 'sotet');
    header("Refresh:0");
} else if (isset($_POST['vilagos'])) {
    setcookie('stilus', 'vilagos');
    header("Refresh:0");
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Neptun</a>

        <ul class="navbar-nav me-auto">


            <?php if (isset($_SESSION['priv']) && ($_SESSION['priv'] == 'admin' || $_SESSION['priv'] == 'oktato' || $_SESSION['priv'] == 'targyadmin')) : ?>
                <li class="nav-item">
                    <a class="nav-link" href="hallgatok.php">Hallgatók</a>
                </li>
            <?php endif; ?>

            <?php if (isset($_SESSION['priv']) && ($_SESSION['priv'] == 'admin' || $_SESSION['priv'] == 'targyadmin')) : ?>
                <li class="nav-item">
                    <a class="nav-link" href="oktatok.php">Oktatók</a>
                </li>
            <?php endif; ?>

            <?php if (isset($_SESSION['priv'])) : ?>
                <li class="nav-item">
                    <a class="nav-link" href="targyak.php">Tantárgyak</a>
                </li>


                <li class="nav-item">
                    <a class="nav-link" href="jegyek.php">Jegyek</a>
                </li>
            <?php endif; ?>

        </ul>

        <form method="post" style="margin-bottom: 0; margin-right: 75px;">
            <?php if ($_COOKIE['stilus'] == 'vilagos') : ?>
                <input class="nav-link fas" type="submit" value="&#xf186;" name="sotet" style="background: none; border: none; vertical-align: middle; color: white;">

            <?php elseif ($_COOKIE['stilus'] == 'sotet') : ?>
                <input class="nav-link fas" type="submit" value="&#xf185;" name="vilagos" style="background: none; border: none; vertical-align: middle; color: white;">
            <?php endif; ?>

        </form>




        <?php if (isset($_SESSION['neptun'])) : ?>
            <form method="post" style="margin-bottom: 0;">
                <input class="nav-link" type="submit" value="Kijelentkezés" name="end" style="background: none; border: none; vertical-align: middle; color: white;">
            </form>

        <?php else : ?>
            <a class="nav-link my-2 my-sm-0" href="bejelentkezes.php" style="color: white;">Bejelentkezés</a>
        <?php endif; ?>
    </div>
</nav>