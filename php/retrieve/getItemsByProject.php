<?
include_once '../function_header.php';
if(isset($_GET['projectId'])) {
	
	$queryItems ="
		SELECT
			*
		FROM
			item
			LEFT JOIN material USING (materialId)
		WHERE
			projectId = ".$_GET['projectId']."
	";
	$result = mysql_query($queryItems,$conexion);
	$jsondata = array();
	while($row = mysql_fetch_assoc($result)){
		$jsondata[] = $row;
	}
	
	echo json_encode($jsondata);
}

mysql_close();


?>
