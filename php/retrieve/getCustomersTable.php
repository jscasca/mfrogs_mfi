<?
include_once '../function_header.php';
include_once '../datapack-functions/datapack.php';

$dataTable = getCustomersTable($conexion, $_GET);
$tableString =  "<table class='listing form' cellpadding='0' cellspacing='0' ><tr>";
for($k = 1; $k < sizeOf($dataTable[0]); $k++) {
	$tableString.="<th>".$dataTable[0][$k]."</th>";
}
$tableString.="</tr>";
for($i = 1; $i < sizeOf($dataTable); $i++) {
	$tableString.="<tr customerId='".$dataTable[$i][0]."' id='customer".$dataTable[$i][0]."' class='doubleClickable'>";
	for($j = 1; $j < sizeOf($dataTable[$i]); $j++) {
		$tableString.='<td align="right">'.$dataTable[$i][$j].'</td>';
	}
	$tableString.="</tr>";
}
$jsondata['objectId'] = 'customersTable';
$jsondata['table'] = $tableString;
echo json_encode($jsondata);


mysql_close();
?>
