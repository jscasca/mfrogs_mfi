<?php

include_once '../function_header.php';
include '../common_server_functions.php';

//print_r($_REQUEST);

$responseCode = 0;

$supplier = $_REQUEST['supplier']; if($supplier == '0' || $supplier == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Please select a supplier',''));
$material = $_REQUEST['material']; if($material == '0' || $material == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Please select a material',''));
$price = $_REQUEST['price']; if($price == '0' || $price == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Please select a price',''));
$info = $_REQUEST['info'];

//die(wrapError(-2,'Feature not ready'));
saveSupplierMaterial($conexion, $supplier, $material, $price, $info);
$materialInfo = mysql_fetch_assoc(mysql_query("SELECT * FROM material WHERE materialId = '$material'", $conexion));
$delImg = createActionIcon(IMG_DELETE,'delete'.$supplier."-".$material,'Delete Price','../submit/deleteSupplierMaterial.php','supplier='.$supplier.'&material='.$material,'delete'," width='22' height='22'");
$newLine = printRow('',array($materialInfo['materialName'],decimalPad($price), date('m/d/Y'),$delImg));

$response['code'] = 0;	
$response['created'] = "";
$response['newLine'] = $newLine;

echo json_encode($response);
?>
