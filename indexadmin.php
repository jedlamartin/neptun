
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="css/bootstrap.min.<?= $_COOKIE['stilus'] ?>.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Neptun</title>
</head>

<body>
    <div class="container">
        <?php
        include("csatlakozas.php");

        include('fejlec.php');
        ?>
        <h1>Neptun nyilvántartási rendszer</h1>
    </div>
</body>

</html>