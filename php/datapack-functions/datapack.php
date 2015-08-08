<?php

function getCustomerSuperChecksTable($handler, $params) {
	$query = "SELECT *, COALESCE(customerCreditAmount, '0') as checkCredit FROM customer_super_check JOIN customer USING (customerId) LEFT JOIN customer_credit USING (customerSuperCheckId) WHERE customerSuperCheckId <> '0'";
	if(isset($params['id']) && $params['id'] != '') $query.=" AND customerSuperCheckId = '".$params['id']."' ";
	if(isset($params['customer']) && $params['customer'] != '0') $query.=" AND customerId = '".$params['customer']."' ";
	if(isset($params['number']) && $params['number'] != '') $query.=" AND customerSuperCheckNumber = '".$params['number']."' ";
	if(isset($params['afterDate']) && $params['afterDate'] != '') $query.=" AND customerSuperCheckDate >= '".$params['afterDate']."' ";
	if(isset($params['beforeDate']) && $params['beforeDate'] != '') $query.=" AND customerSuperCheckDate <= '".$params['beforeDate']."' ";
	$query.=" ORDER BY ".((isset($params['orderby']) && $params['orderby'] != '') ? $params['orderby'] : "customerSuperCheckId desc");
	$query.=" limit ".((isset($params['limit']) && $params['limit'] != '') ? $params['limit'] : "200");
	return getObjectsTable($handler, $query, 'customerSuperCheckId', $params);
}

function getFuelLoadsTable($handler, $params, $limit = '200') {
	$query = "SELECT * FROM fuel_load JOIN broker ON (broker.brokerId = fuel_load.brokerId) LEFT JOIN truck USING (truckId) WHERE fuelLoadId <> '0'";
	if(isset($params['fuelLoadId']) && $params['fuelLoadId'] != '') $query.=" AND fuelLoadId = '".$params['fuelLoadId']."'";
	if(isset($params['broker']) && $params['broker'] != '0') $query.=" AND brokerId = '".$params['broker']."'";
	if(isset($params['truck']) && $params['truck'] != '0') $query.=" AND truckId = '".$params['truck']."'";
	if(isset($params['start']) && $params['start'] != '0') $query.=" AND fuelLoadDate >= '".$params['start']."'";
	if(isset($params['end']) && $params['end'] != '0') $query.=" AND fuelLoadDate <= '".$params['end']."'";
	$query.=" ORDER BY ".((isset($params['orderby']) && $params['orderby'] != '') ? $params['orderby'] : "fuelLoadId desc");
	$query.=" limit ".((isset($params['limit']) && $params['limit'] != '') ? $params['limit'] : "200");
	return getObjectsTable($handler, $query, 'fuelLoadId', $params);
}

function getProjectsTable($handler, $params, $limit = '200') {
	$projectsQuery = "SELECT * FROM project JOIN address ON (address.addressId = project.addressId) JOIN customer ON (customer.customerId = project.customerId) LEFT JOIN contact USING (contactId) WHERE projectId <> '0'";
	
	if(isset($params['projectId']) && $params['projectId'] != '') $projectsQuery.= " AND projectId = '".$params['projectId']."'";
	if(isset($params['name']) && $params['name'] != '') $projectsQuery.= " AND projectName like '%".$params['name']."%'";
	if(isset($params['customer']) && $params['customer'] != '0') $projectsQuery.= " AND customerId = '".$params['customer']."'";
	if(isset($params['contact']) && $params['contact'] != '0') $projectsQuery.= " AND contactId = '".$params['contact']."'";
	if(isset($params['addressLine1']) && $params['addressLine1'] != '') $projectsQuery.= " AND addressLine1 like '%".$params['addressLine1']."%'";
	if(isset($params['addressCity']) && $params['addressCity'] != '') $projectsQuery.= " AND addressCity like '%".$params['addressCity']."%'";
	if(isset($params['addressState']) && $params['addressState'] != '0') $projectsQuery.= " AND addressState ='".$params['addressState']."'";
	if(isset($params['addressZip']) && $params['addressZip'] != '') $projectsQuery.= " AND addressZip ='".$params['addressZip']."'";
	$projectsQuery.=" ORDER BY projectId desc ".($limit == '' ? '' : ' limit '.$limit);
	return getObjectsTable($handler, $projectsQuery, 'projectId', $params);
}

