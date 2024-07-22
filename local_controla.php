<?
session_name("app_admin");
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
     	 	header("Location: local_inicio.php");
	    }     
		//------------------------------------------   
        else
        {        
            $hora_actual=gettimeofday();//Obtiene la hora actual.
            $hora_entrada=$_SESSION["hora"];//Obtiene la hora de la ultima peticion.
            $diferencia=$hora_actual["sec"]-$hora_entrada;//Calcula la diferencia entre la hora de ingreso y                                                                                       // la hora actual.
             if($diferencia>18000)
             {//Si la diferencia es mayor a 5 horas, el usuario es desconectado y enviado a la pantalla de
                                 //login.
                  session_destroy();
                  header("Location: local_inicio.php");
              }
              else
              {
                  $_SESSION["hora"]=$hora_actual["sec"];//Actualiza la hora de la ultima peticion.
              }           
           }
		   //----------------------------------------------------
	}
	else
	{		  
		$_SESSION['autentificado']="NO";
		header("Location: local_inicio.php");
    }
?>