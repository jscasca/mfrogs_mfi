<?php


define('NYRO_CLASS','nyroable');
define('ACTION_ICON_CLASS','actionIcon');

define('NYRO_URL_REF','urlRef');
define('NYRO_ATTR_NAME','attrName');

/*--------------VARIABLES------------------------*/
define('ERROR_CODE_EXPIRED',-7);
define('ERROR_CODE_INVALID_VALUE',-6);
define('ERROR_CODE_FIVE',-5);
define('ERROR_CODE_DUPLICATE',-4);
define('ERROR_CODE_INTERNAL_ERROR', -3);
define('ERROR_CODE_NOT_READY', -2);
define('ERROR_CODE_MISSING_PARAMETERS', -1);
define('SUCCESS_CODE', 0);

/* ------------IMG TITLES--------------------*/
define('TITLE_VIEW_ESTIMATE', 'View Estimate');
define('TITLE_VIEW_PROJECT', 'View Project');
define('TITLE_VIEW_CUSTOMER', 'View Customer');
define('TITLE_VIEW_BROKER', 'View Broker');
define('TITLE_VIEW_TRUCK', 'View Truck');
define('TITLE_VIEW_DRIVER', 'View Driver');
define('TITLE_VIEW_VENDOR', 'View Vendor');
define('TITLE_VIEW_SUPPLIER', 'View Supplier');
define('TITLE_VIEW_CONTACT', 'View Contact');
define('TITLE_VIEW_ITEM', 'View Item');


/*--------------IMAGES-----------------------*/
define('IMG_VIEW','/mfi/img/16.png');
define('IMG_EDIT','/mfi/img/13.png');
define('IMG_SAVE','/mfi/img/7.png');
define('IMG_DELETE','/mfi/img/118.png');
define('IMG_ADD','/mfi/img/112.png');
define('IMG_PAY','/mfi/img/88.png');
define('IMG_MNG','/mfi/img/44.png');

define('IMG_NEW_ITEM','/mfi/img/43.png');
define('IMG_NEW_ITEM_PROPOSAL','/mfi/img/42.png');
define('IMG_NEW_PROPOSAL','/mfi/img/42.png');

define('IMG_LOADING','/mfi/img/007.gif');


define('SIZE_SMALL_ATTR',' width="12" height="12"');

$trBackgroundClass = "class='bg'";

$brokerExtendedTables = "broker JOIN address USING (addressId) JOIN term USING (termId) LEFT JOIN ethnic USING (ethnicId)";

$driverExtendedTables = "driver JOIN address USING (addressId) JOIN term USING (termId) LEFT JOIN ethnic USING (ethnicId)";

$vendorExtendedTables = "vendor JOIN address USING (addressId)";
$supplierExtendedTables = "supplier JOIN address USING (addressId) JOIN vendor USING (vendorId)";

$itemExtendedSelect = "*,CASE WHEN itemType = 0 THEN 'Loads' WHEN itemType = 1 THEN 'Tons' WHEN itemType = 2 THEN 'Hours' 
			END AS 'itemTypeString'";
$itemProposalExtendedSelect = "*,CASE WHEN itemProposalType = 0 THEN 'Loads' WHEN itemProposalType = 1 THEN 'Tons' WHEN itemProposalType = 2 THEN 'Hours' 
			END AS 'itemProposalTypeString'";

$itemExtendedTables = "item JOIN material USING (materialId) JOIN supplier USING (supplierId) JOIN project USING (projectId) JOIN vendor USING (vendorId) JOIN customer USING (customerId)";
$itemProposalExtendedTables = "item_proposal JOIN material USING (materialId) JOIN supplier USING (supplierId) JOIN project USING (projectId) JOIN vendor USING (vendorId) JOIN customer USING (customerId)";

$projectExtendedTables = "project JOIN address USING (addressId) LEFT JOIN jobland USING (jobLandId) LEFT JOIN jobterrain USING (jobTerrainId)
	JOIN customer USING (customerId)";
$customerExtendedTables = "customer JOIN address USING (addressId) JOIN term USING (termId)";

$estimateExtendedTables = "fakeproject JOIN customer USING (customerId) JOIN address ON (address.addressId = fakeproject.addressId)";

$ticketExtendedTables = "ticket JOIN item USING (itemId) JOIN material USING (materialId) JOIN project USING (projectId)
	JOIN customer USING (customerId) JOIN supplier USING (supplierId) JOIN vendor USING (vendorId) JOIN truck USING (truckId)
	JOIN broker USING (brokerId) LEFT JOIN driver USING (driverId) LEFT JOIN reportticket USING (ticketId)
	LEFT JOIN invoiceticket USING (ticketId) LEFT JOIN supplierinvoiceticket USING (ticketId)";

?>