function getContactsTable($handler, $params, $limit = '200') {
	$contactsQuery = "SELECT * FROM contact JOIN address ON (address.addressId = contact.addressId) JOIN customer USING (customerId) WHERE contactId <> '0'";
	if(isset($params['contactId']) && $params['contactId'] != '') $contactsQuery.= " AND contactId = '".$params['contactId']."'";
	if(isset($params['name']) && $params['name'] != '') $contactsQuery.= " AND contactName like '%".$params['name']."%'";
	if(isset($params['customer']) && $params['customer'] != '0') $contactsQuery.= " AND customerId = '".$params['customer']."'";
	if(isset($params['addressLine1']) && $params['addressLine1'] != '') $contactsQuery.= " AND addressLine1 like '%".$params['addressLine1']."%'";
	if(isset($params['addressCity']) && $params['addressCity'] != '') $contactsQuery.= " AND addressCity like '%".$params['addressCity']."%'";
	if(isset($params['addressState']) && $params['addressState'] != '0') $contactsQuery.= " AND addressState ='".$params['addressState']."'";
	if(isset($params['addressZip']) && $params['addressZip'] != '') $contactsQuery.= " AND addressZip ='".$params['addressZip']."'";
	
	$contactsQuery.= " ORDER BY contactId desc ".($limit == '' ? '' : ' limit '.$limit);
	return getObjectsTable($handler, $contactsQuery, 'contactId', $params);
	
}

function getCustomersTable($handler, $params, $limit = '200') {
	$customersQuery = "SELECT * FROM customer JOIN address USING (addressId) JOIN term USING (termId) WHERE customerId <> '0'";
	if(isset($params['customerId']) && $params['customerId'] != '') $customersQuery.= " AND customerId = '".$params['customerId']."'";
	if(isset($params['name']) && $params['name'] != '') $customersQuery.= " AND customerName like '%".$params['name']."%'";
	if(isset($params['term']) && $params['term'] != '0') $customersQuery.= " AND termId = '".$params['term']."'";
	if(isset($params['addressLine1']) && $params['addressLine1'] != '') $customersQuery.= " AND addressLine1 like '%".$params['addressLine1']."%'";
	if(isset($params['addressCity']) && $params['addressCity'] != '') $customersQuery.= " AND addressCity like '%".$params['addressCity']."%'";
	if(isset($params['addressState']) && $params['addressState'] != '0') $customersQuery.= " AND addressState ='".$params['addressState']."'";
	if(isset($params['addressZip']) && $params['addressZip'] != '') $customersQuery.= " AND addressZip ='".$params['addressZip']."'";
	
	$customersQuery.= " ORDER BY customerId desc ".($limit == '' ? '' : ' limit '.$limit);
	return getObjectsTable($handler, $customersQuery, 'customerId', $params);
}

function getVendorsTable($handler, $params, $limit = '200') {
	$vendorsQuery = "SELECT * FROM vendor JOIN address USING (addressId) WHERE vendorId <> '0'";
	if(isset($params['vendorId']) && $params['vendorId'] != '') $vendorsQuery.= " AND vendorId = '".$params['vendorId']."'";
	if(isset($params['name']) && $params['name'] != '') $vendorsQuery.= " AND vendorName like '%".$params['name']."%'";
	if(isset($params['addressLine1']) && $params['addressLine1'] != '') $vendorsQuery.= " AND addressLine1 like '%".$params['addressLine1']."%'";
	if(isset($params['addressCity']) && $params['addressCity'] != '') $vendorsQuery.= " AND addressCity like '%".$params['addressCity']."%'";
	if(isset($params['addressState']) && $params['addressState'] != '0') $vendorsQuery.= " AND addressState ='".$params['addressState']."'";
	if(isset($params['addressZip']) && $params['addressZip'] != '') $vendorsQuery.= " AND addressZip ='".$params['addressZip']."'";
	
	$vendorsQuery.= " ORDER BY vendorId desc ".($limit == '' ? '' : ' limit '.$limit);
	return getObjectsTable($handler, $vendorsQuery, 'vendorId', $params);
}

