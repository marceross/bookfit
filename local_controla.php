<?php
//session_name("app_admin");
//session_start();
date_default_timezone_set('America/Argentina/Cordoba');


if(isset($_SESSION['autentificado'])){
    
    
    $s_s = $_SESSION['autentificado'];
    
    
    
   
        if($s_s == "SI")
    	{
    	      
    	    /*
    	    
    	    $hora_actual=gettimeofday();//Obtiene la hora actual.
            $hora_entrada=$_SESSION["hora"];//Obtiene la hora de la ultima peticion.
            $diferencia=$hora_actual["sec"]-$hora_entrada;//Calcula la diferencia entre la hora de ingreso y                                               
            
            
            // la hora actual.
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
    	    
    	    */
    	  
     	 	
	    }else{
	        
	          header("Location: local_inicio.php");
	        
	        
	    }  
	    
	    
   
   
}else{
        $_SESSION['autentificado']="NO";
		header("Location: local_inicio.php");
}


  
?>