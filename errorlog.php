<?
include ("conex.php");
include("local_controla.php");
//get content of textfile
//$filename = "error_log";
//$content = file($filename);

//$handle = fopen("/home/rasmus/file.txt", "r");
//$handle = fopen("error_log", "r");


//Forcing a download using readfile()
/*$file = 'error_log';

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
}*/

$file = 'error_log';

if (file_exists($file)) {
    readfile($file);
    exit;
}

?>


<!DOCTYPE html>
<html lang="es">

<head>
<title>Lokales</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">


<div>
<?// echo $content; ?>
<?// echo $handle; ?>
<? echo $file;?>
</div>

</body>
</html>




