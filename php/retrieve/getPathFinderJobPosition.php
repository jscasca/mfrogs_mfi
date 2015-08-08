<?
include_once '../function_header.php';

if(isset($_GET['projectId']))
{
	if($_GET['pathType'] == 0) {
		$queryPosition = "SELECT * FROM project JOIN address USING (addressId) WHERE projectId = ".$_GET['projectId'];
	} else {
		$queryPosition = "SELECT * FROM fakeproject JOIN address USING (addressId) WHERE fakeprojectId = ".$_GET['projectId'];
	}
	$result = mysql_query($queryPosition,$conexion);
	
	$row = mysql_fetch_assoc($result);
	
	$jsondata['lat'] = $row['addressLat'];
	$jsondata['lng'] = $row['addressLong'];
	
	echo json_encode($jsondata);
}
mysql_close();

?>

