<?php

include_once '../function_header.php';
include '../common_server_functions.php';

//print_r($_REQUEST);

$responseCode = 0;

$name = $_REQUEST['name']; if($name == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS, 'Missing Vendor Name', 'newVendorName'));
$existingVendor = objectQuery($conexion, '*', 'vendor', "vendorName = '$name'");
if($existingVendor != null ) die(wrapFormError(ERROR_CODE_DUPLICATE,"The name '$name' is already in use by another vendor [".$existingVendor['vendorName']."] with ID [".$existingVendor['vendorId']."]",'newVendorName'));
$info = $_REQUEST['info'];
$comment = $_REQUEST['comment'];

$tel = $_REQUEST['tel'];
$fax = $_REQUEST['fax'];

$line1 = $_REQUEST['line1'];
$line2 = $_REQUEST['line2'];
$city = $_REQUEST['city'];
$state = $_REQUEST['state'];
$zip = $_REQUEST['zip'];
$box = $_REQUEST['box'];

//die(wrapError(-2,'Feature not ready'));
$vendorId = saveNewVendor($conexion, $name, $info, $comment, $tel, $fax, $line1, $line2, $city, $state, $zip, $box);

echo wrapSubmitResponse(SUCCESS_CODE, $vendorId);
?>
