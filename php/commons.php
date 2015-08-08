<?php

function createAddIcon($iconId, $url, $params, $objectType = '') { return createActionIcon(IMG_ADD, $iconId, 'Add '.$objectType, $url, $params, 'show');}
function createEditIcon($iconId, $url, $params, $objectType = '') { return createActionIcon(IMG_EDIT, $iconId, 'Edit '.$objectType, $url, $params, 'show');}
function createDeleteIcon($iconId, $url, $params, $objectType = '') { return createActionIcon(IMG_DELETE, $iconId, 'Delete '.$objectType, $url, $params, 'delete');}

function createActionIcon($imgSrc, $iconId, $title, $url, $params, $type = 'show', $attributes = '') {
	return "
	<div class='actionIconDiv'>
		<img src='$imgSrc'
		id='$iconId'
		title='$title'
		class='".ACTION_ICON_CLASS."'
		useurl='$url'
		withparams='$params'
		calltype='$type'
		$attributes
		/>
	</div>
	";
}

function createFormRowTextFieldWithValue($fieldName, $id, $class = '', $strict = false, $attributes = '', $value) {
	return createFormRowTextField($fieldName, $id, $class = '', $strict = false, $attributes." value='$value'"); 
}

function createFormRowTextField($fieldName, $id, $class = '', $strict = false, $attributes = '') {
	return createFormRow($fieldName, $class, $strict, createInputText($id, $id, $attributes));
	//return "<tr $class><td class='first'><strong>$fieldName</strong>".($strict?'<span style="color:red;">*</span>':"")."</td><td class='last'>".createInputText($id, $id, $attributes)."</td></tr>";
}

function createFormRow($fieldName, $class, $strict, $stringObject) {
	return "<tr $class><td class='first' width='172'><strong>$fieldName: </strong>".($strict?'<span style="color:red;">*</span>':"")."</td><td class='last'>$stringObject</td></tr>";
}

function createSimpleButton($id, $label, $name = '', $attributes = '') {
	if($name == '')$name = $label;
	return "<button type='button' id='$id' name='$name' $attributes>$label</button>";
}

function createCheckbox($id, $label, $name = '', $attributes = '') {
	if($name == '')$name = $label;
	return "<input type='checkbox' id='$id' name='$name' $attributes class='checkableHeaders' /><label for='$id'>$label</label>";
}

function createInputTextArea($id, $name = '', $attributes = '', $startingValue = '') {
	if($name == '') $name = $id;
	return "<textarea id='$id' name='$name' $attributes>$startingValue</textarea>";
}

function createInputText($id, $name = '', $attributes = '', $startingValue ='') {
	if($name == '')$name = $id;
	if($startingValue != '') $startingValue = " value='$startingValue'";
	return "<input type='text' id='$id' name='$name' $attributes $startingValue />";
}

function createInputHidden($id, $name = '', $attributes = '', $startingValue = '') {
	if($name == '')$name = $id;
	if($startingValue != '') $startingValue = " value='$startingValue'";
	return "<input type='hidden' id='$id' name='$name' $attributes $startingValue />";
	
}
	
function emptySelect($name = 'genericSelect', $type = 'Object', $attributes = '') {
	$stringSelect = "
	<select name='$name' id='$name' style='font-family:verdana;font-size:8pt;width:150px' $attributes>
		<option value='0' selected='selected'>--Select $type--</option>
	</select>
	";
	return $stringSelect;
}

function createGenericNyroableAttributesSmall($objectId, $objectType) { return createGenericNyroableAttributes($objectId, 'small', $objectType);}
function createGenericNyroableAttributesMedium($objectId, $objectType) { return createGenericNyroableAttributes($objectId, 'medium', $objectType);}
function createGenericNyroableAttributesLarge($objectId, $objectType) { return createGenericNyroableAttributes($objectId, 'large', $objectType);}

