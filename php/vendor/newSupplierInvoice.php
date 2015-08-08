<?
$title = "MFI";
$subtitle = "Vendor";

$tab = "VENDOR";

include_once '../app_header.php';
?>
<div id="middle">
<?include_once 'navigation.php';?>

<script type="text/javascript">
var currentTickets = new Array();
var totalAmount = 0;
var totalTicket = 0;

$(document).ready(function() {
	
	//$('.add-ticket').on('click',function(){
	$(document).on('click','.add-ticket',function(){
		var id = $(this).closest("tr").attr('ticketId');
		//console.log(id);
		if(id!=undefined){
			moveTicket(id);
			$(this).closest("tr").remove();
		}
	});
	
	//$('.removable').on('click',function(){
	$(document).on('click','.removable',function(){
		var toSubstract = $(this).closest("tr").find(".sumable").text();
		var ticketId = $(this).closest('tr').attr('ticketId');
		totalAmount = parseFloat(totalAmount) - parseFloat(toSubstract);
		totalTicket = parseInt(totalTicket) - 1;
		updateAmount(totalAmount);
		updateCount(totalTicket);
		delete currentTickets[ticketId];
		$(this).closest("tr").remove();
	});
	
	$('#submitNewVendorInvoice').click(function() {
		submitNewVendorInvoice();
	});
	
	$('#previewButton').click(function() {
		previewBalance();
	});
	
	$('#vendorId').change(function() {
		getSuppliersOptions('supplierId',$(this).val());
		//$('#previewButton').removeAttr('disabled');
	});
	
	$('#vendorInvoiceSearchTickets').click(function() {
		getSupplierTickets();
	});
});

function moveTicket(ticketId){
	if(typeof currentTickets[ticketId] !== 'undefined'){return 0;}
	currentTickets[ticketId] = "1";
	var projectName = $("#projectName"+ticketId).val();
	var ticketDate = $("#ticketDate"+ticketId).val();
	var materialName = $("#materialName"+ticketId).val();
	var ticketMfi = $("#ticketMfi"+ticketId).val();
	var ticketNumber = $("#ticketNumber"+ticketId).val();
	var price = $("#price"+ticketId).val();
	
	var newRow = $("<tr ticketId='"+ticketId+"' ></tr>").addClass('accountable');
	newRow.append("<td>"+projectName+"</td>");
	newRow.append("<td>"+ticketDate+"</td>");
	newRow.append("<td>"+materialName+"</td>");
	newRow.append("<td>"+ticketMfi+"</td>");
	newRow.append("<td>"+ticketNumber+"</td>");
	newRow.append("<td class='sumable'>"+price+"</td>");
	newRow.append("<td><img src='/trucking/img/118.png' class='removable' width='22px' height='20px' ></td>");
	totalAmount = parseFloat(price) + parseFloat(totalAmount);
	updateAmount(totalAmount);
	totalTicket = parseInt(totalTicket) + 1;
	updateCount(totalTicket);
	$("#supplierTickets").append(newRow);
}

function updateAmount(newAmount){
	$("#amountSum").text(newAmount.toFixed(2));
}

function updateCount(newCount){
	$("#amountCount").text("( "+newCount+" )");
}



function addTickets(){
	console.log("adding tickets...");
	if($('.accountable').length){
		var first = true;
		var ticketList = "";
		$('.accountable').each(function(index, obj){
			if(first){ first = false;}
			else{ ticketList += "-"; }
			
			var ticketId = obj.getAttribute("ticketId");
			ticketList += ticketId;
		});
		console.log(ticketList);
		$("#hiddenTickets").val(ticketList);
		
	}
}

function submitNewVendorInvoice() {
	var data = getVendorInvoiceParams();
	var url = "../submit/submitNewVendorInvoice.php";
	submitNewObject(url, data);
}

function getVendorInvoiceParams() {
	
	var dataArray = new Array();
	dataArray['vendorId'] = getVal('vendorId');
	dataArray['supplierId'] = getVal('supplierId');
	dataArray['invoice'] = getVal('invoiceNumber');
	dataArray['date'] = evalDate(getVal('invoiceDate'));
	dataArray['amount'] = getVal('invoiceAmount');
	dataArray['current'] = totalAmount;
	dataArray['comment'] = getVal('invoiceComment');
	dataArray['tickets'] = getCurrentTickets();
	return arrayToDataString(dataArray);
}

function getSupplierTickets() {
	var dataArray = new Array();
	dataArray['vendorId'] = getVal('vendorId');
	dataArray['supplierId'] = getVal('supplierId');
	dataArray['startDate'] = evalDate(getVal('startDate'));
	dataArray['endDate'] = evalDate(getVal('endDate'));
	dataArray['projectId'] = getVal('projectId');
	dataArray['limit'] = "200";
	dataArray['currentActive'] = getCurrentTickets();
	dataArray['invoiced'] = $("input[name='inInvoice']:checked").val();
	$.ajax({
		type: "GET",
		url: "../retrieve/getNewVendorInvoiceTickets.php",
		data: arrayToDataString(dataArray),
		success:function(data){
			var obj=jQuery.parseJSON(data);
			console.log(obj);
			if(obj.error==null){
				if(obj.table!=null){
					$('#tickets > tbody:last').remove();
					//$('#tickets').empty();
					$('#tickets').append(obj.table);
				}
			}else{alert('Error: '+obj.error);}
		},
		async: false
	});
}