function getSuppliersTable($handler, $params, $limit = '200') {
	$suppliersQuery = "SELECT * FROM supplier JOIN address ON (address.addressId = supplier.addressId) JOIN vendor USING (vendorId) WHERE supplierId <> '0'";
	if(isset($params['supplierId']) && $params['supplierId'] != '') $suppliersQuery.= " AND supplierId = '".$params['supplierId']."'";
	if(isset($params['name']) && $params['name'] != '') $suppliersQuery.= " AND supplierName like '%".$params['name']."%'";
	if(isset($params['vendor']) && $params['vendor'] != '0') $suppliersQuery.= " AND vendorId = '".$params['vendor']."'";
	if(isset($params['addressLine1']) && $params['addressLine1'] != '') $suppliersQuery.= " AND addressLine1 like '%".$params['addressLine1']."%'";
	if(isset($params['addressCity']) && $params['addressCity'] != '') $suppliersQuery.= " AND addressCity like '%".$params['addressCity']."%'";
	if(isset($params['addressState']) && $params['addressState'] != '0') $suppliersQuery.= " AND addressState ='".$params['addressState']."'";
	if(isset($params['addressZip']) && $params['addressZip'] != '') $suppliersQuery.= " AND addressZip ='".$params['addressZip']."'";
	$suppliersQuery.=" ORDER BY supplierId desc ".($limit == '' ? '' : ' limit '.$limit);
	return getObjectsTable($handler, $suppliersQuery, 'supplierId', $params);
}

function getEstimatesTable($handler, $params, $limit = '200') {
	$estimatesQuery = "SELECT * FROM fakeproject JOIN address ON (address.addressId = fakeproject.addressId) JOIN customer USING (customerId) WHERE fakeprojectId <> '0'";
	if(isset($params['estimateId']) && $params['estimateId'] != '') $estimatesQuery.= " AND fakeprojectId = '".$params['estimateId']."'";
	if(isset($params['name']) && $params['name'] != '') $estimatesQuery.= " AND fakeprojectName like '%".$params['name']."%'";
	if(isset($params['customer']) && $params['customer'] != '0') $estimatesQuery.= " AND customerId = '".$params['customer']."'";
	if(isset($params['addressLine1']) && $params['addressLine1'] != '') $estimatesQuery.= " AND addressLine1 like '%".$params['addressLine1']."%'";
	if(isset($params['addressCity']) && $params['addressCity'] != '') $estimatesQuery.= " AND addressCity like '%".$params['addressCity']."%'";
	if(isset($params['addressState']) && $params['addressState'] != '0') $estimatesQuery.= " AND addressState ='".$params['addressState']."'";
	if(isset($params['addressZip']) && $params['addressZip'] != '') $estimatesQuery.= " AND addressZip ='".$params['addressZip']."'";
	$estimatesQuery.=" ORDER BY fakeprojectId desc ".($limit == '' ? '' : ' limit '.$limit);
	return getObjectsTable($handler, $estimatesQuery, 'fakeprojectId', $params);
}