function createGenericNyroableAttributes($objectId, $size, $objectType) {
	$sizeAttributes = '';
	if($size == 'small') {
		$sizeAttributes = SIZE_SMALL_ATTR;
	}
	if($size == 'medium') {
		die('Code not ready');
	}
	if($size == 'large') {
		die('Code not ready');
	}
	if($objectType == 'estimate') return createNyroableAttributes('viewEstimate','estimateId',$objectId, $sizeAttributes);
	if($objectType == 'project') return createNyroableAttributes('viewProject','projectId',$objectId, $sizeAttributes);
	if($objectType == 'customer') return createNyroableAttributes('viewCustomer','customerId',$objectId, $sizeAttributes);
	if($objectType == 'broker') return createNyroableAttributes('viewBroker','brokerId',$objectId, $sizeAttributes);
	if($objectType == 'driver') return createNyroableAttributes('viewDriver','driverId',$objectId, $sizeAttributes);
	if($objectType == 'truck') return createNyroableAttributes('viewTruck','truckId',$objectId, $sizeAttributes);
	if($objectType == 'contact') return createNyroableAttributes('viewContact','contactId',$objectId, $sizeAttributes);
	if($objectType == 'vendor') return createNyroableAttributes('viewVendor','vendorId',$objectId, $sizeAttributes);
	if($objectType == 'supplier') return createNyroableAttributes('viewSupplier','supplierId',$objectId, $sizeAttributes);
	if($objectType == 'item') return createNyroableAttributes('viewItem','itemId',$objectId, $sizeAttributes);
	if($objectType == 'ticket') return createNyroableAttributes('viewTicket','ticketId',$objectId, $sizeAttributes);
	die("Unimplemented object type: $objectType");
}

function createProjectNyroableAttributesSmall($projectId) { return createNyroableAttributes('viewProject','projectId',$projectId, SIZE_SMALL_ATTR); }
function createCustomerNyroableAttributesSmall($customerId) { return createNyroableAttributes('viewCustomer','customerId',$customerId, SIZE_SMALL_ATTR); }
function createBrokerNyroableAttributesSmall($brokerId) { return createNyroableAttributes('viewBroker','brokerId',$brokerId, SIZE_SMALL_ATTR); }

function createNyroableAttributes($url, $attrName, $attrValue, $additionalAttributes = '') {
	return " ".NYRO_URL_REF."='$url' ".NYRO_ATTR_NAME."='$attrName' $attrName='$attrValue' $additionalAttributes";
}

/**
 * Code for an img with (Source, Class, Tittle, Attributes)
 */
function printImgLink($src, $class, $title, $attributes) {
	return "
		<img
			src='$src'
			class='$class'
			title='$title'
			$attributes
			/>";
}

function printRow($rowAttributes, $columns) {
	$row = "<tr $rowAttributes >";
	foreach($columns as $column) {
		$row.="<td>$column</td>";
	}
	return $row.="</tr>";
}

function printTuple($rowAttributes, $atribute, $value, $viewLink = '') {
	$valueString = "<strong>$value</strong> $viewLink";
	if($value == null || $value == '') $valueString = 'N/A';
	return "
		<tr $rowAttributes>
			<td class='first'  width='172'>$atribute</td>
			<td class='last'>
				$valueString
			</td>
		</tr>";
}

function getTruckCheckedFeatures($handler, $truckId) {
	$features = mysql_query("select * from truckfeature where truckId = '$truckId'", $handler);
	$featureString = "";
	$glue = "";
	while($feature = mysql_fetch_assoc($features)) {
		$featureString .= $glue . $feature['featureId'];
		$glue = "~";
	}
	return $featureString;
}

function featureArrayToCheckBoxes($array, $width = 1, $checked = '') {
	$featureString = "<table><tr>";
	$i = 0;
	$checkedArray = array();
	if($checked != '') {
		$checkedFeatures = explode("~",$checked);
		foreach($checkedFeatures as $checkedFeature) {
			$checkedArray[$checkedFeature] = "true";
		}
	}
	$checkValue = "";
	foreach($array as $id=>$value) {
		if($i >= $width) {
			$featureString.="</tr><tr>";
			$i = 0;
		}
		if($checkedArray[$id] == "true"){ $checkValue = " checked='checked'";}
		else {$checkValue = "";}
		$featureString.="<td align='right'><input type='checkbox' $checkValue id='$value' name='truckFeatures' class='featureCheckbox' value='$id'><label for='$value'><strong>$value</strong></label></td>";
		$i++;
	}
	return $featureString."</tr></table>";
}

