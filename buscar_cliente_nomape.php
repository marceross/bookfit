<?php
include("conex.php");
include("local_controla.php");
//$connect = mysqli_connect("localhost", "root", "", "ajax_search");
$output = '';
if(isset($_POST["query"]))
{
	$search = mysqli_real_escape_string($mysqli, $_POST["query"]);
	$query = "
	SELECT * FROM registrados 
	WHERE nombre LIKE '%".$search."%'
	OR apellido LIKE '%".$search."%' 
	
	";
}
else
{
	$query = "";
}
$result = mysqli_query($mysqli, $query);
if(mysqli_num_rows($result) > 0)
{
	$output .= '<div class="table-responsive">
					<table class="table table bordered">
						<tr>
							<th>Dni</th>
							<th>nombre</th>
							<th>apellido</th>
						</tr>';
	while($row = mysqli_fetch_array($result))
	{
		$output .= '
			<tr>
				<td>'.$row["dni"].'</td>
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