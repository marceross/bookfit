<?php
//$connect = mysqli_connect("localhost", "root", "", "ajax_search");
include("conex.php");
include("local_controla.php");
$output = '';
if(isset($_GET["q"]))
{
	//$search = mysqli_real_escape_string($connect, $_POST["query"]);
	$query = "
	SELECT * FROM registrados 
	WHERE nombre LIKE '%".$_GET["q"]."%'
	OR apellido LIKE '%".$_GET["q"]."%' 
	
	";
}
else
{
	$query = "";
}
$result = mysqli_query($mysqli, $query);
if(mysqli_num_rows($result) > 0)
{
	$output .= '<div class="">
					<table class="table-primary table-hover">
					    <thead>
						<tr>
                            <th>dni</th>
							<th>nombre</th>
							<th>apellido</th>
						</tr>
						</thead>';
	while($row = mysqli_fetch_array($result))
	{
		$output .= '
			<tr>
                <td><a href="app_vercliente2.php?id='.$row["dni"].'">'.$row["dni"].'</td>
				<td>'.$row["nombre"].'</td>
				<td>'.$row["apellido"].'</td>
			</tr>
		';
	}
	echo $output;
}
else
{
	echo 'Data Not Found';
}
?>