function evalDate(date){
	var patternDate = new RegExp(/(\d+)\/(\d+)\/(\d+)/);
	patternDate.compile(patternDate);
	if(date.match(patternDate)){
		date=date.replace(patternDate,'$3-$1-$2');
		return date;
	}else{return '0';}
}

function printSupplierInvoice(id) {printInvoice("Supplier", id);}
function printBrokerInvoice(id) {printInvoice("Broker", id);}
function printCustomerInvoice(id) {printInvoice("Customer", id);}

function printInvoice(type, invoiceId) {
	var url = "../reports/show" + type + "Invoice.php?invoiceId=" + invoiceId;
	var windowName = type + " Invoice [" + invoiceId +"]";
	var windowSize = 'width=814,heigh=514,scrollbars=yes';
	window.open(url,windowName,windowSize);
}

function getContactsOptions(objectId, customerId) {
	getExternalOptions(
		"../retrieve/getExternalOptions.php", 
		prepareExternalData(objectId,'Contact','contact','customerId',customerId,'contactName','asc','contactId','contactName')
		);
}

function getDriversOptions(objectId, brokerId){
	getExternalOptions(
		"../retrieve/getExternalOptions.php", 
		prepareExternalData(objectId,'Driver','driver','brokerId',brokerId,'driverLastName','asc','driverId','driverLastName_driverFirstName')
		);
}

function getTrucksOptions(objectId, brokerId){
	getExternalOptions(
		"../retrieve/getExternalOptions.php", 
		prepareExternalData(objectId,'Truck','truck','brokerId',brokerId,'truckNumber','asc','truckId','truckNumber')
		);
}

function getMaterialsOptions(objectId, supplierId){
	getExternalOptions(
		"../retrieve/getExternalOptions.php", 
		prepareExternalData(objectId,'Material','material JOIN suppliermaterial USING (materialId)','supplierId',supplierId,'materialId','asc','materialId','materialName')
		);
}

function getEstimatesOptions(objectId, customerId){
	getExternalOptions(
		"../retrieve/getExternalOptions.php", 
		prepareExternalData(objectId,'Estimate','fakeproject','customerId',customerId,'fakeprojectId','asc','fakeprojectId','fakeprojectId_fakeprojectName')
		);
}

function getProjectsOptions(objectId, customerId){
	getExternalOptions(
		"../retrieve/getExternalOptions.php", 
		prepareExternalData(objectId,'Project','project','customerId',customerId,'projectId','asc','projectId','projectId_projectName')
		);
}

function getItemsOptions(objectId, projectId){
	getExternalOptions(
		"../retrieve/getExternalOptions.php", 
		prepareExternalData(objectId,'Item','item','projectId',projectId,'itemId','asc','itemId','itemNumber_itemDisplayFrom_itemDisplayTo')
		);
}

function getSuppliersOptions(objectId, vendorId){
	getExternalOptions(
		"../retrieve/getExternalOptions.php", 
		prepareExternalData(objectId,'Supplier','supplier','vendorId',vendorId,'supplierId','asc','supplierId','supplierName')
		);
}

function prepareExternalData(objectId, objectName, table, where, condition, order, type, id, values) {
	return "objectId=" + objectId + 
		"&objectName=" + objectName +
		"&table=" + table +
		"&where=" + where +
		"&condition=" + condition +
		"&order=" + order +
		"&type=" + type +
		"&id=" + id +
		"&values=" + values;
}

function submitDataToUrl(url, data) {
	$.ajax({
		type:	"GET",
		url:	url,
		data:	data,
		success: function(data){doAfterSubmit(data);},
		async: true
	});
}

function getExternalOptions(url, data) {
	$.ajax({
		type:	"GET",
		url:	url,
		data:	data,
		success:function(data) {
			try{
				var obj = jQuery.parseJSON(data);
				var selectObj = $('#' + obj.objectId);
				selectObj.children().remove();
				jQuery.each(obj.options, function(i, val) {
					selectObj.append("<option value='" + i + "'>" + val + "</option>");
				});
			} catch(e) {
				alert("Internal Error: Please contact the administrator.");
			}
		},
		async: true
	});
}

function getExternalTable(url, data) {
	$.ajax({
		type:	"GET",
		url:	url,
		data:	data,
		success:function(data) {
			try{
				var obj = jQuery.parseJSON(data);
				var tableDiv = $('#' + obj.objectId);
				tableDiv.empty();
				tableDiv.append(obj.table);
			} catch(e) {
				alert("Internal Error: Please contact the administrator.");
			}
		},
		async: true
	});
}

function getVal(objectId) {
	if($('#'+objectId).val() == undefined){ console.log("value undefined for [" + objectId + "]");return '';}
	return escape($('#'+objectId).val());
}

function getRadioVal(objectName) {
	return $('input:radio[name=' + objectName +']:checked').val();
}

function submitNewObject(url, data) {
	$.ajax({
		type:	"GET",
		url:	url,
		data:	data,
		success: function(data) {
			doAfterSubmit(data);
		},
		async:	true
	});
}

function deleteElement(url, data) {
	$.ajax({
		type:	"GET",
		url:	url,
		data:	data,
		success: function(data) {
			doAfterDelete(data);
		},
		async:	true
	});
}

function arrayToDataString(dataArray) {
	var data = "";
	var glue = "";
	for(key in dataArray) {
		data += glue + key + "=" + dataArray[key];
		glue = "&";
	}
	return data;
}

/*
 * On polymorphism
function addEvent(obj,evt,fn) {
  if (document.addEventListener) {
    addEvent = function (obj,evt,fn) {
      obj.addEventListener(evt,fn,false);
    }
  }
  else if (document.attachEvent) {
    addEvent = function (obj,evt,fn) {
      obj.attachEvent('on'+evt,fn);
    }
  }
  addEvent(obj,evt,fn);
}
* This function effectively rewrites itself the first time it is called by creating another function having the same name as the original function. As this new function has been added to the page after the original function, it will be the one that is used for all subsequent calls to that function name but because the second function by that name has not yet been added to the page the first time the function is called, te original function (the one that recreates itself) will be the one called the first time that the function is called.

This allows us to dramatically reduce the feature sensing code that we would otherwise need in our scripts in order to determine what code we need to run based on the features supported.

This particular example uses feature sensing to add event processing to the web page. If the browser is running JavaScript that supports addEventListener() then the addEvent() function will replace itself with a function that calls addEventListener. If the browser is running JScript which supports attachEvent() then the function will replace itself with a function that calls attachEvent. In effect there are three different addEvent() functions in our web page. The first is called the first time the function is called. That function works out which of the two features that the browser supports and replaces itself with a function that uses that feature to do the processing. It then calls that replacement function in order to perform that task. Subsequent calls to the function will reference the added function directly as it has been added to the page after the original function. Since this function does not perform the feature sensing it will run more efficiently. As it is impossible for a browser to suddenly stop supporting one feature and start supporting another in the middle of processing a script we do not need to perform feature sensing tests more than once in processing the page and so a polymorphic function like this is way more efficient than the more conventional approach of testing the feature every time would be.
*/
