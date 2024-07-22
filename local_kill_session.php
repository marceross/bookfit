<?php
include("conex.php");
//session_start();
include("local_controla.php");		
unset($_SESSION['autentificado']);
unset($_SESSION['dia']);
unset($_SESSION['mes']);
unset($_SESSION['anio']);
unset($_SESSION['id_proveedor']);
header("Location: local_inicio.php");	
?>
<head>
</head>
<body>
</body>
</html>
