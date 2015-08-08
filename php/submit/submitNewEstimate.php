<?php

include_once '../function_header.php';
include '../common_server_functions.php';

//print_r($_REQUEST);

$responseCode = 0;

$customer = $_REQUEST['customer']; if($customer == '0' || $customer == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS, 'Missing Customer', 'newEstimateCustomer'));

$name = $_REQUEST['name']; if($name == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS, 'Missing Estimate Name', 'newEstimateName'));
$existingEstimate = objectQuery($conexion, '*', 'fakeproject', "customerId = '$customer' AND fakeprojectName = '$name'");
if($existingEstimate != null ) die(wrapFormError(ERROR_CODE_DUPLICATE,"The name '$name' is already in use by another estimate [".$existingEstimate['fakeprojectName']."] with ID [".$existingEstimate['fakeprojectIdId']."]",'newEstimateName'));


$line1 = $_REQUEST['line1'];
$line2 = $_REQUEST['line2'];
$city = $_REQUEST['city'];
$state = $_REQUEST['state'];
$zip = $_REQUEST['zip'];
$box = $_REQUEST['box'];

//die(wrapError(-2,'Feature not ready'));
$estimateId = saveNewEstimate($conexion, $name, $customer, $line1, $line2, $city, $state, $zip, $box);

echo wrapSubmitResponse(SUCCESS_CODE, $estimateId);
?>
