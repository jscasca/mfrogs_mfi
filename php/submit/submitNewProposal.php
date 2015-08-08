<?php

include_once '../function_header.php';
include '../common_server_functions.php';


$project = $_REQUEST['project'];
if($project == 0 || $project == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Please select a project','newItemProjectId'));
$supplier = $_REQUEST['supplier'];
if($supplier == 0 || $supplier == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Please select a supplier','newItemSupplierId'));
$material = $_REQUEST['material'];
if($material == 0 || $material == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Please select a material','newItemMaterialId'));
$fromId = $_REQUEST['fromId'];
$toId = $_REQUEST['toId'];
$fromText = $_REQUEST['fromDisplay'];
$toText = $_REQUEST['toDisplay'];
$materialPrice = $_REQUEST['materialPrice'];
if($materialPrice == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Please select a material price','newItemMaterialPrice'));
$brokerCost = $_REQUEST['brokerCost'];
if($brokerCost == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Please select a broker cost','newItemBrokerCost'));
$customerCost = $_REQUEST['customerCost'];
if($customerCost == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Please select a customer cost','newItemCustomerCost'));
$type = $_REQUEST['type'];
if($type == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Please select a type','type'));
$description = $_REQUEST['description'];

$existing = objectQuery($conexion, '*', 'fakeitem', "fakeprojectId = '$project' AND supplierId = '$supplier' AND materialId = '$material' AND itemType = '$type' AND itemMaterialPrice = '$materialPrice' AND itemBrokerCost = '$brokerCost' AND itemCustomerCost = '$customerCost'");
if($existing != null) die (wrapFormError(ERROR_CODE_DUPLICATE, "The proposal already exists"));

$number = "0";
$last = objectQuery($conexion,'*','fakeitem',"fakeprojectId = '$project' ORDER BY itemNumber desc limit 1");
if($last != null) {
	$number = $last['itemNumber'] + 1;
} else {
	$number = "1";
}

//die(wrapError(-2,'Feature not ready'));
$itemId = saveNewProposal($conexion, $number, $project, $supplier, $material, 0, 0, $fromText, $toText, $materialPrice, $brokerCost, $customerCost, $type, $description);

echo wrapSubmitResponse(SUCCESS_CODE, $itemId);
?>
