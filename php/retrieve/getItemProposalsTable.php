<?
include_once '../function_header.php';
include_once '../datapack-functions/datapack.php';

$dataTable = getItemsProposalsTable($conexion, $_GET);
$tableString =  "<table class='listing form' cellpadding='0' cellspacing='0' ><tr>";
for($k = 1; $k < sizeOf($dataTable[0]); $k++) {
	$tableString.="<th>".$dataTable[0][$k]."</th>";
}
$tableString.="</tr>";
for($i = 1; $i < sizeOf($dataTable); $i++) {
	$tableString.="<tr itemProposalId='".$dataTable[$i][0]."' id='itemProposal".$dataTable[$i][0]."' class='doubleClickable'>";
	for($j = 1; $j < sizeOf($dataTable[$i]); $j++) {
		$tableString.='<td align="right">'.$dataTable[$i][$j].'</td>';
	}
	$tableString.="</tr>";
}
$jsondata['objectId'] = 'itemProposalsTable';
$jsondata['table'] = $tableString;
echo json_encode($jsondata);


mysql_close();
?>
