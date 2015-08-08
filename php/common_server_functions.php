<?php

function wrapError($code, $message) {
	$response['code'] = $code;
	$response['msg'] = $message;
	return json_encode($response);
}

function wrapFormError($code, $message, $focus = '') {
	$response['code'] = $code;
	$response['msg'] = $message;
	$response['focus'] = $focus;
	return json_encode($response);
}

function wrapSubmitResponse($code, $objectId) {
	$response['code'] = $code;
	$response['created'] = $objectId;
	return json_encode($response);
}

function getCoordinates($address) {
	$_url = sprintf('http://maps.google.com/maps?output=js&q=%s',rawurlencode($address));
	$_result = false;
	if($_result = file_get_contents($_url)) {
		if(strpos($_result,'errortips')>1 || strpos($_result,'Did you mean:') !== false) return false;
		preg_match('!center:\s*{lat:\s*(-?\d+\.\d+),lng:\s*(-?\d+\.\d+)}!U', $_result, $_match);
		$_coords['lat'] = $_match[1];
		$_coords['long'] = $_match[2];
		$_coords[0] = $_coords['lat'];
		$_coords[1] = $_coords['long'];
	}
	return $_coords;
}

function saveAddress($handler, $line1, $line2, $city, $state, $zip, $box) {
	$line1 = mysql_real_escape_string($line1);
	$line2 = mysql_real_escape_string($line2);
	$city = mysql_real_escape_string($city);
	$state = mysql_real_escape_string($state);
	$zip = mysql_real_escape_string($zip);
	$box = mysql_real_escape_string($box);
	$coordinates = getCoordinates("$line1 $zip $city $state");
	$queryAddress = "INSERT INTO address (addressLine1, addressLine2, addressCity, addressState, addressZip, addressPOBox, addressLat, addressLong)
		values ('$line1','$line2','$city','$state','$zip','$box','".$coordinates[0]."','".$coordinates[1]."')";
	
	mysql_query($queryAddress, $handler);
	return mysql_insert_id();
}

function editAddress($handler, $addressId, $line1, $line2, $city, $state, $zip, $box) {
	$addressInfo = getAddressInfo($handler, $addressId);
	if( $addressInfo['addressLine1'] != $line1 || $addressInfo['addressLine2'] != $line2 ||
		$addressInfo['addressCity'] != $city || $addressInfo['addressState'] != $state ||
		$addressInfo['addressZip'] != $zip || $addressInfo['addressPOBox'] != $box ) {
		$line1 = mysql_real_escape_string($line1);
		$line2 = mysql_real_escape_string($line2);
		$city = mysql_real_escape_string($city);
		$state = mysql_real_escape_string($state);
		$zip = mysql_real_escape_string($zip);
		$box = mysql_real_escape_string($box);
		$coordinates = getCoordinates("$line1 $zip $city $state");
		$queryAddress = "
			UPDATE address SET
				addressLine1 = '$line1',
				addressLine2 = '$line2',
				addressCity = '$city',
				addressState = '$state',
				addressZip = '$zip',
				addressPOBox = '$box',
				addressLat = '".$coordinates[0]."',
				addressLong = '".$coordinates[1]."'
			WHERE addressId = '$addressId'
		";
		mysql_query($queryAddress, $handler);
	}
}

function deleteTruckFeatures($handler, $truckId) {
	mysql_query("delete from truckfeature where truckId = '$truckId'",$handler);
}

function saveTruckFeatures($handler, $truckId, $featureString) {
	deleteTruckFeatures($handler, $truckId);
	$features = explode("~",$featureString);
	foreach($features as $feature) {
		$queryFeature = "INSERT INTO truckfeature (truckId, featureId) values ('$truckId', '$feature')";
		mysql_query($queryFeature, $handler);
	}
}

function saveVendorInvoiceTickets($handler, $invoiceId, $tickets) {
	//if invocie = 0 throw exception
	if($invoiceId == "0") return;
	foreach($tickets as $ticket) {
		mysql_query("INSERT INTO supplierinvoiceticket (supplierInvoiceId, ticketId) values ('$invoiceId', '$ticket')", $handler);
	}
}

function saveNewVendorInvoice($handler, $supplier, $number, $amount, $comment, $date, $tickets) {
	
	$query = "INSERT INTO supplierinvoice (supplierId, supplierInvoiceNumber, supplierInvoiceAmount, supplierInvoiceComment, supplierInvoiceDate, supplierInvoiceCreationDate)
	values ('$supplier','$number','$amount','$comment','$date',now())";
	mysql_query($query, $handler);
	$invoiceId = mysql_insert_id();
	saveVendorInvoiceTickets($handler, $invoiceId, $tickets);
	return $invoiceId;
}