function getProposalsTable($handler, $params, $limit = '200') {
	$proposalsQuery = "
	SELECT *,
			CASE 
				WHEN itemType = 0 THEN 'Loads' 
				WHEN itemType = 1 THEN 'Tons' 
				WHEN itemType = 2 THEN 'Hours' 
			END AS 'itemProposalTypeString'
	FROM
		fakeitem
		JOIN fakeproject USING (fakeprojectId)
		JOIN customer USING (customerId)
		JOIN supplier USING (supplierId)
		JOIN vendor USING (vendorId)
		JOIN material USING (materialId)
	WHERE fakeitemId <> 0
	";
	if(isset($params['fakeitemId']) && $params['fakeitemId'] != '') $proposalsQuery.= " AND fakeitemId = '".$params['fakeitemId']."'";
	if(isset($params['customerId']) && $params['customerId'] != '0') $proposalsQuery.= " AND customerId = '".$params['customerId']."'";
	if(isset($params['projectId']) && $params['projectId'] != '0') $proposalsQuery.= " AND projectId = '".$params['projectId']."'";
	if(isset($params['vendorId']) && $params['vendorId'] != '0') $proposalsQuery.= " AND vendorId = '".$params['vendorId']."'";
	if(isset($params['supplierId']) && $params['supplierId'] != '0') $proposalsQuery.= " AND supplierId = '".$params['supplierId']."'";
	if(isset($params['materialId']) && $params['materialId'] != '0') $proposalsQuery.= " AND materialId = '".$params['materialId']."'";
	if($params['type'] != 3) $proposalsQuery.= " AND itemType = '".$params['type']."'";
	$proposalsQuery.=" ORDER BY fakeitemId desc ".($limit == '' ? '' : ' limit '.$limit);
	return getObjectsTable($handler, $proposalsQuery, 'fakeitemId', $params);
}

function getItemsProposalsTable($handler, $params, $limit = '200') {
	$itemProposalsQuery = "
	SELECT
		*,
			CASE 
				WHEN itemProposalType = 0 THEN 'Loads' 
				WHEN itemProposalType = 1 THEN 'Tons' 
				WHEN itemProposalType = 2 THEN 'Hours' 
			END AS 'itemProposalTypeString'
	FROM
		item_proposal
		JOIN project USING (projectId)
		JOIN customer USING (customerId)
		JOIN supplier USING (supplierId)
		JOIN vendor USING (vendorId)
		JOIN material USING (materialId)
	WHERE itemProposalId <> 0
	";
	if(isset($params['itemProposalId']) && $params['itemProposalId'] != '') $itemProposalsQuery.= " AND itemProposalId = '".$params['itemProposalId']."'";
	if(isset($params['customerId']) && $params['customerId'] != '0') $itemProposalsQuery.= " AND customerId = '".$params['customerId']."'";
	if(isset($params['projectId']) && $params['projectId'] != '0') $itemProposalsQuery.= " AND projectId = '".$params['projectId']."'";
	if(isset($params['vendorId']) && $params['vendorId'] != '0') $itemProposalsQuery.= " AND vendorId = '".$params['vendorId']."'";
	if(isset($params['supplierId']) && $params['supplierId'] != '0') $itemProposalsQuery.= " AND supplierId = '".$params['supplierId']."'";
	if(isset($params['materialId']) && $params['materialId'] != '0') $itemProposalsQuery.= " AND materialId = '".$params['materialId']."'";
	if($params['type'] != 3) $itemProposalsQuery.= " AND itemProposalType = '".$params['type']."'";
	$itemProposalsQuery.=" ORDER BY itemProposalId desc ".($limit == '' ? '' : ' limit '.$limit);
	//echo $itemProposalsQuery;
	return getObjectsTable($handler, $itemProposalsQuery, 'itemProposalId', $params);
}

function getObjectsTable($handler, $query, $objectId, $params) {
	$values = $params['values'];
	$headers = $params['headers'];
	$types = $params['variables'];
	$dataTable = array();
	$headerArray = explode("~",$headers);
	$headerRow = array("id");
	foreach($headerArray as $header) {
		$headerRow[] = $header;
	}
	$dataTable[] = $headerRow;
	$typeMap = createTypeMap($values, $types, '~');
	$objects = mysql_query($query, $handler);
	//echo $query;
	while($object = mysql_fetch_assoc($objects)) {
		$dataTable[] = explode('~',$object[$objectId].mapValuesWithTypes($object, $typeMap, '~', ''));
	}
	return $dataTable;
	
}