function arrayToSelect($array, $selected = 0, $name = 'genericSelect', $type = 'Object', $ignoreFirst = false, $attributes = '') {
	$stringSelect = "<select name='$name' id='$name' style='font-family:verdana;font-size:8pt;width:150px' $attributes>";
	if(!$ignoreFirst)$stringSelect.="<option value='0' selected='selected'>--Select $type--</option>";
	foreach($array as $id=>$value) {
		if($id == $selected) $stringSelect.="<option selected='selected' value='$id'>$value</option>";
		else $stringSelect.="<option value='$id'>$value</option>";
	}
	$stringSelect.="</select>";
	return $stringSelect;
}

function arrayToRadio($array, $selected = '', $name = 'genericRadio', $type = '<br/>', $ignoreFirst = false) {
	$stringRadio = "";
	foreach($array as $id=>$value) {
		$stringRadio.="<input type='radio' ".($selected == $id ? "checked='checked'" : "")." id='$name$id' name='$name' value='$id' /><label for='$name$load'>$value</label>";
	}
	return $stringRadio;
}

function getCustomerSuperCheckInfo($handler, $checkId) {return objectQuery($handler, '*', 'customer_super_check LEFT JOIN customer_credit USING (customerSuperCheckId)',"customerSuperCheckId = '$checkId'");}

function getCustomerInvoiceInfo($handler, $invoiceId) {return objectQuery($handler, '*', 'invoice JOIN project USING (projectId) JOIN customer USING (customerId)',"invoiceId='$invoiceId'");}

function getBasicEstimateInfo($handler, $estimateId) { return objectQuery($handler,'*','fakeproject',"fakeprojectId = '$estimateId'");}
function getBasicReportInfo($handler, $reportId) { return objectQuery($handler, '*', 'report', "reportId = '$reportId'");}
function getBasicDriverInfo($handler, $driverId) { return objectQuery($handler, '*', 'driver join address using (addressId) join term using (termId)', 'driverId = '.$driverId);}
function getBasicBrokerInfo($handler, $brokerId) { return objectQuery($handler, '*', 'broker join address using (addressId) join term using (termId)', 'brokerId = '.$brokerId);}
function getSupplierInfo($handler, $supplierId) { return objectQuery($handler, '*', 'supplier join address using (addressId)', 'supplierId = '.$supplierId);}
function getBasicVendorInfo($handler, $vendorId) { return objectQuery($handler, '*', 'vendor', 'vendorId = '.$vendorId);}
function getBasicTruckInfo($handler, $truckId) { return objectQuery($handler, '*', 'truck', 'truckId = '.$truckId);}
function getBasicCustomerInfo($handler, $customerId) { return objectQuery($handler, '*', 'customer', 'customerId = '.$customerId);}
function getBasicProjectInfo($handler, $projectId) { return objectQuery($handler, '*', 'project', 'projectId = '.$projectId);}
function getEstimateInfo($handler, $estimateId) { return objectQuery($handler, '*', 'fakeproject join address USING (addressId)', 'fakeprojectId = '.$estimateId);}
function getProjectInfo($handler, $projectId) { return objectQuery($handler, '*', 'project join address USING (addressId)', 'projectId = '.$projectId);}
function getBasicSupplierInfo($handler, $supplierId) { return objectQuery($handler, '*', 'supplier', 'supplierId = '.$supplierId);}
function getAddressInfo($handler, $addressId) { return objectQuery($handler, '*', 'address', 'addressId = '.$addressId);}

function objectQuery($handler, $select, $from, $where) {
	$objectQuery = "SELECT $select FROM $from ".($where==''?'':" WHERE $where");
	return objectInfo($handler, $objectQuery);
}

//Use only for single objects
function objectInfo($handler, $query) {
	//echo $query;
	return mysql_fetch_assoc(mysql_query($query, $handler));
}

function createEthnicitySelect($handler, $name = 'ethnicId', $selected = "0") {
	$ethnicArray = objectArray($handler, 'ethnic', 'ethnicName asc', 'ethnicId', 'ethnicName');
	return arrayToSelect($ethnicArray, $selected, $name, 'Ethnicity');
}