function saveEditEstimate($handler, $estimateId, $name, $customer, $line1, $line2, $city, $state, $zip, $box) {
	$estimate = getBasicEstimateInfo($handler, $estimateId);
	$addressId = editAddress($handler, $estimate['addressId'], $line1, $line2, $city, $state, $zip, $box);
	$query = "UPDATE fakeproject SET
		fakeprojectName = '$name',
		customerId = '$customer'
	WHERE fakeprojectId = '$estimateId'";
	mysql_query($query, $estimate);
	return $estimateId;
	
}

function saveNewEstimate($handler, $name, $customer, $line1, $line2, $city, $state, $zip, $box) {
	$addressId = saveAddress($handler, $line1, $line2, $city, $state, $zip, $box);
	$query = "INSERT INTO fakeproject (fakeprojectName, customerId, addressId)
	values ('$name', '$customer', '$addressId')";
	mysql_query($query, $handler);
	return mysql_insert_id();
}

function saveNewCustomer($handler, $name, $tel, $fax, $web, $term, $line1, $line2, $city, $state, $zip, $box) {
	
	$tel = cleanInputPhone($tel);
	$fax = cleanInputPhone($fax);
	
	$addressId = saveAddress($handler, $line1, $line2, $city, $state, $zip, $box);
	$query = "INSERT INTO customer (customerName,addressId,customerTel,customerFax,customerWebsite,termId)
	values ('$name','$addressId','$tel','$fax','$web','$term')";
	mysql_query($query, $handler);
	return mysql_insert_id();
}

function saveEditCustomer($handler, $customerId, $name, $tel, $fax, $web, $term, $line1, $line2, $city, $state, $zip, $box) {
	
	$tel = cleanInputPhone($tel);
	$fax = cleanInputPhone($fax);
	
	$customer = getBasicCustomerInfo($handler, $customerId);
	$addressId = editAddress($handler, $customer['addressId'], $line1, $line2, $city, $state, $zip, $box);
	$query = "UPDATE customer SET
		customerName = '$name',
		customerTel = '$tel',
		customerFax = '$fax',
		customerWebsite = '$web',
		termId = '$term'
	WHERE customerId = '$customerId'	
	";
	mysql_query($query, $handler);
	return $customerId;
}

function saveEditTruck($handler, $truckId, $broker, $number, $driver, $plates, $info, $brand, $year, $serial, $tire, $line1, $line2, $city, $state, $zip, $box, $features = '') {
	$truck = getBasicTruckInfo($handler, $truckId);
	
	$broker = mysql_real_escape_string($broker);
	$number = mysql_real_escape_string($number);
	$driver = mysql_real_escape_string($driver);
	$plates = mysql_real_escape_string($plates);
	$info = mysql_real_escape_string($info);
	$brand = mysql_real_escape_string($brand);
	$year = mysql_real_escape_string($year);
	$serial = mysql_real_escape_string($serial);
	$tire = mysql_real_escape_string($tire);
	
	$addressId = editAddress($handler, $truck['addressId'], $line1, $line2, $city, $state, $zip, $box);
	$queryTruck = "UPDATE truck SET
		brokerId = '$broker',
		truckNumber = '$number',
		truckDriver = '$driver',
		truckPlates = '$plates',
		truckInfo = '$info',
		truckBrand = '$brand',
		truckYear = '$year',
		truckSerial = '$serial',
		truckTireSize = '$tire'
	WHERE truckId = '$truckId'";
	mysql_query($queryTruck, $handler);
	if($features != '') saveTruckFeatures($handler, $truckId, $features);
	return $truckId;
}

function saveNewTicket($handler, $item, $truck, $driver, $date, $amount, $brokerAmount, $number, $mfi) {
	$query = "INSERT INTO ticket (itemId, truckId, driverId, ticketDate, ticketAmount, ticketBrokerAmount, ticketNumber, ticketMfi)
		VALUES ('$item', '$truck', '$driver', '$date', '$amount', '$brokerAmount', '$number', '$mfi')";
	mysql_query($query, $handler);
	return mysql_insert_id();
}

function saveEditTicket($handler, $ticketId, $item, $truck, $driver, $date, $amount, $brokerAmount, $number, $mfi) {
	$query = "UPDATE ticket SET
		itemId = '$item',
		truckId = '$truck',
		driverId = '$driver',
		ticketDate = '$date',
		ticketAmount = '$amount',
		ticketBrokerAmount = '$brokerAmount',
		ticketNumber = '$number',
		ticketMfi = '$mfi',
	WHERE ticketId = '$ticketId'";
}

