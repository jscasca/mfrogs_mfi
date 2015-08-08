<?php

include_once '../function_header.php';

$brokerId = $_GET['brokerId'];
$objectId = $_GET['objectId'];

$driversQuery = "
	SELECT
		*
	FROM
		driver
	WHERE
		brokerId = $brokerId
	ORDER BY
		driverLastName asc
	";
$drivers = mysql_query($driversQuery, $conexion);
$jsondata['objectId'] = $objectId;
$jsondata['options'][0] = "";
while($driver = mysql_fetch_assoc($drivers)) {
	$jsondata['options'][$driver['driverId'] = $driver['driverLastName']." ".$row['driverFirstName'];
}
echo json_encode($jsondata);
mysql_close();
?>
