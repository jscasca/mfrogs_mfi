<?php
include_once '../nyro_header.php';
$reportId = $_GET['reportId'];
$supplierInvoice = mysql_fetch_assoc(mysql_query("SELECT * FROM supplierinvoice WHERE supplierInvoiceId = '$reportId'",$conexion));
?>
<script type="text/javascript">
$(document).ready(function() {
	
});

function closeNM() {
	//console.log("closing");
	$.nmTop().close();
}


</script>
<div id='formDiv' class='table'>
	<img src="/mfi/img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
	<img src="/mfi/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
	<table class="listing form" cellpadding="0" cellspacing="0">
		<tr>
			<th colspan='4'>Pay Cheques for Supplier Invoice [<?php echo $supplierInvoice['supplierInvoiceNumber'];?>]</th>
		</tr>
		<tr>
		<th>Number</th>
		<th>Date</th>
		<th>Amount</th>
		<th>Remove</th>
		</tr>
		<?php
		$cheques = mysql_query("select * from suppliercheque where supplierInvoiceId = $reportId", $conexion);
		//Truck
		$flag = true; 
		while($cheque = mysql_fetch_assoc($cheques)) {
			$flag =!$flag;
			echo "<tr ".($flag?"class='bg'":"")." id='paycheque".$cheque['supplierchequeId']."'>";
			echo "<td>".$cheque['supplierchequeNumber']."</td>";
			echo "<td>".to_MDY($cheque['supplierchequeDate'])."</td>";
			echo "<td>".decimalPad($cheque['supplierchequeAmount'])."</td>";
			echo "<td>".createActionIcon(IMG_DELETE,'','Delete Payment','../submit/deleteSupplierCheck.php','chequeId='.$cheque['supplierchequeId'],'delete'," width='22' height='22'")."</td>";
			echo "</tr>";
		}		
		?>
	</table>
</div>