function saveNewTruck($handler, $broker, $number, $driver, $plates, $info, $brand, $year, $serial, $tire, $line1, $line2, $city, $state, $zip, $box, $features = '') {
	$broker = mysql_real_escape_string($broker);
	$number = mysql_real_escape_string($number);
	$driver = mysql_real_escape_string($driver);
	$plates = mysql_real_escape_string($plates);
	$info = mysql_real_escape_string($info);
	$brand = mysql_real_escape_string($brand);
	$year = mysql_real_escape_string($year);
	$serial = mysql_real_escape_string($serial);
	$tire = mysql_real_escape_string($tire);
	
	$addressId = saveAddress($handler, $line1, $line2, $city, $state, $zip, $box);
	$queryTruck = "INSERT INTO truck (brokerId, truckNumber,truckDriver,truckPlates,addressId,truckInfo,truckBrand,truckYear,truckSerial,truckTireSize)
		values('$broker','$number','$driver','$plates','$addressId','$info','$brand','$year','$serial','$tire')";
	mysql_query($queryTruck, $handler);
	$truckId = mysql_insert_id();
	if($features != '') saveTruckFeatures($handler, $truckId, $features);
	return $truckId;
}

function saveEditDriver($handler, $driverId, $broker, $first, $last, $ssn, $tel, $mobile, $carrier, $email, $start, $percentage, $term,
	$gender, $ethnicId, $driverClass, $line1, $line2, $city, $state, $zip, $box) {
	
	$driver = getBasicDriverInfo($handler, $driverId);
	
	$broker = cleanInputString($broker);
	$first = cleanInputString($first);
	$last = cleanInputString($last);
	$ssn = cleanInputString($ssn);
	$tel = cleanInputPhone($tel);
	$mobile = cleanInputPhone($mobile);
	$carrier = cleanInputString($carrier);
	$email = cleanInputString($email);
	$start = cleanInputDate($start);
	$percentage = cleanInputString($percentage);
	$term = cleanInputString($term);
	$gender = cleanInputString($gender);
	$ethnicId = cleanInputString($ethnicId);
	$driverClass = cleanInputString($driverClass);
	$addressId = editAddress($handler, $driver['addressId'], $line1, $line2, $city, $state, $zip, $box);
	$queryDriver = "UPDATE driver SET
		brokerId = '$broker',
		driverFirstName = '$first',
		driverLastName = '$last',
		driverSSN = '$ssn',
		driverTel = '$tel',
		driverMobile = '$mobile',
		carrierId = '$carrier',
		driverEmail = '$email',
		driverStartDate = '$start',
		driverPercentage = '$percentage',
		termId = '$term',
		driverGender = '$gender',
		ethnicId = '$ethnicId',
		driverClass = '$driverClass'
	WHERE driverId = '$driverId'";
	
	mysql_query($queryDriver, $handler);
	return $driverId;
	
}

function saveNewDriver($handler, $broker, $first, $last, $ssn, $tel, $mobile, $carrier, $email, $start, $percentage, $term,
	$gender, $ethnicId, $driverClass, $line1, $line2, $city, $state, $zip, $box) {
	$broker = cleanInputString($broker);
	$first = cleanInputString($first);
	$last = cleanInputString($last);
	$ssn = cleanInputString($ssn);
	$tel = cleanInputPhone($tel);
	$mobile = cleanInputPhone($mobile);
	$carrier = cleanInputString($carrier);
	$email = cleanInputString($email);
	$start = cleanInputDate($start);
	$percentage = cleanInputString($percentage);
	$term = cleanInputString($term);
	$gender = cleanInputString($gender);
	$ethnicId = cleanInputString($ethnicId);
	$driverClass = cleanInputString($driverClass);
	
	$addressId = saveAddress($handler, $line1, $line2, $city, $state, $zip, $box);
	$queryDriver = "INSERT INTO driver (brokerId,driverFirstName,driverLastName,addressId,driverSSN,driverTel,driverMobile,carrierId,driverEmail,driverStartDate,driverStatus,driverPercentage,termId,driverGender,ethnicId,driverClass)
		values ('$broker','$first','$last','$addressId','$ssn','$tel','$mobile','$carrier','$email','$start','1','$percentage','$term','$gender','$ethnicId','$driverClass')";
		
	mysql_query($queryDriver, $handler);
	return mysql_insert_id();
}

function cleanInputString($inputString) { return mysql_real_escape_string($inputString); }
function cleanInputDate($inputString) { return ($inputString == '' ? '' : to_YMD(cleanInputString($inputString))); }
function cleanInputPhone($inputString) { return cleanPhoneNumber(cleanInputString($inputString)); }

