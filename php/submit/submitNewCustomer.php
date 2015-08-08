<?php

include_once '../function_header.php';
include '../common_server_functions.php';

//print_r($_REQUEST);

$responseCode = 0;

$name = $_REQUEST['name']; if($name == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS, 'Missing Customer Name', 'newCustomerName'));
$existingCustomer = objectQuery($conexion, '*', 'customer', "customerName = '$name'");
if($existingCustomer != null ) die(wrapFormError(ERROR_CODE_DUPLICATE,"The name '$name' is already in use by another customer [".$existingCustomer['customerName']."] with ID [".$existingCustomer['customerId']."]",'newCustomerName'));
$term = $_REQUEST['term']; if($term == '0') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS, 'Missing Customer Pay Terms', 'newCustomerTerm'));
$web = $_REQUEST['web'];

$tel = $_REQUEST['tel'];
$fax = $_REQUEST['fax'];

$line1 = $_REQUEST['line1'];
$line2 = $_REQUEST['line2'];
$city = $_REQUEST['city'];
$state = $_REQUEST['state'];
$zip = $_REQUEST['zip'];
$box = $_REQUEST['box'];

//die(wrapError(-2,'Feature not ready'));
$customerId = saveNewCustomer($conexion, $name, $tel, $fax, $web, $term, $line1, $line2, $city, $state, $zip, $box);

echo wrapSubmitResponse(SUCCESS_CODE, $customerId);
?>
