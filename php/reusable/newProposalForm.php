<?php
$customer = 0;
$supplier = 0;
$estimate = 0;
$material = 0;
$displayFrom = "";
$displayTo = "";
$materialPrice = "";
if(isset($_GET['customerId'])) $customer = $_GET['customerId'];
if(isset($_GET['estimateId'])) {
	$estimate = $_GET['estimateId'];
	$estimateInfo = getEstimateInfo($conexion,$estimate);
	$displayFrom = $estimateInfo['estimateName']." @ ".$estimateInfo['addressLine1'];
}
if(isset($_GET['supplierId'])) {
	$supplier = $_GET['supplierId'];
	$supplierInfo = getSupplierInfo($conexion,$supplier);
	$displayTo = $supplierInfo['supplierName']." @ ".$supplierInfo['addressLine1'];
}
if(isset($_GET['materialId']) && isset($_GET['supplierId'])) {
	$material = $_GET['materialId'];
	$materialInfo = mysql_fetch_assoc(mysql_query("SELECT * FROM suppliermaterial WHERE materialId = '$material' AND supplierId = '$supplier'", $conexion));
	$materialPrice = decimalPad($materialInfo['supplierMaterialPrice']);
	
}
?>
<script type="text/javascript">
$(document).ready(function() {
	
	$('#newItemCustomerId').change(function() {
		getEstimatesOptions('newItemProjectId',$(this).val());
	});
	
	$('#newItemSupplierId').change(function() {
		getMaterialsOptions('newItemMaterialId',$(this).val());
		getAdditionalInfo($(this).val(),0,'supplierInfo');
	});
	
	$('#newItemProjectId').change(function() {
		getAdditionalInfo($(this).val(),0,'estimateInfo');
	});
	
	$('#newItemMaterialId').change(function() {
		getAdditionalInfo($(this).val(),$('#newItemSupplierId').val(),'materialPrice');
	});
	
	$('#newItemReverse').click(function() {
		var aux=$('#newItemFromDisplay').val();
		$('#newItemFromDisplay').val($('#newItemToDisplay').val());
		$('#newItemToDisplay').val(aux);
		//var aux=$('#fromAddressId').val();
		//$('#fromAddressId').val($('#toAddressId').val());
		//$('#toAddressId').val(aux);
	});
	
	$('#submitNewProposal').click(function() {
		submitNewProposal();
	});
	
});

function submitNewProposal() {
	var data = getNewItemProposalParams();
	var url = '../submit/submitNewProposal.php';
	
	submitNewObject(url, data);
}

function getNewItemProposalParams() {
	var dataArray = new Array();
	dataArray['project'] = getVal('newItemProjectId');
	dataArray['supplier'] = getVal('newItemSupplierId');
	dataArray['material'] = getVal('newItemMaterialId');
	dataArray['fromId'] = getVal('newItemProjectId');
	dataArray['toId'] = getVal('newItemProjectId');
	dataArray['fromDisplay'] = getVal('newItemFromDisplay');
	dataArray['toDisplay'] = getVal('newItemToDisplay');
	dataArray['materialPrice'] = getVal('newItemMaterialPrice');
	dataArray['brokerCost'] = getVal('newItemBrokerCost');
	dataArray['customerCost'] = getVal('newItemCustomerCost');
	dataArray['type'] = getRadioVal('newItemType');
	dataArray['description'] = getVal('newItemDescription');
	
	
	return arrayToDataString(dataArray);
}

function getAdditionalInfo(objectId, additionalId, type) {
	$.ajax({
		type:	"GET",
		url:	"../retrieve/getProposalAdditionalInfo.php",
		data:	"objectId=" + objectId + "&additionalId=" + additionalId + "&type=" + type,
		success: function(data) {
			try{
				var obj = jQuery.parseJSON(data);
				if(obj.type == "estimateInfo") {
					$('#newItemFromDisplay').val(obj.displayFrom);
				}
				
				if(obj,type == "supplierInfo") {
					$('#newItemToDisplay').val(obj.displayTo);
				}
				
				if(obj,type == "materialPrice") {
					$('#newItemMaterialPrice').val(obj.materialPrice);
				}
			} catch(e) {
				alert("Internal Error: Please contact the administrator.");
			}
		},
		async:	true
	});
}
</script>
<div id="center-column">
	<div class="table">
		<table class="listing form" cellpadding="0" cellspacing="0">
			<tr>
				<th class="full" colspan="2">New Item</th>
			</tr>
			<?php 
			$flag = true; 
			//echo createFormRow('Customer', $flag ? 'class="bg"' : '', true, arrayToSelect(customersArray($conexion), (isset($_GET['customerId']) ? $_GET['customerId'] : 0 ), 'newItemCustomerId', 'Customer')); $flag = !$flag;
			//echo createFormRow('Estimate', $flag ? 'class="bg"' : '', true, emptySelect("newItemProjectId","Estimate")); $flag = !$flag;
			
			echo createFormRow('Customer', $flag ? 'class="bg"' : '', true, arrayToSelect(customersArray($conexion), $customer, 'newItemCustomerId', 'Customer')); $flag = !$flag;
			if($customer == 0)echo createFormRow('Estimate', $flag ? 'class="bg"' : '', true, emptySelect("newItemProjectId","Estimate"));
			else echo createFormRow('Estimate', $flag ? 'class="bg"' : '', true, arrayToSelect(estimatesArray($conexion, $customer), $estimate, "newItemProjectId", "Estimate"));
			$flag = !$flag;
			
			echo createFormRow('Supplier', $flag ? 'class="bg"' : '', true, arrayToSelect(suppliersArray($conexion), $supplier, 'newItemSupplierId', 'Supplier')); $flag = !$flag;
			if($supplier == 0)echo createFormRow('Material', $flag ? 'class="bg"' : '', true, emptySelect("newItemMaterialId","Material"));
			else echo createFormRow('Material', $flag ? 'class="bg"' : '', true, arrayToSelect(materialBySupplierArray($conexion, $supplier),$material, "newItemMaterialId","Material")); 
			$flag = !$flag;
			
			echo createFormRowTextField("From <img id='newItemReverse' src='/trucking/img/48.png' width='18px'/>", 'newItemFromDisplay', $flag ? 'class="bg"' : '', false, "size=25px value=\"$displayFrom\""); $flag = !$flag; 
			echo createFormRowTextField('To', 'newItemToDisplay', $flag ? 'class="bg"' : '', false, "size=25px value=\"$displayTo\""); $flag = !$flag; 
			echo createFormRowTextField('Material Price', 'newItemMaterialPrice', $flag ? 'class="bg"' : '', true, "size=10px value=\"$materialPrice\""); $flag = !$flag; 
			echo createFormRowTextField('Broker Cost', 'newItemBrokerCost', $flag ? 'class="bg"' : '', true, 'size=10px'); $flag = !$flag; 
			echo createFormRowTextField('Customer Cost', 'newItemCustomerCost', $flag ? 'class="bg"' : '', true, 'size=10px'); $flag = !$flag; 
			
			echo createFormRow('Type', $flag ? 'class="bg"' : '', true, arrayToRadio(array("Loads","Tons","hours"),0,"newItemType","")); $flag = !$flag;
			
			echo createFormRow('Description', $flag ? 'class="bg"' : '', false, createInputTextArea('newItemDescription','','rows="2" cols="43"')); $flag = !$flag;
			
			?>
		</table>
		<table>
			<tr>
				<td><?php echo createSimpleButton('submitNewProposal', 'Submit');?></td>
			</tr>
		</table>
	</div>
</div>