function saveSupplierCheck($handler, $report, $number, $amount, $date) {
	
	$querySupplierCheque = "INSERT INTO suppliercheque (supplierInvoiceId,supplierchequeNumber, supplierchequeAmount, supplierchequeDate)
		values ('$report','$number','$amount','$date')";
	
	mysql_query($querySupplierCheque, $handler);
	return mysql_insert_id();
}

function deleteSupplierCheck($handler, $checkId) {
	mysql_query("DELETE FROM suppliercheque WHERE supplierchequeId = '$checkId'", $handler);
}

function saveBrokerCheck($handler, $report, $number, $amount, $date) {
	
	$queryBrokerCheque = "INSERT INTO paidcheques (reportId, paidchequeNumber, paidchequesAmount, paidchequesDate)
		values ('$report','$number','$amount','$date')";
	mysql_query($queryBrokerCheque, $handler);
	return mysql_insert_id();
}

function deleteBrokerCheck($handler, $checkId) {
	mysql_query("DELETE FROM paidcheques WHERE paidchequesId = '$checkId'", $handler);
}

function saveNewBroker($handler, $pid, $name, $contact, $tax, $tel, $fax, $radio, $mobile, $carrier, $email, $icc, $wc, 
	$wcexp, $inslb, $lbexp, $genln, $glexp, $start, $percentage, $term, $gender, $ethnicId, $line1, $line2, $city, $state, $zip, $box) {
	$pid = mysql_real_escape_string($pid);
	$name = mysql_real_escape_string($name);
	$contact = mysql_real_escape_string($contact);
	$tax = mysql_real_escape_string($tax);
	$tel = cleanPhoneNumber(mysql_real_escape_string($tel));
	$fax = cleanPhoneNumber(mysql_real_escape_string($fax));
	$radio = mysql_real_escape_string($radio);
	$mobile = cleanPhoneNumber(mysql_real_escape_string($mobile));
	$carrier = mysql_real_escape_string($carrier);
	$email = mysql_real_escape_string($email);
	$icc = mysql_real_escape_string($icc);
	$wc = mysql_real_escape_string($wc);
	$wcexp = to_YMD(mysql_real_escape_string($wcexp));
	$inslb = mysql_real_escape_string($inslb);
	$lbexp = to_YMD(mysql_real_escape_string($lbexp));
	$genln = mysql_real_escape_string($genln);
	$glexp = to_YMD(mysql_real_escape_string($glexp));
	$start = to_YMD(mysql_real_escape_string($start));
	$percentage = mysql_real_escape_string($percentage);
	$term = mysql_real_escape_string($term);
	$gender = mysql_real_escape_string($gender);
	$ethnicId = mysql_real_escape_string($ethnicId);
	$line1 = mysql_real_escape_string($line1);
	$line2 = mysql_real_escape_string($line2);
	$city = mysql_real_escape_string($city);
	$state = mysql_real_escape_string($state);
	$zip = mysql_real_escape_string($zip);
	$box = mysql_real_escape_string($box);
	
	$addressId = saveAddress($handler, $line1, $line2, $city, $state, $zip, $box);
	$queryBroker = "INSERT INTO broker ( brokerPid, brokerName, brokerContactName, addressId, brokerTax, brokerTel, brokerFax, brokerRadio, brokerMobile, carrierId, brokerEmail, brokerIccCert, brokerInsuranceWc, brokerWcExpire, brokerInsuranceLiability, brokerLbExpire, brokerGeneralLiability, brokerGlExp, brokerStartDate, brokerStatus, brokerPercentage, brokerGender, ethnicId, termId )
		values ('$pid','$name','$contact','$addressId','$tax','$tel','$fax','$radio','$mobile','$carrier','$email','$icc','$wc','$wcexp','$inslb','$lbexp','$genln','$glexp','$start','1','$percentage','$gender','$ethnicId','$term')"; 
	
	mysql_query($queryBroker, $handler);
	return mysql_insert_id();
}