function createTermSelect($handler, $name = 'termId', $selected = "0") {
	$termArray = objectArray($handler, 'term', 'termName asc', 'termId', 'termName');
	return arrayToSelect($termArray, $selected, $name, 'Term');
}

function createCarrierSelect($handler, $name = 'carrierId', $selected = "0") {
	$carrierArray = objectArray($handler, 'carrier', 'carrierName asc', 'carrierId', 'carrierName');
	return arrayToSelect($carrierArray, $selected, $name, 'Carrier');
}

function createStateSelect($handler, $name = 'stateId', $selected = "0") {
	$stateArray = objectArray($handler, 'state', 'stateName asc', 'stateId', 'stateName');
	return arrayToSelect($stateArray, $selected, $name, 'State');
}

function materialBySupplierArray($handler, $supplierId = 0) {return objectArray($handler, "material ".($supplierId == 0 ? "" : "JOIN suppliermaterial USING (materialId) WHERE supplierId = '$supplierId'"),"materialName asc","materialId","materialName");}
function estimatesArray($handler, $customerId = 0) {return objectArray($handler, "fakeproject ".($customerId == 0 ? "" : " WHERE customerId = '$customerId' "),"fakeprojectId desc", "fakeprojectId", "fakeprojectId,fakeprojectName");}
function projectsArray($handler, $customerId = 0) {return objectArray($handler, "project ".($customerId == 0 ? "" : " WHERE customerId = '$customerId' "),"projectId desc", "projectId", "projectId,projectName");}
function trucksArray($handler, $brokerId = 0) {return objectArray($handler, "truck JOIN broker USING (brokerId) ".($brokerId == 0 ? "" : " WHERE brokerId = '$brokerId' "), 'truckNumber','truckId','brokerPid,truckNumber');}
function suppliersArray($handler) { return objectArray($handler, 'supplier', 'supplierName asc', 'supplierId', 'supplierName'); }
function customersArray($handler) { return objectArray($handler, 'customer', 'customerName asc', 'customerId', 'customerName'); }
function brokersArray($handler) { return objectArray($handler, 'broker', 'brokerName asc', 'brokerId', 'brokerName'); }
function vendorsArray($handler) { return objectArray($handler, 'vendor', 'vendorName asc', 'vendorId', 'vendorName'); }
function ethnicsArray($handler) { return objectArray($handler, 'ethnic', 'ethnicName asc', 'ethnicId', 'ethnicName'); }
function materialsArray($handler) { return objectArray($handler, 'material', 'materialName asc', 'materialId', 'materialName'); }
function termsArray($handler) { return objectArray($handler, 'term', 'termName asc', 'termId', 'termName'); }

function objectArray($handler, $table, $orderBy, $id, $values) {
	$objectQuery = "SELECT * FROM $table ".($orderBy == "" ? "" : "ORDER BY $orderBy");
	$objects = mysql_query($objectQuery, $handler);
	$objectArray = array();
	while($object = mysql_fetch_assoc($objects)) {
		$objectArray[$object[$id]] = mapValues($object, $values);
	}
	return $objectArray;
}

function mapValues($objectArray, $valuesString, $separator = ',', $prefix = '', $suffix = ' ') {
	$valuesArray = explode($separator,$valuesString);
	$valueString = "";
	foreach($valuesArray as $value) {
		$valueString.=$prefix.$objectArray[$value].$suffix;
	}
	return $valueString;
}

function mapValuesWithTypes($objectArray, $valueTypeMap, $prefix = '', $suffix = ' ') {
	$valueString = ""; 
	//Types admitted String, Double, Date
	foreach($valueTypeMap as $value=>$type) {
		if($type == 'double') $valueString.=$prefix.decimalPad($objectArray[$value]).$suffix;
		else if($type == 'date') $valueString.=$prefix.to_MDY($objectArray[$value]).$suffix;
		else $valueString.=$prefix.$objectArray[$value].$suffix;
	}
	return $valueString;
}

