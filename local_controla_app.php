<?php
session_name("app_reservas"); // AGREGAR session_name('app_sistema'); a los otros archivos, los que son del sistema anterior
session_start();
date_default_timezone_set('America/Argentina/Cordoba');
/*
------------------------------------------------------------------------------------------------------------------------
Controla.php
Verifica que el usuario este autenticado, y no haga mas de 5 minutos que no realiza ninguna peticion.
------------------------------------------------------------------------------------------------------------------------
*/
   $existe=isset($_SESSION['autentificado']);
   if($existe)
   {
   		if($_SESSION["autentificado"]!="SI")
    	{//Verifica si el usuario esta autenticado                           
      	 	//session_destroy();
			//echo $_SESSION["autentificado"];
              header("Location: login.html");
              exit();
	    }     
		
	}
	else
	{		  
		$_SESSION['autentificado']="NO";
        header("Location: login.html");
        exit();
    }
?>