function saveEditBroker($handler, $brokerId, $pid, $name, $contact, $tax, $tel, $fax, $radio, $mobile, $carrier, $email, $icc, $wc, 
	$wcexp, $inslb, $lbexp, $genln, $glexp, $start, $percentage, $term, $gender, $ethnicId, $line1, $line2, $city, $state, $zip, $box) {
	$broker = getBasicBrokerInfo($handler, $brokerId);
	
	$pid = mysql_real_escape_string($pid);
	$name = mysql_real_escape_string($name);
	$contact = mysql_real_escape_string($contact);
	$tax = mysql_real_escape_string($tax);
	$tel = cleanPhoneNumber(mysql_real_escape_string($tel));
	$fax = cleanPhoneNumber(mysql_real_escape_string($fax));
	$radio = mysql_real_escape_string($radio);
	$mobile = cleanPhoneNumber(mysql_real_escape_string($mobile));
	$carrier = mysql_real_escape_string($carrier);
	$email = mysql_real_escape_string($email);
	$icc = mysql_real_escape_string($icc);
	$wc = mysql_real_escape_string($wc);
	$wcexp = to_YMD(mysql_real_escape_string($wcexp));
	$inslb = mysql_real_escape_string($inslb);
	$lbexp = to_YMD(mysql_real_escape_string($lbexp));
	$genln = mysql_real_escape_string($genln);
	$glexp = to_YMD(mysql_real_escape_string($glexp));
	$start = to_YMD(mysql_real_escape_string($start));
	$percentage = mysql_real_escape_string($percentage);
	$term = mysql_real_escape_string($term);
	$gender = mysql_real_escape_string($gender);
	$ethnicId = mysql_real_escape_string($ethnicId);
	$line1 = mysql_real_escape_string($line1);
	$line2 = mysql_real_escape_string($line2);
	$city = mysql_real_escape_string($city);
	$state = mysql_real_escape_string($state);
	$zip = mysql_real_escape_string($zip);
	$box = mysql_real_escape_string($box);
	
	$addressId = editAddress($handler, $broker['addressId'], $line1, $line2, $city, $state, $zip, $box);
	$queryBroker = "
	UPDATE broker SET
	 brokerPid = '$pid', 
	 brokerName = '$name', 
	 brokerContactName = '$contact', 
	 brokerTax = '$tax', 
	 brokerTel = '$tel', 
	 brokerFax = '$fax', 
	 brokerRadio = '$radio', 
	 brokerMobile = '$mobile', 
	 carrierId = '$carrier', 
	 brokerEmail = '$email', 
	 brokerIccCert = '$icc', 
	 brokerInsuranceWc = '$wc', 
	 brokerWcExpire = '$wcexp', 
	 brokerInsuranceLiability = '$inslb', 
	 brokerLbExpire = '$lbexp', 
	 brokerGeneralLiability = '$genln', 
	 brokerGlExp = '$glexp', 
	 brokerStartDate = '$start', 
	 brokerStatus = '1', 
	 brokerPercentage = '$percentage', 
	 brokerGender = '$gender', 
	 ethnicId = '$ethnicId', 
	 termId = '$term'
	WHERE brokerId = '$brokerId'"; 
	
	mysql_query($queryBroker, $handler);
	return $brokerId;
}

function saveNewVendor($handler, $name, $info, $comment, $tel, $fax, $line1, $line2, $city, $state, $zip, $box) {
	
	$tel = cleanPhoneNumber(mysql_real_escape_string($tel));
	$fax = cleanPhoneNumber(mysql_real_escape_string($fax));
	
	$addressId = saveAddress($handler, $line1, $line2, $city, $state, $zip, $box);
	
	$queryVendor = "INSERT INTO vendor (vendorName,vendorInfo,vendorComment,vendorTel,vendorFax,addressId)
	values ('$name','$info','$comment','$tel','$fax','$addressId')";
	mysql_query($queryVendor, $handler);
	return mysql_insert_id();
	
}

function saveEditVendor($handler, $vendorId, $name, $info, $comment, $tel, $fax, $line1, $line2, $city, $state, $zip, $box) {
	$vendor = getBasicVendorInfo($handler, $vendorId);
	$tel = cleanPhoneNumber(mysql_real_escape_string($tel));
	$fax = cleanPhoneNumber(mysql_real_escape_string($fax));
	$addressId = editAddress($handler, $vendor['addressId'], $line1, $line2, $city, $state, $zip, $box);
	
	$queryVendor = "UPDATE vendor SET
		vendorName = '$name',
		vendorInfo = '$info',
		vendorComment = '$comment',
		vendorTel = '$tel',
		vendorFax = '$fax'
		WHERE vendorId = '$vendorId'";
	mysql_query($queryVendor, $handler);
	return $vendorId;
}

function saveNewSupplier($handler, $vendor, $name, $tel, $fax, $info, $dumptime, $line1, $line2, $city, $state, $zip, $box) {
	
	$tel = cleanPhoneNumber(mysql_real_escape_string($tel));
	$fax = cleanPhoneNumber(mysql_real_escape_string($fax));
	$addressId = saveAddress($handler, $line1, $line2, $city, $state, $zip, $box);
	$supplierQuery = "INSERT INTO supplier (vendorId,supplierName,supplierTel,supplierFax,addressId,supplierInfo,supplierDumptime)
	values ('$vendor','$name','$tel','$fax','$addressId','$info','$dumptime')";
	mysql_query($supplierQuery, $handler);
	return mysql_insert_id();
}