function getItemsTable($handler, $params, $limit = '200') {
	$values = $params['values'];
	$headers = $params['headers'];
	$types = $params['variables'];
	
	$itemsQuery = "
		SELECT
			*,
			CASE 
				WHEN itemType = 0 THEN 'Loads' 
				WHEN itemType = 1 THEN 'Tons' 
				WHEN itemType = 2 THEN 'Hours' 
			END AS 'itemTypeString'
		FROM
			item
			JOIN project USING (projectId)
			JOIN customer USING (customerId)
			JOIN supplier USING (supplierId)
			JOIN vendor USING (vendorId)
			JOIN material USING (materialId)
		WHERE itemId <> 0
	";
	if(isset($params['itemId']) && $params['itemId'] != '') $itemsQuery.= " AND itemId = '".$params['itemId']."'";
	if(isset($params['customerId']) && $params['customerId'] != '0') $itemsQuery.= " AND customerId = '".$params['customerId']."'";
	if(isset($params['projectId']) && $params['projectId'] != '0') $itemsQuery.= " AND projectId = '".$params['projectId']."'";
	if(isset($params['vendorId']) && $params['vendorId'] != '0') $itemsQuery.= " AND vendorId = '".$params['vendorId']."'";
	if(isset($params['supplierId']) && $params['supplierId'] != '0') $itemsQuery.= " AND supplierId = '".$params['supplierId']."'";
	if(isset($params['materialId']) && $params['materialId'] != '0') $itemsQuery.= " AND materialId = '".$params['materialId']."'";
	if($params['type'] != 3) $itemsQuery.= " AND itemType = '".$params['type']."'";
	
	$itemsQuery.=" ORDER BY itemId desc ".($limit == '' ? '' : ' limit '.$limit);
	$dataTable = array();
	$headerArray = explode("~",$headers);
	$headerRow = array("id");
	foreach($headerArray as $header) {
		$headerRow[] = $header;
	}
	$dataTable[] = $headerRow;
	$typeMap = createTypeMap($values, $types, '~');
	//echo $itemsQuery;
	$items = mysql_query($itemsQuery, $handler);
	while($item = mysql_fetch_assoc($items)) {
		$dataTable[] = explode('~',$item['itemId'].mapValuesWithTypes($item, $typeMap, '~', ''));
	}
	
	return $dataTable;
}

function getTrucksTable($handler, $params, $limit = '200') {
	$values = $params['values'];
	$headers = $params['headers'];
	$types = $params['variables'];
	
	$trucksQuery = "
		SELECT
			*
		FROM
			truck
			JOIN address ON (truck.addressId = address.addressId)
			JOIN broker USING (brokerId)
		WHERE truckId <> 0
	";
	if(isset($params['truckId']) && $params['truckId'] != '') $trucksQuery.= " AND truckId = '".$params['truckId']."'";
	if(isset($params['brokerId']) && $params['brokerId'] != '0') $trucksQuery.= " AND brokerId = '".$params['brokerId']."'";
	if(isset($params['truckNumber']) && $params['truckNumber'] != '') $trucksQuery.= " AND truckNumber = '%".$params['truckNumber']."%'";
	if(isset($params['addressCity']) && $params['addressCity'] != '') $trucksQuery.= " AND addressCity like '%".$params['addressCity']."%'";
	if(isset($params['addressState']) && $params['addressState'] != '0') $trucksQuery.= " AND addressState ='".$params['addressState']."'";
	if(isset($params['addressZip']) && $params['addressZip'] != '') $trucksQuery.= " AND addressZip ='".$params['addressZip']."'";
	
	$trucksQuery.=" ORDER BY truckId desc ".($limit == '' ? '' : ' limit '.$limit);
	$dataTable = array();
	$headerArray = explode("~",$headers);
	$headerRow = array("id");
	foreach($headerArray as $header) {
		$headerRow[] = $header;
	}
	$dataTable[] = $headerRow;
	$typeMap = createTypeMap($values, $types, '~');
	//echo $trucksQuery;
	$trucks = mysql_query($trucksQuery, $handler);
	while($truck = mysql_fetch_assoc($trucks)) {
		$dataTable[] = explode('~',$truck['truckId'].mapValuesWithTypes($truck, $typeMap, '~', ''));
	}
	
	return $dataTable;
}

