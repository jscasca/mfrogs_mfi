<?php

include_once '../function_header.php';
include '../common_server_functions.php';

//print_r($_REQUEST);

$responseCode = 0;

$date = $_REQUEST['date'];if($date == '0' || $date == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Please type a valid date','newFuelLoadDate'));

$broker = $_REQUEST['broker']; if($broker == '0' || $broker == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS, 'Missing Broker', 'newFuelLoadBroker'));
$truck = $_REQUEST['truck']; if($truck == '0' || $truck == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS, 'Missing Truck', 'newFuelLoadTruck'));
$comment = $_REQUEST['driver'];if($comment == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Missing Driver','newFuelLoadDriver'));
$start = $_REQUEST['start'];if($start == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Missing Start Measure','newFuelLoadStart'));
$finish = $_REQUEST['finish'];if($finish == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Missing Finish Measure','newFuelLoadFinish'));
if($start > $finish || $start < 0) die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Start and Finishing values are not valid','newFuelLoadFinish'));
$registered = $_REQUEST['registered'];
$miles = $_REQUEST['miles'];if($miles == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Missing Miles Measure','newFuelLoadMileage'));

//die(wrapError(-2,'Feature not ready'));
$fuel = saveNewFuelLoad($conexion, $broker, $truck, $date, $comment, $start, $finish, $registered, $miles);

echo wrapSubmitResponse(SUCCESS_CODE, $vendorId);
?>