function saveEditSupplier($handler, $supplierId, $vendor, $name, $tel, $fax, $info, $dumptime, $line1, $line2, $city, $state, $zip, $box) {
	$supplier = getBasicSupplierInfo($handler, $supplierId);
	$tel = cleanPhoneNumber(mysql_real_escape_string($tel));
	$fax = cleanPhoneNumber(mysql_real_escape_string($fax));
	$addressId = editAddress($handler, $supplier['addressId'], $line1, $line2, $city, $state, $zip, $box);
	$supplierQuery = "UPDATE supplier SET
		supplierName = '$name',
		vendorId = '$vendor',
		supplierTel = '$tel',
		supplierFax = '$fax',
		supplierInfo = '$info',
		supplierDumptime = '$dumptime'
	WHERE supplierId = '$supplierId'";
	
	mysql_query($supplierQuery, $handler);
	return $supplierId;
}

function saveNewItem($handler, $number,$projectId, $supplierId, $materialId, $fromId, $toId, $fromText, $toText, $material, $broker, $customer, $type, $description) {
	
	$queryItem = "INSERT INTO item (itemNumber, projectId,supplierId,materialId,fromAddressId,toAddressId,itemDisplayFrom,itemDisplayTo,itemMaterialPrice,itemBrokerCost,itemCustomerCost,itemType,itemDescription)
	values ('$number', '$projectId','$supplierId','$materialId','$fromId','$toId','$fromText','$toText','$material','$broker','$customer','$type','$description')";
	mysql_query($queryItem, $handler);
	return mysql_insert_id();
}

function saveEditItem($handler, $itemId, $number, $projectId, $supplierId, $materialId, $fromId, $toId, $fromText, $toText, $material, $broker, $customer, $type, $description) {
	
	$queryItem = "
	UPDATE item SET
		itemNumber = '$number',
		projectId = '$projectId'
	WHERE itemProposalId = '$itemId'
	";
	
	mysql_query($queryItem, $handler);
	return $itemId;
}

function saveNewItemProposal($handler, $projectId, $supplierId, $materialId, $fromId, $toId, $fromText, $toText, $material, $broker, $customer, $type, $description) {
	
	
	$queryItem = "INSERT INTO item_proposal (projectId,supplierId,materialId,fromAddressId,toAddressId,itemProposalDisplayFrom,itemProposalDisplayTo,itemProposalMaterialPrice,itemProposalBrokerCost,itemProposalCustomerCost,itemProposalType,itemProposalDescription,itemProposalCreationDate)
	values ('$projectId','$supplierId','$materialId','$fromId','$toId','$fromText','$toText','$material','$broker','$customer','$type','$description',now())";
	mysql_query($queryItem, $handler);
	return mysql_insert_id();
}

function saveEditItemProposal($handler, $itemId, $projectId, $supplierId, $materialId, $fromId, $toId, $fromText, $toText, $material, $broker, $customer, $type, $description) {
	
	$queryItem = "
	UPDATE item_proposal SET
		projectId = '$projectId',
		itemProposalCreationDate = now()
	WHERE itemId = '$itemId'
	";
	
	mysql_query($queryItem, $handler);
	return $itemId;
}

function saveNewProposal($handler, $number, $projectId, $supplierId, $materialId, $fromId, $toId, $fromText, $toText, $material, $broker, $customer, $type, $description) {
	
	
	$queryItem = "INSERT INTO fakeitem (itemNumber,fakeprojectId,supplierId,materialId,fromAddressId,toAddressId,itemDisplayFrom,itemDisplayTo,itemMaterialPrice,itemBrokerCost,itemCustomerCost,itemType,itemDescription)
	values ('$number','$projectId','$supplierId','$materialId','$fromId','$toId','$fromText','$toText','$material','$broker','$customer','$type','$description')";
	mysql_query($queryItem, $handler);
	return mysql_insert_id();
}

function saveEditProposal($handler, $itemId, $projectId, $supplierId, $materialId, $fromId, $toId, $fromText, $toText, $material, $broker, $customer, $type, $description) {
	
	$queryItem = "
	UPDATE fakeitem SET
		itemNumber = '$number',
		fakeprojectId = '$projectId',
		itemProposalCreationDate = now()
	WHERE itemId = '$itemId'
	";
	
	mysql_query($queryItem, $handler);
	return $itemId;
}

function saveNewFuelLoad($handler, $broker, $truck, $date, $comment, $start, $finish, $registered, $mileage) {
	
	$query = "INSERT INTO fuel_load (brokerId, truckId, fuelLoadDate, fuelLoadCommet, fuelLoadStart, fuelLoadFinish, fuelLoadRegistered, fuelLoadMileage)
	VALUES ('$broker','$truck','$date','$comment','$start', '$finish', '$registered', '$mileage')";
	mysql_query($query, $handler);
	return mysql_insert_id();
}