function getDriversTable($handler, $params, $limit = '200') {
	$values = $params['values'];
	$headers = $params['headers'];
	$types = $params['variables'];
	
	$driversQuery = "
		SELECT
			*
		FROM
			driver
			JOIN address ON (driver.addressId = address.addressId)
			JOIN broker USING (brokerId)
			JOIN term ON (driver.termId = term.termId)
			LEFT JOIN ethnic ON (driver.ethnicId = ethnic.ethnicId)
		WHERE driverId <> 0
	";
	if(isset($params['driverId']) && $params['driverId'] != '') $driversQuery.= " AND driverId = '".$params['driverId']."'";
	if(isset($params['driverFirstName']) && $params['driverFirstName'] != '') $driversQuery.= " AND driverFirstName = '%".$params['driverFirstName']."%'";
	if(isset($params['driverLastName']) && $params['driverLastName'] != '') $driversQuery.= " AND driverLastName = '%".$params['driverLastName']."%'";
	if(isset($params['brokerId']) && $params['brokerId'] != '0') $driversQuery.= " AND brokerId = '".$params['brokerId']."'";
	if(isset($params['addressCity']) && $params['addressCity'] != '') $driversQuery.= " AND addressCity like '%".$params['addressCity']."%'";
	if(isset($params['addressState']) && $params['addressState'] != '0') $driversQuery.= " AND addressState ='".$params['addressState']."'";
	if(isset($params['addressZip']) && $params['addressZip'] != '') $driversQuery.= " AND addressZip ='".$params['addressZip']."'";
	
	if(isset($params['driverGender']) && $params['driverGender'] != '0') $driversQuery.= " AND driverGender ='".$params['driverGender']."'";
	if(isset($params['ethnicId']) && $params['ethnicId'] != '0') $driversQuery.= " AND ethnicId ='".$params['ethnicId']."'";
	
	$driversQuery.=" ORDER BY driverId desc ".($limit == '' ? '' : ' limit '.$limit);
	$dataTable = array();
	$headerArray = explode("~",$headers);
	$headerRow = array("id");
	foreach($headerArray as $header) {
		$headerRow[] = $header;
	}
	$dataTable[] = $headerRow;
	$typeMap = createTypeMap($values, $types, '~');
	$drivers = mysql_query($driversQuery, $handler);
	while($driver = mysql_fetch_assoc($drivers)) {
		$dataTable[] = explode('~',$driver['driverId'].mapValuesWithTypes($driver, $typeMap, '~', ''));
	}
	
	return $dataTable;
	
}

