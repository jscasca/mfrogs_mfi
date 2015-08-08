<?php

include_once '../function_header.php';
include '../common_server_functions.php';

//print_r($_REQUEST);

$responseCode = 0;

$brokerId = $_REQUEST['brokerId']; if($brokerId == 0 || $brokerId == '') die(wrapError(ERROR_CODE_FIVE,'INTERNAL ERROR'));

$pid = $_REQUEST['pid']; if($pid == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Missing PID', 'editBrokerPid'));
$existingBroker = objectQuery($conexion, '*', 'broker', "brokerPid = '$pid' AND brokerId <> $brokerId");
if($existingBroker != null ) die(wrapFormError(ERROR_CODE_DUPLICATE,"The PID '$pid' is already in use by another broker [".$existingBroker['brokerName']."]",'editBrokerPid'));
$name = $_REQUEST['name']; if($name == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS, 'Missing Broker Name', 'editBrokerName'));
$existingBroker = objectQuery($conexion, '*', 'broker', "brokerName = '$name' AND brokerId <> $brokerId");
if($existingBroker != null ) die(wrapFormError(ERROR_CODE_DUPLICATE,"The name '$name' is already in use by another broker [".$existingBroker['brokerName']."] with PID [".$existingBroker['brokerPid']."]",'editBrokerName'));
$contact = $_REQUEST['contact'];
$tax = $_REQUEST['tax'];
$tel = $_REQUEST['tel'];
$fax = $_REQUEST['fax'];
$radio = $_REQUEST['radio'];
$mobile = $_REQUEST['mobile'];
$carrier = $_REQUEST['carrier'];
$email = $_REQUEST['email'];
$line1 = $_REQUEST['line1'];
$line2 = $_REQUEST['line2'];
$city = $_REQUEST['city'];
$state = $_REQUEST['state'];
$zip = $_REQUEST['zip'];
$box = $_REQUEST['box'];
$term = $_REQUEST['term']; if($term == 0) die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS, 'Missing Broker Terms', 'termId'));
$icc = $_REQUEST['icc'];
$wc = $_REQUEST['inswc']; if($wc == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS, 'Missing Insurance WC information', 'brokerInsWc'));
$wcexp = $_REQUEST['wcexp']; if($wcexp == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS, 'Missing Expirantion Date', 'brokerWcExpire'));
$inslb = $_REQUEST['inslb']; if($inslb == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS, 'Missing Insurance Liability', 'brokerInsLiability'));
$lbexp = $_REQUEST['lbexp']; if($lbexp == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS, 'Missing Expiration Date', 'brokerLbExpire'));
$genln = $_REQUEST['genln'];
$glexp = $_REQUEST['glexp'];
$start = $_REQUEST['start'];
$percentage = $_REQUEST['percentage']; if($percentage == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS, 'Missing Broker Percentage', 'brokerPercentage'));
$gender = $_REQUEST['gender']; if($gender == "0") die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS, 'Missing Broker Gender', 'gender'));
$ethnicId = $_REQUEST['ethnic']; if($ethnicId == "0") die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS, 'Missing Broker Ethnicity', 'ethnicId'));

//die(wrapError(-2,'Feature not ready'));
$brokerId = saveEditBroker($conexion, $brokerId, $pid, $name, $contact, $tax, $tel, $fax, $radio, $mobile, $carrier, $email, $icc, $wc, 
	$wcexp, $inslb, $lbexp, $genln, $glexp, $start, $percentage, $term, $gender, $ethnicId, $line1, $line2, $city, $state, $zip, $box);

echo wrapSubmitResponse(SUCCESS_CODE, $brokerId);
?>