function saveEditFuelLoad($handler, $fuel, $broker, $truck, $date, $comment, $start, $finish, $registered, $mileage) {
	$query = "UPDATE fuel_load SET
	brokerId = '$broker',
	truckId = '$truck',
	fuelLoadDate = '$date',
	fuelLoadCommet = '$comment',
	fuelLoadStart = '$start',
	fuelLoadFinish = '$finish',
	fuelLoadRegistered = '$registered',
	fuelLoadMileage = '$mileage'
	WHERE fuelLoadId = '$fuel'
	";
	mysql_query($query, $handler);
	return $fuel;
}

function saveNewCustomerSuperCheck($handler, $customer, $number, $amount, $date, $note) {
	
	$query = "INSERT INTO customer_super_check (customerId, customerSuperCheckNumber, customerSuperCheckAmount, customerSuperCheckDate, customerSuperCheckCreationDate, customerSuperCheckNote)
	values ('$customer' ,'$number', '$amount', '$date', now(), '$note')";
	
	mysql_query($query, $handler);
	$checkId = mysql_insert_id();
	updateCredit($handler, $checkId);
	return $checkId;

}

function saveCustomerCheck($handler, $invoice, $number, $amount, $date, $superCheck) {
	$query = "INSERT INTO receiptcheques (invoiceId, receiptchequeNumber, receiptchequesAmount, receiptchequesDate, customerSuperCheckId)
	values ('$invoice','$number','$amount','$date','$superCheck')";
	mysql_query($query, $handler);
	$checkId = mysql_insert_id();
	if($superCheck!=0 && $superCheck!=null) updateCredit($handler, $superCheck);
	return $checkId;
}

function deleteCustomerCheck($handler, $checkId) {
	$check = mysql_fetch_assoc(mysql_query("SELECT * FROM receiptcheques WHERE receiptchequesId = '$checkId'", $handler));
	mysql_query("DELETE FROM receiptcheques WHERE receiptchequesId = '$checkId'", $handler);
	if($check['customerSuperCheckId']!=0 && $check['customerSuperCheckId']!=null) updateCredit($handler, $check['customerSuperCheckId']);
	return $checkId;
}

function saveEditCredit($handler, $checkId, $customer, $number, $amount, $date, $note) {
	
}

function updateCredit($handler, $customerSuperCheckId){
	$queryCredit = "
		SELECT
			customerSuperCheckAmount - COALESCE(SUM(receiptchequesAmount),0) as difference,
			customerCreditAmount as lastCredit
		FROM
			customer_super_check
			LEFT JOIN receiptcheques using (customerSuperCheckId)
			LEFT JOIN customer_credit using (customerSuperCheckId)
		WHERE
			customerSuperCheckId = $customerSuperCheckId
	";
	$creditInfo = mysql_fetch_assoc(mysql_query($queryCredit, $handler));
	if($creditInfo['difference'] == 0){
		if($creditInfo['lastCredit'] != null){
			//delete
			mysql_query("delete from customer_credit where customerSuperCheckId = $customerSuperCheckId", $handler);
		}else{
			//wont happend nothing to do
			mysql_query("delete from customer_credit where customerSuperCheckId = $customerSuperCheckId", $handler);
		}
	}else{
		if($creditInfo['lastCredit'] != null){
			//update
			mysql_query("update customer_credit set customerCreditAmount = ".$creditInfo['difference']." where customerSuperCheckId = $customerSuperCheckId", $handler);
		}else{
			//insert
			mysql_query("insert into customer_credit (customerSuperCheckId, customerCreditAmount) values ($customerSuperCheckId, ".$creditInfo['difference'].")", $handler);
		}
	}
	return $creditInfo['difference'];
}

function deleteCustomerSuperCheck($handler, $checkId) {
	mysql_query("DELETE FROM customer_super_check WHERE customerSuperCheckId = '$checkId'", $handler);
	mysql_query("DELETE FROM customer_credit WHERE customerSuperCheckId = '$checkId'", $handler);
	mysql_query("DELETE FROM receiptcheques WHERE customerSuperCheckId = '$checkId'", $handler);
}

