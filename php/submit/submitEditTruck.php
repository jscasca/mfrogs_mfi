<?php

include_once '../function_header.php';
include '../common_server_functions.php';

//print_r($_REQUEST);

$responseCode = 0;

$truckId = $_REQUEST['truckId']; if($truckId == 0 || $truckId == '') die(wrapError(ERROR_CODE_FIVE,'INTERNAL ERROR'));

$broker = $_REQUEST['broker']; if($broker == 0 || $broker == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Please select a broker','brokerId'));
$number = $_REQUEST['number']; if($number == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Please type the truck number','truckNumber'));

$existingTruck = objectQuery($conexion, '*', 'truck', "truckNumber = '$number' AND brokerId = '$broker' AND truckId <> '$truckId'");
if($existingTruck != null ) die(wrapFormError(ERROR_CODE_DUPLICATE,"The number '$number' is already in use by another truck with ID [".$existingTruck['truckId']."]",'truckNumber'));

$driver = $_REQUEST['driver'];
$plates = $_REQUEST['plates'];
$info = $_REQUEST['addinfo'];
$brand = $_REQUEST['brand'];
$year = $_REQUEST['year'];
$serial = $_REQUEST['serial'];
$tire = $_REQUEST['tire'];

$features = $_REQUEST['features'];

$line1 = $_REQUEST['line1'];
$line2 = $_REQUEST['line2'];
$city = $_REQUEST['city'];
$state = $_REQUEST['state'];
$zip = $_REQUEST['zip'];
$box = $_REQUEST['box'];

//die(wrapError(-2,'Feature not ready'));
$truckId = saveEditTruck($conexion, $truckId, $broker, $number, $driver, $plates, $info, $brand, $year, $serial, $tire, $line1, $line2, $city, $state, $zip, $box, $features);

echo wrapSubmitResponse(SUCCESS_CODE, $truckId);
?>
