<?php
include_once '../nyro_header.php';
$reportId = $_GET['reportId'];
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
			<th colspan='4'>Manage Broker Invoice [<?php echo $reportId;?>]</th>
		</tr>
		<tr>
		<th>Number</th>
		<th>Date</th>
		<th>Amount</th>
		<th>Remove</th>
		</tr>
		<?php
		$cheques = mysql_query("select * from paidcheques where reportId = $reportId", $conexion);
		//Truck
		$flag = true; 
		while($cheque = mysql_fetch_assoc($cheques)) {
			$flag =!$flag;
			echo "<tr ".($flag?"class='bg'":"")." id='paycheque".$cheque['paidchequesId']."'>";
			echo "<td>".$cheque['paidchequeNumber']."</td>";
			echo "<td>".to_MDY($cheque['paidchequesDate'])."</td>";
			echo "<td>".decimalPad($cheque['paidchequesAmount'])."</td>";
			echo "<td>".createActionIcon(IMG_DELETE,'','Delete Payment','../submit/deleteBrokerCheck.php','paidchequesId='.$cheque['paidchequesId'],'delete'," width='22' height='22'")."</td>";
			echo "</tr>";
		}		
		?>
	</table>
</div>
