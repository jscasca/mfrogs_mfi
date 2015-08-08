<?php

include_once '../function_header.php';

$objectId = $_REQUEST['objectId'];
$additionalId = $_REQUEST['additionalId'];
$infoNeed = $_REQUEST['type'];
$response['type'] = $infoNeed;
//type = projectInfo | supplierInfo | materialPrice
switch($infoNeed) {
	case 'estimateInfo':
		$estimateInfo = getEstimateInfo($conexion,$objectId);
		$response['displayFrom'] = $estimateInfo['fakeprojectName']." @ ".$estimateInfo['addressLine1'];
		break;
	case 'supplierInfo':
		$supplierInfo = getSupplierInfo($conexion,$objectId);
		$response['displayTo'] = $supplierInfo['supplierName']." @ ".$supplierInfo['addressLine1'];
		break;
	case 'materialPrice':
		//echo "SELECT * FROM suppliermaterial WHERE materialId = '$objectId' AND supplierId = '$additionalId'";
		$materialInfo = mysql_fetch_assoc(mysql_query("SELECT * FROM suppliermaterial WHERE materialId = '$objectId' AND supplierId = '$additionalId'", $conexion));
		$response['materialPrice'] = $materialInfo['supplierMaterialPrice'];
		$response['materialLast'] = to_MDY($materialInfo['supplierMaterialLastModified']);
		break;
}

echo json_encode($response);

?>
