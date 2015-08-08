<?
$title = "MFI";
$subtitle = "Customer";

$tab = "CUSTOMER";

include_once '../app_header.php';
?>
<div id="middle">
<?include_once 'navigation.php';?>

<script type="text/javascript">
	var type = "generic";
$(document).ready(function() {
	
	$('#submitButton').click(function() {
		submitCustomerInvoice();
	});
	
	$('#previewButton').click(function() {
		previewBalance();
	});
	
	$('#customerId').change(function() {
		getProjectsOptions('projectId',$(this).val());
	});
	
	$('#projectId').change(function() {
		getMaterials($(this).val());
		getItems($(this).val());
		$('#previewButton').removeAttr('disabled');
	});
	
	$('#generalInvoiceType').change(function() {
			$('#materialId').val(0).hide();
			$('#itemId').val(0).hide();
			type = "generic";
	});
	
	$('#itemInvoiceType').change(function() {
			$('#materialId').val(0).hide();
			$('#itemId').show();
			type = "item";
	});
	
	$('#materialInvoiceType').change(function() {
			$('#itemId').val(0).hide();
			$('#materialId').show();
			type = "material";
	});
	
	$('#invoiceStartDate').blur(function(){
		var startDate = this.value;
		if(startDate != ""){
			startDate = startDate.replace(/(\d+)\/(\d+)\/(\d+)/,'$3/$1/$2');
			d =new Date(startDate);
			d.setDate((d.getDate() - d.getDay())+6);
			
		}else{
			d = new Date();
			b = new Date(d);
			$('#invoiceStartDate').val((b.getMonth()+1) + '/' + b.getDate() + '/' + b.getFullYear());
		}
		year = d.getFullYear()+'';
		month=d.getMonth()+1+'';
		day = d.getDate()+'';
		if(month.length==1)month='0'+month;
		if(day.length==1)day='0'+day;
		$('#invoiceEndDate').val(month+'/'+day+'/'+year);
	});
	
	$('#materialId').hide();
	$('#itemId').hide();
	$('#generalInvoiceType').attr('checked','checked');
});

function disableButton() {
	$('#submitButton').attr('disabled','disabled');
}

function enableButton() {
	$('#submitButton').removeAttr('disabled');
}

function doAfterSubmit(data) {
	var obj = jQuery.parseJSON(data);
	if(obj.code == 0) {
		//went without problems
		$('#previewFrame').empty();
		console.log(data);
	} else {
		
	}
}

function submitCustomerInvoice() {
	disableButton();
	var url = '../submit/submitCustomerInvoice.php';
	var data = getParams();
	submitDataToUrl(url, data);
}

function previewBalance() {
	var url = '../reports/previewCustomerInvoice.php?' + getParams();
	$('#previewFrame').empty();
	$('<iframe />',{
		name: 'Customer Invoice',
		id: 'previewedFrame',
		src: url
	}).width('100%').height('2048px').appendTo('#previewFrame');
	$('#submitButton').removeAttr('disabled');
}

function getVal(objectId) {
	return escape($('#'+objectId).val());
}

function getParams() {
	var dataArray = new Array();
	dataArray['project'] = getVal('projectId');
	dataArray['startDate'] = evalDate(getVal('invoiceStartDate'));
	dataArray['endDate'] = evalDate(getVal('invoiceEndDate'));
	dataArray['type'] = type;
	dataArray['material'] = getVal('materialId');
	dataArray['item'] = getVal('itemId');
	dataArray['comment'] = getVal('newCustomerInvoiceComment');
	var data = "";
	var glue = "";
	for(key in dataArray) {
		data += glue + key + "=" + dataArray[key];
		glue = "&";
	}
	return data;
}

function getMaterials(projectId) {
	$.ajax({
		type: "GET",
		url: "../retrieve/getMaterialsByProject.php",
		data: "projectId="+projectId,
		success:function(data){
			var obj=jQuery.parseJSON(data);
			var materialHolder = $('#materialId');
			materialHolder.children().remove();
			materialHolder.append("<option value='0' >--Select material--</option>");
			jQuery.each(obj, function(i,o){
				materialHolder.append("<option value='" + o.materialId + "' >" + o.materialName + "</option>");
			});
		},
		async: false
	});
}

function getItems(projectId) {
	$.ajax({
		type: "GET",
		url: "../retrieve/getItemsByProject.php",
		data: "projectId="+projectId,
		success:function(data){
			var obj=jQuery.parseJSON(data);
			var itemHolder = $('#itemId');
			itemHolder.children().remove();
			itemHolder.append("<option value='0' >--Select Item--</option>");
			jQuery.each(obj, function(i,o){
				itemHolder.append("<option value='" + o.itemId + "' >" + o.materialName + " @ " + o.itemMaterialPrice + " to " + o.itemDisplayTo + "</option>");
			});
		},
		async: false
	});
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
				<th class="full" colspan="4">View Customer Balance</th>
			</tr>
			<tr>
				<td>Customer/Project:</td>
				<td>Starting Date:</td>
				<td>Ending Date:</td>
				<td><?php  echo createSimpleButton('submitButton', 'Submit','','disabled="disabled"');?></td>
			</tr>
			<tr class='bg'>
				<td><?php echo arrayToSelect(customersArray($conexion), 0, 'customerId', 'Customer'); ?></td>
				<td rowspan='2'><?php echo createInputText('invoiceStartDate','',"size='10px'");?></td>
				<td rowspan='2'><?php echo createInputText('invoiceEndDate','',"size='10px'");?></td>
				<td rowspan='2'><?php  echo createSimpleButton('previewButton', 'Preview','','disabled="disabled"');?></td>
			</tr>
			<tr>
				<td><?php echo emptySelect("projectId","Project");?></td>
			</tr>
			<tr>
				<td>Filter</td>
				<td>Condition</td>
				<td colspan='2'>Comment</td>
			</tr>
			<tr class='bg'>
				<td class='first'>
					<input type='radio' name='type' value='general' id='generalInvoiceType' checked ><label for='generalInvoiceType'>All Tickets</label><br/>
					<input type='radio' name='type' value='item' id='itemInvoiceType' ><label for='itemInvoiceType'>By Item</label><br/>
					<input type='radio' name='type' value='material' id='materialInvoiceType' ><label for='materialInvoiceType'>By Material</label><br/>
				</td>
				<td>
					<select name='materialId' id='materialId' style='font-family:verdana;font-size:8pt'>
						<option selected='selected' value='0' >--Select Material--</option>
					</select>
					
					<select name='itemId' id='itemId' style='font-family:verdana;font-size:8pt'>
						<option selected='selected' value='0' >--Select Item--</option>
					</select>
				</td>
				<td colspan='2'>
					<?php echo createInputTextArea('newCustomerInvoiceComment','','');?>
				</td>
			</tr>
			<?php 
			?>
		</table>
	</div>
	
	<div class='iframes' id='previewFrame'></div>
</div>
	
<?include_once '../news.php';?>
</div>
<?
include_once '../app_footer.php';
?>
