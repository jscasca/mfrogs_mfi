<?php

include_once '../function_header.php';
include '../common_server_functions.php';

//print_r($_REQUEST);

$responseCode = 0;

$vendor = $_REQUEST['vendor']; if($vendor == ''||$vendor=='0') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS, 'Missing Vendor', 'newVendorId'));
$name = $_REQUEST['name']; if($name == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS, 'Missing Supplier Name', 'newSupplierName'));
$existingSupplier = objectQuery($conexion, '*', 'supplier', "supplierName = '$name' AND vendorId = '$vendor'");
if($existingSupplier != null ) die(wrapFormError(ERROR_CODE_DUPLICATE,"The name '$name' is already in use by another supplier [".$existingSupplier['supplierName']."] with ID [".$existingSupplier['supplierId']."]",'newSupplierName'));
$info = $_REQUEST['info'];
$dumptime = $_REQUEST['dumptime'];

$tel = $_REQUEST['tel'];
$fax = $_REQUEST['fax'];

$line1 = $_REQUEST['line1'];
$line2 = $_REQUEST['line2'];
$city = $_REQUEST['city'];
$state = $_REQUEST['state'];
$zip = $_REQUEST['zip'];
$box = $_REQUEST['box'];

//die(wrapError(-2,'Feature not ready'));
$supplierId = saveNewSupplier($conexion, $vendor, $name, $tel, $fax, $info, $dumptime, $line1, $line2, $city, $state, $zip, $box);

echo wrapSubmitResponse(SUCCESS_CODE, $supplierId);
?>
