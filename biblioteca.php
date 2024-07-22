<?php
	//Funcion que sustituye espacios en blanco con %20
	function sin_espacios($x)
	{
		$cadena="";
		$largo=strlen($x);
		for($i=0; $i<$largo;$i++)
		{
			$caracter=substr($x,$i,1);
			if($caracter==' ')
			{
				$cadena=$cadena.'%20';
			}
			else
			{
				$cadena=$cadena.$caracter;
			}
		}
		return $cadena;
	}
	//Funcion que sustituye espacios con _
	function guion_bajo($x)
	{
		$cadena="";
		$largo=strlen($x);
		for($i=0; $i<$largo;$i++)
		{
			$caracter=substr($x,$i,1);
			if($caracter==' ')
			{
				$cadena=$cadena.'_';
			}
			else
			{
				$cadena=$cadena.$caracter;
			}
		}
		return $cadena;
	}
//Agrega ceros para dar formato
function agregar_ceros($x,$long)
{
		$resultado="";
		for($i=1;$i<=$long-strlen($x);$i++)
	{
		$resultado=$resultado.'0';
	}
	$resultado=$resultado.$x;
	return $resultado;
}

//Da formato latino a una fecha
function formato_latino($x)
{
	$fecha_arg=substr($x,8,2)."/".substr($x,5,2)."/".substr($x,0,4);
	return $fecha_arg;
}

//Reemplaza coma por punto
function comaporpunto($x)
{
	$cadena="";
	for($i=0;$i<=strlen($x);$i++)
	{
		$caracter=substr($x,$i,1);
		if($caracter<>",")
		{
			$cadena=$cadena.$caracter;
		}
		else
		{
			$cadena=$cadena.".";
		}
	}
	return $cadena;
}
//Quita la coma de numeros formateados para poder cargarlos en la base de datos
function quitar_coma($x)
{
	$cadena="";
	for($i=0;$i<=strlen($x);$i++)
	{
		$caracter=substr($x,$i,1);
		if($caracter<>",")
		{
			$cadena=$cadena.$caracter;
		}
	}
	return $cadena;
}

//Funcion que lee la cotizacion de una moneda respecto de otra
function cotizacion($base, $objetivo) {         
        $url = 'fx.sauder.ubc.ca'; 
        $path = '/cgi/fxdata'; 
        $data = 'b='.$base.'&c='.$objetivo.'&rd=1&q=volume&f=plain'; 
         
        $conn = fsockopen($url, 80); 
         
        fputs($conn, "POST ".$path." HTTP/1.1\n"); 
        fputs($conn, "Host: ".$url."\n"); 
        fputs($conn, "Content-type: application/x-www-form-urlencoded\n"); 
          fputs($conn, "Content-length: ". strlen($data)."\n"); 
          fputs($conn, "Connection: close\n\n");  

        $done = fputs($conn, $data); 

        $out = ''; 

        while (!feof($conn)) { 
            $out .= fgets($conn, 128); 
        } 

        fclose($conn); 
         
        return substr($out, 225, 6);
         
    }  
?>