function getBrokersTable($handler, $params, $limit = '200') {
	$values = $params['values'];
	$headers = $params['headers'];
	$types = $params['variables'];
	
	$brokersQuery = "
		SELECT
			*
		FROM
			broker
			JOIN address USING (addressId)
			JOIN term USING (termId)
			LEFT JOIN ethnic USING (ethnicId)
		WHERE brokerId <> 0
	";
	if(isset($params['brokerId']) && $params['brokerId'] != '') $brokersQuery.= " AND brokerId = '".$params['brokerId']."'";
	if(isset($params['brokerPid']) && $params['brokerPid'] != '') $brokersQuery.= " AND brokerPid like '%".$params['brokerPid']."%'";
	if(isset($params['brokerName']) && $params['brokerName'] != '') $brokersQuery.= " AND brokerName like '%".$params['brokerName']."%'";
	if(isset($params['addressLine1']) && $params['addressLine1'] != '') $brokersQuery.= " AND addressLine1 like '%".$params['addressLine1']."%'";
	if(isset($params['tel']) && $params['tel'] != '') $brokersQuery.= " AND brokerTel like '%".cleanPhoneNumber($params['tel'])."%'";
	if(isset($params['addressCity']) && $params['addressCity'] != '') $brokersQuery.= " AND addressCity like '%".$params['addressCity']."%'";
	if(isset($params['addressState']) && $params['addressState'] != '0') $brokersQuery.= " AND addressState ='".$params['addressState']."'";
	if(isset($params['addressZip']) && $params['addressZip'] != '') $brokersQuery.= " AND addressZip ='".$params['addressZip']."'";
	if(isset($params['brokerGender']) && $params['brokerGender'] != '0') $brokersQuery.= " AND brokerGender ='".$params['brokerGender']."'";
	if(isset($params['ethnicId']) && $params['ethnicId'] != '0') $brokersQuery.= " AND ethnicId ='".$params['ethnicId']."'";
	
	$brokersQuery.=" ORDER BY brokerName asc ".($limit == '' ? '' : ' limit '.$limit);	
	$dataTable = array();
	$headerArray = explode("~",$headers);
	$headerRow = array("id");
	foreach($headerArray as $header) {
		$headerRow[] = $header;
	}
	$dataTable[] = $headerRow;
	$typeMap = createTypeMap($values, $types, '~');
	$brokers = mysql_query($brokersQuery, $handler);
	while($broker = mysql_fetch_assoc($brokers)) {
		$dataTable[] = explode('~',$broker['brokerId'].mapValuesWithTypes($broker, $typeMap, '~', ''));
	}
	
	return $dataTable;
}

