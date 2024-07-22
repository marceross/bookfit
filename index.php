<?php
include("conex.php");
session_start();

if (empty($_SESSION['visited'])) {
    $stmt = $mysqli->prepare("UPDATE indicador_botones SET clicks = clicks + 1 WHERE cod = 0");
    $stmt->execute();
    $stmt->close();
}

$_SESSION['visited'] = true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>LOKALES TRAINING SPOT</title>
    <link href="css/index.css" rel="stylesheet" type="text/css">
    <link href="https://lokales.com.ar/favico.ico" rel="shortcut icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div style="display: none" id="hideAll">&nbsp;</div>
    <script type="text/javascript">
        document.getElementById("hideAll").style.display = "block";
        window.onload = function() {
            document.getElementById("hideAll").style.display = "none";
        };
    </script>

    <main class="main">
        <div class="logo"><b>L<span>OK</span>A<span>L</span>ES</b></div>
        <div class="logo">
            <span><a href="https://lokales.com.ar/index_procesa.php?cod=1" id="btn-act">SKATE - ACRO - ESCALADA</a></span>
        </div>
        <a href="https://lokales.com.ar/index_procesa.php?cod=5" id="btn-twtr">INSCRIPCION</a>
    </main>

    <footer class="footer">
        <a href="https://lokales.com.ar/index_procesa.php?cod=2" class="footer__item">hola@lokales.com.ar</a>
        <a href="https://lokales.com.ar/index_procesa.php?cod=3" class="fa fa-facebook"></a>
        <a href="https://lokales.com.ar/index_procesa.php?cod=4" class="fa fa-instagram"></a>
    </footer>
</body>
</html>
