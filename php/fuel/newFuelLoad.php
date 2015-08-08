<?
$title = "MFI";
$subtitle = "Fuel";

$tab = "FUEL";

include_once '../app_header.php';
?>
<div id="middle">
<?include_once 'navigation.php';?>
<script type="text/javascript">
$(document).on('dblclick', '.doubleClickable', function() {
		var url = '/mfi/php/nyros/viewFuelLoad.php?fuelLoadId=' + $(this).attr('fuelLoadId');
		$.nmManual(url,{callbacks:{initFilters: function(nm){nm.filters.push('link');}}});
});

function doAfterClose() {
	//funciton to be overwritted
	location.reload();
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
				alert("Item created successfully");
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

function getFeatures() {
	var features = "";
	var glue = "";
	var checkedB = $('.featureCheckbox:checked');
	$.each(checkedB, function(index, obj) {
		features = features + glue + obj.value;
		glue = "~";
	});
	return escape(features);
}
</script>
<div id="center-column">
<?php include '../reusable/newFuelLoadForm.php';?>
	<div class="table" id="oldFuelLoads">
		<table class="listing form" cellpadding="0" cellspacing="0">
			<tr>
				<th class="full" colspan="8">Older Elements</th>
			</tr>
			<tr>
				<th>Date</th>
				<th>Broker</th>
				<th>Truck</th>
				<th>Driver</th>
				<th>Start</th>
				<th>Finish</th>
				<th>Registered</th>
				<th>Miles</th>
			</tr>
			<?php 
			$fuels = mysql_query("SELECT * FROM fuel_load JOIN broker ON (broker.brokerId = fuel_load.brokerId) JOIN truck USING (truckId) order by fuelLoadId", $conexion);
			while($fuel = mysql_fetch_assoc($fuels)) {
				echo "<tr class='doubleClickable' fuelLoadId='".$fuel['fuelLoadId']."'>
					<td>".to_MDY($fuel['fuelLoadDate'])."</td>
					<td>".$fuel['brokerName']."</td>
					<td>".$fuel['brokerPid']."-".$fuel['truckNumber']."</td>
					<td>".$fuel['fuelLoadCommet']."</td>
					<td>".$fuel['fuelLoadStart']."</td>
					<td>".$fuel['fuelLoadFinish']."</td>
					<td>".$fuel['fuelLoadRegistered']."</td>
					<td>".$fuel['fuelLoadMileage']."</td>
				</tr>";
			}
			?>
		</table>
	</div>
</div>
<?include_once '../news.php';?>
</div>
<?
include_once '../app_footer.php';
?>