function saveSupplierMaterial($handler, $supplier, $material, $price, $info) {
	mysql_query("DELETE FROM suppliermaterial WHERE supplierId = '$supplier' AND materialId = '$material'", $handler);
	mysql_query("INSERT INTO suppliermaterial (supplierId, materialId, supplierMaterialLastModified, supplierMaterialPrice, supplierMaterialInfo)
	values ('$supplier','$material',now(),'$price','$info')", $handler);
}

function deleteSupplierMaterial($handler, $supplier, $material) {
	mysql_query("DELETE FROM suppliermaterial WHERE supplierId = '$supplier' AND materialId = '$material'", $handler);
}

function deleteSupplierInvoice($handler, $reportId) {
	mysql_query("DELETE FROM supplierinvoiceticket WHERE supplierInvoiceId = '$reportId'", $handler);
	mysql_query("DELETE FROM supplierinvoice WHERE supplierInvoiceId = '$reportId'", $handler);
}

function deleteBrokerInvoice($handler, $reportId) {
	mysql_query("DELETE FROM reportticket WHERE reportId = $reportId", $handler);
	mysql_query("DELETE FROM report WHERE reportId = $reportId", $handler);
}

function deleteAddress($handler, $addressId) {
	mysql_query("DELETE FROM address WHERE addressId = '$addressId'", $handler);
}

function deleteDriver($handler, $driverId) {
	$driverInfo = getBasicDriverInfo($handler, $driverId);
	
	deleteAddress($handler, $driverInfo['addressId']);
	
	mysql_query("DELETE FROM driver WHERE driverId = '$driverId'", $handler);
	return $driverId;
}

function deleteTruck($handler, $truckId) {
	$truckInfo = getBasicTruckInfo($handler, $truckId);
	
	deleteTruckFeatures($handler, $truckId);
	deleteAddress($handler, $truckInfo['addressId']);
	
	mysql_query("DELETE FROM truck WHERE truckId = '$truckId'", $handler);
	return $truckId;
}

function deleteBroker($handler, $brokerId) {
	$brokerInfo = getBasicBrokerInfo($handler, $brokerId);
	
	$trucks = mysql_query("SELECT * FROM truck WHERE brokerId = $brokerId", $handler);
	while($truck = mysql_fetch_assoc($trucks)) {
		deleteTruck($handler, $truck['truckId']);
	}
	
	$drivers = mysql_query("SELECT * FROM driver WHERE brokerId = $brokerId", $handler);
	while($driver = mysql_fetch_assoc($drivers)) {
		deleteDriver($handler, $driver['driverId']);
	}
	deleteAddress($handler, $brokerInfo['addressId']);
	mysql_query("DELETE FROM broker WHERE brokerId = '$brokerId'", $handler);
	return $brokerId;
	
}

function deleteVendor($handler, $vendorId) {
	$vendorInfo = getBasicVendorInfo($handler, $vendorId);
	
	deleteAddress($handler, $vendorInfo['addressId']);
	mysql_query("DELETE FROM vendor WHERE vendorId = '$vendorId'", $handler);
	return $vendorId;
}

function deleteSupplier($handler, $supplierId) {
	$supplierInfo = getBasicSupplierInfo($handler, $supplierId);
	deleteAddress($handler, $supplierInfo['addressId']);
	mysql_query("DELETE FROM suppliermaterial WHERE supplierId = '$supplierId'", $handler);
	mysql_query("DELETE FROM supplier WHERE supplierId = '$supplierId'", $handler);
	return $supplierId;
}

function deleteItem($handler, $itemId) {
	mysql_query("DELETE FROM item WHERE itemId = '$itemId'", $handler);
	return $itemId;
}

function deleteItemProposal($handler, $itemProposalId) {
	mysql_query("DELETE FROM item_proposal WHERE itemProposalId = '$itemProposalId'", $handler);
	return $itemProposalId;
}

function deleteProposal($handler, $proposalId) {
	mysql_query("DELETE FROM fakeitem WHERE fakeitemId = '$proposalId'", $handler);
	return $proposalId;
}

function deleteEstimate($handler, $estimateId) {
	$estimateInfo = getBasicEstimateInfo($handler, $estimateId);
	$proposals = mysql_query("SELECT * FROM fakeitem WHERE fakeprojectId = '$estimateId'", $handler);
	while($proposal = mysql_fetch_assoc($proposals)) {
		deleteProposal($handler, $proposal['fakeitemId']);
	}
	deleteAddress($handler, $estimateInfo['addressId']);
	mysql_query("DELETE FROM fakeproject WHERE fakeprojectId = '$estimateId'", $handler);
	return $estimateId;
}

function deleteFuelLoad($handler, $fuelLoadId) {
	mysql_query("DELETE FROM fuel_load WHERE fuelLoadId = '$fuelLoadId'", $handler);
	return $fuelLoadId;
}

function deleteTicket($handler, $ticketId) {
	mysql_query("DELETE FROM ticket WHERE ticketId = '$ticketId'", $handler);
	return $ticketId;
}





















?>
