<?php

include_once '../function_header.php';

$orderby = "";
$table = $_GET['table'];
$where = (isset($_GET['where'])?$_GET['where']:1);
$condition = (isset($_GET['condition'])?$_GET['condition']:1);
$type = $_GET['type'];
$order = $_GET['order'];
if(isset($_GET['order'])) $orderby = "ORDER BY $order $type";
$id = $_GET['id'];
$values = $_GET['values'];
$objectId = $_GET['objectId'];
$objectName = $_GET['objectName'];

$objectQuery = "
	SELECT
		*
	FROM
		$table
	WHERE
		$where = $condition
	$orderby
	";//echo $objectQuery;
$objects = mysql_query($objectQuery, $conexion);
$jsondata['objectId'] = $objectId;
$jsondata['options'][0] = " --Select $objectName--";
while($row = mysql_fetch_assoc($objects)) {
	$jsondata['options'][$row[$id]] = mapValues($row,$values,'_');
}
echo json_encode($jsondata);
mysql_close();
?>
