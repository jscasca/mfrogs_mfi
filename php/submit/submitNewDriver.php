<?php

include_once '../function_header.php';
include '../common_server_functions.php';

$broker = $_REQUEST['broker']; if($broker == 0 || $broker == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Please select a broker','brokerId'));
$first = $_REQUEST['first']; if($first == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Please type the driver first name','firstName'));
$last = $_REQUEST['last']; if($last == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Please type the driver last name','lastName'));

$existingDriver = objectQuery($conexion, '*', 'driver', "driverFirstName like '$first' AND driverLastName like '$last' AND brokerId = $broker");
if($existingDriver != null ) die(wrapFormError(ERROR_CODE_DUPLICATE,"The driver name '$first $last' already exists with ID [".$existingDriver['driverId']."]",'firstName'));

$ssn = $_REQUEST['ssn']; if($ssn == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Please type the driver SSN','ssn'));
$tel = $_REQUEST['tel'];
$mobile = $_REQUEST['mobile'];
$carrier = $_REQUEST['carrier'];
$email = $_REQUEST['email'];
$term = $_REQUEST['term']; if($term == 0) die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS, 'Missing Driver Terms', 'termId'));
$start = $_REQUEST['start'];
$percentage = $_REQUEST['percentage']; if($percentage == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS, 'Missing Driver Percentage', 'percentage'));
$gender = $_REQUEST['gender']; if($gender == "0") die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS, 'Missing Driver Gender', 'gender'));
$ethnicId = $_REQUEST['ethnic']; if($ethnicId == "0") die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS, 'Missing Driver Ethnicity', 'ethnicId'));
$driverClass = $_REQUEST['class']; if($driverClass == "0") die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS, 'Missing Driver Class', 'driverClass'));

$line1 = $_REQUEST['line1'];
$line2 = $_REQUEST['line2'];
$city = $_REQUEST['city'];
$state = $_REQUEST['state'];
$zip = $_REQUEST['zip'];
$box = $_REQUEST['box'];

//die(wrapError(-2,'Feature not ready'));
$driverId = saveNewDriver($conexion, $broker, $first, $last, $ssn, $tel, $mobile, $carrier, $email, $start, $percentage, $term,
	$gender, $ethnicId, $driverClass, $line1, $line2, $city, $state, $zip, $box);

echo wrapSubmitResponse(SUCCESS_CODE, $truckId);
?>