function createTypeMap($values, $types, $separatorV = ',', $separatorT = '') {
	if($separatorT == '')$separatorT = $separatorV;
	$valuesArray = explode($separatorV, $values);
	$typesArray = explode($separatorT, $types);
	$typeMap = array();
	for($i = 0; $i < sizeOf($valuesArray); $i++) {
		$typeMap[$valuesArray[$i]] = isset($typesArray[$i]) ? $typesArray[$i] : 'string';
	}
	return $typeMap;
}

function contains($haystack,$needle) {
	$pos = strpos($haystack,$needle);
	if($pos === false)
		return false;
	else
		return true;
}

function startsWith($haystack, $needle) {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

function endsWith($haystack, $needle) {
    $length = strlen($needle);
    $start  = $length * -1; //negative
    return (substr($haystack, $start) === $needle);
}

function cleanPhoneNumber($str) {
	$num = preg_replace('%[^0-9]%',"",$str);
	return $num;
}

function showPhoneNumber($num) {
	$len=strlen($num);
	if($len==0) {
		return "N/A";
	}
	if($len<10) {
		$str="";
		$count=0;
		for($i=$len-1;$i>0;$i--) {
			$count++;
			$str=$num[$i].$str;
			if($count==3) {
				$str=" ".$str;
				$count=0;
			}
		}
		$str=$num[0].$str;
	}
	if($len==10) {
		$str="(".substr($num,0,3).") ".substr($num,3,3)." ".substr($num,6);
	}
	if($len>10) {
		$str=substr($num,0,$len-10)." (".substr($num,$len-10,3).") ".substr($num,$len-7,3)." ".substr($num,$len-4);
	}
	return $str;
}

function getDateWeek($ymdWeek){
	return date('W', strtotime($ymdWeek));
}

//Takes a date a return the next saturday
function getNextSaturday($date){
	
	$now = strtotime($date);
	$nextSaturday = strtotime('next Saturday', $now);
	return date('Y-m-d',$nextSaturday);
}

//Takes a date in 'Y-m-d' and returns the last sunday before that day. If that day is a sunday it will return the one before.
function lastSunday($date){
	return (date('Y-m-d', strtotime('last Sunday', strtotime($date)) ));
}

//Takes a function in 'Y-m-d' notation
function isSunday($date){
	return (date('N', strtotime($date)) == 7);
}

//converts from YYYY-MM-DD to mm/dd/yyyy
function to_MDY($date2conv, $short = false) {
	//$m=substr($date2conv,5,2);
	$m=substr($date2conv,strpos($date2conv,"-")+1,strpos($date2conv,"-",strpos($date2conv,"-")+1)-strpos($date2conv,"-")-1);
	//$d=substr($date2conv,8,2);
	$d=substr($date2conv,strrpos($date2conv,"-")+1);
	//$y=substr($date2conv,0,4);
	$y=substr($date2conv,0,strpos($date2conv,"-"));
	if($short) $y = substr($y,2);
	return($m.'/'.$d.'/'.$y);
}

//converts from mm/dd/yyyy to YYYY-MM-DD
function to_YMD($date2conv) {
	$m=substr($date2conv,0,strpos($date2conv,"/"));
	$d=substr($date2conv,strpos($date2conv,"/")+1,strpos($date2conv,"/",strpos($date2conv,"/")+1)-strpos($date2conv,"/")-1);
	$y=substr($date2conv,strrpos($date2conv,"/")+1);
	return($y.'-'.$m.'-'.$d);
}

function b2t($b) {
	if($b)
		return 1;
	else
		return 0;
}

function decimalPad($value, $positions = 3) {
	$value = round($value, $positions);
	$pos = strpos($value, ".");
	if($pos===false) {
		$value.= ".".str_repeat("0", $positions);
	} else {
		if(strlen($value)==$pos+$positions+1) $value = $value;
		if(strlen($value)<$pos+$positions+1) $value .= str_repeat("0",($positions - (strlen($value) - ($pos+1))));
		if(strlen($value)>$pos+$positions+1) $value = substr($value,0,($pos+$positions+1));
	}
	return $value;
}

function p_array($array) {
	echo '<PRE>';
	print_r($array);
	echo '</PRE>';
}

function getReportBalance($reportId, $conexionHandler){
	$totalReported = getReportTotal($reportId, $conexionHandler);
	$totalPaid = getReportPaid($reportId, $conexionHandler);
	return decimalPad($totalReported - $totalPaid);
	
}

function getInvoiceTotal($invoiceId, $conexionHandler){
	$queryTotal = "
		SELECT
			SUM( itemCustomerCost * ticketAmount ) as invoiceTotal
		FROM
			invoiceticket
			JOIN ticket using (ticketId)
			JOIN item using (itemId)
		WHERE
			invoiceId = $invoiceId
	";
	$invoiceInfo = mysql_fetch_assoc(mysql_query($queryTotal, $conexionHandler));
	return decimalPad($invoiceInfo['invoiceTotal']);
}

function getInvoicePaid($invoiceId, $conexionHandler){
	$queryPaid = "
		SELECT
			SUM( receiptchequesAmount ) as paidTotal
		FROM
			receiptcheques
		WHERE
			invoiceId = $invoiceId
	";
	$paidInfo = mysql_fetch_assoc(mysql_query($queryPaid, $conexionHandler));
	return decimalPad($paidInfo['paidTotal']);
}

function getReportPaid($reportId, $conexionHandler){
	$queryPaid = "
		SELECT
			SUM(paidchequesAmount) as paidTotal
		FROM
			report
			JOIN paidcheques using (reportId)
		WHERE reportId = $reportId
	";
	$paidInfo = mysql_fetch_assoc(mysql_query($queryPaid, $conexionHandler));
	return decimalPad($paidInfo['paidTotal']);
}

function getReportTotal($reportId, $conexionHandler){
	$queryTotal = "
		SELECT
			SUM( (ticketBrokerAmount * itemBrokerCost) * (if(item.itemDescription like 'toll%', 100, if(driver.driverId is null, broker.brokerPercentage, driver.driverPercentage ) ) )/100 ) as totalReported
		FROM
			reportticket
			JOIN report using (reportId)
			JOIN ticket using (ticketId)
			JOIN item using (itemId)
			JOIN broker using (brokerId)
			LEFT JOIN driver on (driver.driverId = report.reportType)
		WHERE
			reportId = ".$reportId."
	";
	$reportInfo = mysql_fetch_assoc(mysql_query($queryTotal, $conexionHandler));
	return decimalPad($reportInfo['totalReported']);
}

//Get the total paid for a supplier invoice
function getSuppliedPaid($invoiceId, $handler) {
	$queryTotal = "
		SELECT
			SUM(supplierchequeAmount) as totalPaid
		FROM
			suppliercheque
		WHERE
			supplierInvoiceId = '".$invoiceId."'
		";
	$suppliedInfo = mysql_fetch_assoc(mysql_query($queryTotal, $handler));
	return decimalPad($suppliedInfo['totalPaid']);
}

function getNextAutoIncrement($handler, $table) {
	$result = mysql_fetch_assoc(mysql_query("show table status like '$table'", $handler));
	return $result['Auto_increment'];
}

function getVendorSupplierCount($handler, $vendorId) {return getElementCount($handler, 'supplier',"vendorId = '$vendorId'");}

function getItemTicketCount($handler, $itemId) { return getElementCount($handler, 'ticket', "itemId = '$itemId'"); }
function getSupplierTicketCount($handler, $supplierId) {return getElementCount($handler, 'ticket JOIN item USING (itemId)',"supplierId = '$supplierId'");}
function getVendorTicketCount($handler, $vendorId) {return getElementCount($handler, 'ticket JOIN item USING (itemId) JOIN supplier USING (supplierId)',"vendoriId = '$vendorId'");}
function getBrokerTicketCount($handler, $brokerId) { return getElementCount($handler, 'ticket JOIN truck USING (truckId)', "brokerId = '$brokerId'"); }
function getDriverTicketCount($handler, $driverId) { return getElementCount($handler, 'ticket', "driverId = '$driverId'"); }
function getTruckTicketCount($handler, $truckId) { return getElementCount($handler, 'ticket', "truckId = '$truckId'"); }

function getElementCount($handler, $table, $conditions = '1=1') {
	$sqlObject = mysql_fetch_assoc(mysql_query("SELECT count(*) AS accounted FROM $table WHERE $conditions", $handler));
	return $sqlObject['accounted'];
}

?>