function getCurrentTickets() {
	var data = "";
	var glue = "";
	for(key in currentTickets) {
		data += glue + key;
		glue = "~";
	}
	return data;
}

function disableButton() {
	$('#submitButton').attr('disabled','disabled');
}

function enableButton() {
	$('#submitButton').removeAttr('disabled');
}

function doAfterSubmit(data) {
	console.log(data);
	try{
		var obj = jQuery.parseJSON(data);
		switch(obj.code){
			case 0:
				alert("Invoice created successfully");
				location.reload();
				break;
			case -1:
				alert(obj.msg);
				$('#' + obj.focus).focus();
				break;
			default:
				alert(obj.msg);
				break;
		}
	} catch(e) {
		alert("Internal Error: Please contact the administrator.");
	}
	enableButton();
}
</script>
<div id="center-column">
	<div class="table">
		<table class="listing form" cellpadding="0" cellspacing="0">
			<tr>
				<th class="full" colspan="4">View Broker Balance</th>
			</tr>
			<tr>
				<td><strong>Vendor:</strong><span style="color:red;">*</span></td>
				<td><strong>Invoice #:</strong><span style="color:red;">*</span></td>
				<td><strong>Date:</strong><span style="color:red;">*</span></td>
				<td><strong>Amount:</strong><span style="color:red;">*</span></td>
			</tr>
			<tr class='bg'>
				<td><?php echo arrayToSelect(vendorsArray($conexion), 0, 'vendorId', 'Vendor'); ?></td>
				<td><?php echo createInputText('invoiceNumber','',"size='10px'");?></td>
				<td><?php echo createInputText('invoiceDate','',"size='10px'");?></td>
				<td><?php echo createInputText('invoiceAmount','',"size='10px'");?></td>
			</tr>
			<tr>
				<td><strong>Supplier:</strong><span style="color:red;">*</span></td>
				<td colspan='2'><strong>Coment:</strong></td>
				<td><strong>Current Amount:</strong></td>
			</tr>
			<tr class='bg'>
				<td><?php echo emptySelect("supplierId","Supplier");?></td>
				<td colspan='2'><?php echo createInputTextArea('invoiceComment','','rows="1" cols="52"');?></td>
				<td id='amountSum'></td>
			</tr>
			<tr>
				<td><strong>Start Date:</strong></td>
				<td><?php echo createInputText('startDate','',"size='10px'");?></td>
				<td><strong>Ticket Count:</strong></td>
				<td id='amountCount'></td>
			</tr>
			<tr class='bg'>
				<td><strong>End Date:</strong></td>
				<td><?php echo createInputText('endDate','',"size='10px'");?></td>
				<td><strong>Project #:</strong></td>
				<td><?php echo createInputText('projectId','',"size='10px'");?></td>
			</tr>
			<tr>
				<td><?php echo createSimpleButton('submitNewVendorInvoice','Submit');?></td>
				<td></td>
				<td>
					<input type='radio' name='inInvoice' value='no' id='noInInvoice' checked ><label for='noInInvoice'>Not in invoice</label><br/>
					<input type='radio' name='inInvoice' value='yes' id='yesInInvoice' ><label for='yesInInvoice'>In invoice</label><br/>
					<input type='radio' name='inInvoice' value='all' id='allInInvoice' ><label for='allInInvoice'>All</label><br/>
				</td>
				<td><?php echo createSimpleButton('vendorInvoiceSearchTickets','Search');?></td>
			</tr>
			<?php 
			?>
		</table>
	</div>
			<div class='table'>
				<img src='/trucking/img/bg-th-left.gif' width='8' height='7' alt='' class='left' />
				<img src='/trucking/img/bg-th-right.gif' width='7' height='7' alt='' class='right' />
				<table class='listing form' cellpadding='0' cellspacing='0' id='supplierTickets'>
					<tr>
						<th class='first' >Job</th>
						<th>Date</th>
						<th>Material</th>
						<th>MFI</th>
						<th>Dump</th>
						<th>Amount</th>
						<th class='last' >Remove</th>
					</tr>
				</table>
			</div>
			<div class='iframes' id='previewFrame' >
			</div>
			<div class='table'>
				<img src='/trucking/img/bg-th-left.gif' width='8' height='7' alt='' class='left' />
				<img src='/trucking/img/bg-th-right.gif' width='7' height='7' alt='' class='right' />
				<table class='listing form' cellpadding='0' cellspacing='0' id='tickets'>
					<thead>
					<tr>
						<th class='first'>Job</th>
						<th>Customer</th>
						<th>Item</th>
						<th>Date</th>
						<th>Material</th>
						<th>From</th>
						<th>MFI</th>
						<th>Ticket</th>
						<th>Material</th>
						<th colspan='2'>Customer</th>
						<th colspan='2'>Broker</th>
						<th class='last'>Add</th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td colspan='13'></td>
					</tr>
					</tbody>
				</table>
			</div>
	
	<div class='iframes' id='previewFrame'></div>
</div>
	
<?include_once '../news.php';?>
</div>
<?
include_once '../app_footer.php';
?>