function getTicketsTable($handler, $params, $limit = '200') {
	$customerId = (isset($params['customerId'])?$params['customerId']:0);
	$projectId = (isset($params['projectId'])?$params['projectId']:0);
	$itemId = (isset($params['itemId'])?$params['itemId']:0);
	$brokerId = (isset($params['brokerId'])?$params['brokerId']:0);
	$truckId = (isset($params['truckId'])?$params['truckId']:0);
	$driverId = (isset($params['driverId'])?$params['driverId']:0);
	$vendorId = (isset($params['vendorId'])?$params['vendorId']:0);
	$supplierId = (isset($params['supplierId'])?$params['supplierId']:0);

	$invoiceId = (isset($params['invoiceId'])?$params['invoiceId']:0);
	$reportId = (isset($params['reportId'])?$params['reportId']:0);
	$supplierInvoiceId = (isset($params['supplierInvoiceId'])?$params['supplierInvoiceId']:0);

	$ticketMfi = (isset($params['ticketMfi'])?$params['ticketMfi']:0);
	$ticketNumber = (isset($params['ticketNumber'])?$params['ticketNumber']:0);
	$startDate = (isset($params['startDate'])?$params['startDate']:0);
	$endDate = (isset($params['endDate'])?$params['endDate']:0);

	$invoiced = (isset($params['invoiced'])?$params['invoiced']:0);
	$reported = (isset($params['reported'])?$params['reported']:0);
	$supplied = (isset($params['supplied'])?$params['supplied']:0);

	$values = $params['values'];
	$headers = $params['headers'];
	$types = $params['variables'];

	$ticketsQuery = "
		SELECT
			*
		FROM
			ticket
			JOIN item USING (itemId)
			JOIN material USING (materialId)
			JOIN supplier USING (supplierId)
			JOIN vendor USING (vendorId)
			JOIN project USING (projectId)
			JOIN customer USING (customerId)
			JOIN truck USING (truckId)
			JOIN broker ON (truck.brokerId = broker.brokerId)
			LEFT JOIN driver USING (driverId)
			LEFT JOIN reportticket USING (ticketId)
			LEFT JOIN invoiceticket USING (ticketId)
			LEFT JOIN supplierinvoiceticket USING (ticketId)
		WHERE ticketId <> 0
	";
	if($customerId!=0)$ticketsQuery.=" AND customer.customerId = $customerId";
	if($projectId!=0)$ticketsQuery.=" AND project.projectId = $projectId";
	if($itemId!=0)$ticketsQuery.=" AND item.itemId = $itemId";
	if($brokerId!=0)$ticketsQuery.=" AND broker.brokerId = $brokerId";
	if($truckId!=0)$ticketsQuery.=" AND truck.truckId = $truckId";
	if($driverId!=0)$ticketsQuery.=" AND driver.driverId = $driverId";
	if($vendorId!=0)$ticketsQuery.=" AND vendor.vendorId = $vendorId";
	if($supplierId!=0)$ticketsQuery.=" AND supplier.supplierId = $supplierId";

	if($invoiceId!=0)$ticketsQuery.=" AND invoiceId = $invoiceId";
	if($reportId!=0)$ticketsQuery.=" AND reportId = $reportId";
	if($supplierInvoiceId!=0)$ticketsQuery.=" AND supplierInvoiceId = $supplierInvoiceId";

	if($ticketMfi!=0)$ticketsQuery.=" AND ticketMfi = '$ticketMfi'";
	if($ticketNumber!=0)$ticketsQuery.=" AND ticketNumber = '$ticketNumber'";
	if($startDate!=0)$ticketsQuery.=" AND ticketDate >= '$startDate'";
	if($endDate!=0)$ticketsQuery.=" AND ticketDate <= '$endDate'";

	if($invoiced == '1')$ticketsQuery.=" AND invoiceId IS NOT NULL";
	if($invoiced == '2')$ticketsQuery.=" AND invoiceId IS NULL";
	if($reported == '1')$ticketsQuery.=" AND reportId IS NOT NULL";
	if($reported == '2')$ticketsQuery.=" AND reportId IS NULL";
	if($supplied == '1')$ticketsQuery.=" AND supplierInvoiceId IS NOT NULL";
	if($supplied == '2')$ticketsQuery.=" AND supplierInvoiceId IS NULL";

	$ticketsQuery.=" ORDER BY ticketId desc ".($limit == '' ? '' : ' limit '.$limit);	
	$dataTable = array();
	$headerArray = explode("~",$headers);
	$headerRow = array("id");
	foreach($headerArray as $header) {
		$headerRow[] = $header;
	}
	$dataTable[] = $headerRow;
	$typeMap = createTypeMap($values, $types, '~');
	$tickets = mysql_query($ticketsQuery, $handler);
	while($ticket = mysql_fetch_assoc($tickets)) {
		$dataTable[] = explode('~',$ticket['ticketId'].mapValuesWithTypes($ticket, $typeMap, '~', ''));
	}
	
	return $dataTable;
	
	/*
	$tableString = "<table class='listing form' cellpadding='0' cellspacing='0' ><tr>";
$headerArray = explode("~",$headers);
foreach($headerArray as $header) {
	$tableString.="<th>$header</th>";
}
$tableString.="</tr>";

$typeMap = createTypeMap($values, $types, '~');
$tickets = mysql_query($ticketsQuery, $conexion);
while($ticket = mysql_fetch_assoc($tickets)) {
	$tableString.="<tr ticketId='".$ticket['ticketId']."' id='ticket".$ticket['ticketId']."' class='doubleClickable'>";
	$tableString.=mapValuesWithTypes($ticket, $typeMap, '<td align="right">', '</td>');
	$tableString.="</tr>";
}

$jsondata['objectId'] = 'ticketsTable';
$jsondata['table'] = $tableString;
	*/
}
?>
