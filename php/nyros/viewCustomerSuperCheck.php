<?php
include_once '../nyro_header.php';
$customerSuperCheckId = $_GET['customerSuperCheckId'];
$customerSuperCheckInfo = objectQuery($conexion, "*, COALESCE(customerCreditAmount, '0') as checkCredit",'customer_super_check JOIN customer USING (customerId) LEFT JOIN customer_credit USING (customerSuperCheckId)','customerSuperCheckId = '.$customerSuperCheckId);

$payments = mysql_query("SELECT * FROM receiptcheques WHERE customerSuperCheckId = '$customerSuperCheckId' ORDER BY invoiceId desc", $conexion);
?>
<script type="text/javascript">
function deleteSuccess() {
	closeNM();
}
</script>
<div id='actions' class="top-bar">
	<?php /*echo createEditIcon('editSupplier','../nyros/editCustomerSuperCheck.php',"customerSuperCheckId=$customerSuperCheckId", "Supplier");*/?>
	<?php echo createDeleteIcon('deleteSupplier','../submit/deleteCustomerSuperCheck.php',"customerSuperCheckId=$customerSuperCheckId", "Supplier");?>
</div>
<div id='formDiv' class='table'>
	<img src="/mfi/img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
	<img src="/mfi/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
	<table class="listing form" cellpadding="0" cellspacing="0">
		<tr>
			<th colspan='2'>Customer Check Information</th>
		</tr>
		<?php
		$flag = true;
		$customerLink = printImgLink(IMG_VIEW, NYRO_CLASS, TITLE_VIEW_VENDOR, createGenericNyroableAttributesSmall($customerSuperCheckInfo['customerId'],'customer'));
		
		echo printTuple(($flag?'':"class='bg'"),'Customer',$customerSuperCheckInfo['customerName'], $customerLink);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Check Number',$customerSuperCheckInfo['customerSuperCheckNumber']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Check Date',to_MDY($customerSuperCheckInfo['customerSuperCheckDate']));$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Check Registration',to_MDY($customerSuperCheckInfo['customerSuperCheckCreationDate']));$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Check Amount',"$ ".decimalPad($customerSuperCheckInfo['customerSuperCheckAmount']));$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Check Note',$customerSuperCheckInfo['customerSuperCheckNote']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Credit',"$ ".decimalPad($customerSuperCheckInfo['checkCredit']));$flag = !$flag;
		?>
	</table>
</div>

<?php
if(mysql_num_rows($payments)>0) {
?>
<div id='formDiv' class='table'>
	<img src="/mfi/img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
	<img src="/mfi/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
	<table class="listing form" cellpadding="0" cellspacing="0">
		<tr>
			<th colspan='2'>Payments Information</th>
		</tr>
		<?php
		$flag = true;
		while($payment = mysql_fetch_assoc($payments)) {
			echo printTuple(($flag?'':"class='bg'"),$payment['invoiceId'],"$ ".decimalPad($payment['receiptchequesAmount']));$flag = !$flag;
		}
		?>
	</table>
</div>
<?php
}
?>
