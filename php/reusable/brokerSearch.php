<?php
include_once '../app_header.php';
?>
<div id="middle">
<?include_once 'navigation.php';?>
<script type="text/javascript">
$(document).ready(function() {
	
	//$.fn.nyroModal();
	$.fn.nyroModal({
		showBg: function (nm, clb) {
			nm.elts.bg.fadeTo(250, 0.7, clb);
		}
	});
	
	$('#searchButton').click(function() {
		searchBrokers();
	});
	
	$('#printButton').click(function() {
		printBrokers();
	});
	
	$('#exportButton').click(function() {
		alert("This feature is not ready");
	});
	
	$(document).on('dblclick', '.doubleClickable', function() {
		var url = '/mfi/php/nyros/viewBroker.php?brokerId=' + $(this).attr('brokerId');
		$.nmManual(url,{callbacks:{initFilters: function(nm){nm.filters.push('link');}}});
	});
});

function doAfterClose() {
	searchBrokers();
}

function printBrokers() {
	var data = getParams();
	var url = "../reports/genericReport.php?" + data + "&title=Broker&sub=Search&type=broker";
	var windowName = 'Broker Report';
	var windowSize = 'width=814,heigh=514,scrollbars=yes';
	window.open(url,windowName,windowSize);
}

function searchBrokers() {
	var data = getParams();
	var url = "../retrieve/getBrokersTable.php";
	getExternalTable(url, data);
}

function getParams() {
	var names = [];
	var headers = [];
	var types = [];
	$('input:checked').each(function() {
		names.push($(this).attr("name"));
		headers.push($(this).attr("headerName"));
		types.push($(this).attr("varType"));
	});
	var attributes = names.join('~');
	var variables = types.join('~');
	var headers = escape(headers.join('~'));
	
	var brokerId = $('#brokerId').val();
	var brokerPid = $('#brokerPid').val();
	var brokerName = escape($('#brokerName').val());
	var brokerAddress = escape($('#addressLine1').val());
	var brokerCity = escape($('#addressCity').val());
	var stateId = $('#stateId').val();
	var zipCode = $('#addressZipCode').val();
	var gender = $('#gender').val();
	var ethnicity = $('#ethnicId').val();
	var tel = $('#searchBrokerPhone').val();
	
	var data = "brokerId="+brokerId+"&brokerPid="+brokerPid+"&brokerName="+brokerName+"&addressLine1="+brokerAddress+
		"&addressCity="+brokerCity+"&addressState="+stateId+"&addressZip="+zipCode+
		"&brokerGender="+gender+"&ethnicId="+ethnicity+"&tel="+tel+
		"&values="+attributes+"&headers="+headers+"&variables="+variables;
		
	return data;
}
</script>
<div id="center-column">
	<div class='table' id='searchBar'>
		<img src="/trucking/img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
		<img src="/trucking/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
		<table class="listing form" cellpadding="0" cellspacing="0" >
			<tr>
				<th class="full"  colspan='5'>Search Brokers</th>
			</tr>
			<tr>
				<td class='first'>Broker ID:</td>
				<td class='first'>Broker PID:</td>
				<td class='first'>Broker Name:</td>
				<td class='first'>Address:</td>
				<td class='first' rowspan='6'>
					<?php echo createCheckbox('c_brokerId','Broker Id','brokerId', 'headerName="Id" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_brokerPid','Broker Pid','brokerPid', 'checked="cehcekd" headerName="Broker PID" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_brokerName','Broker Name','brokerName','checked="checked" headerName="Broker Name" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_gender','Gender','brokerGender', 'headerName="Gender" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_ethnicName','Ethnicity','ethnicName', 'headerName="Ethnicity" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_phoneNumber','Phone','brokerTel', 'headerName="Phone" varType="phone"'); ?><br/>
					<?php echo createCheckbox('c_addressLine1','Address','addressLine1', 'headerName="Address" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_addressCity','City','addressCity', 'headerName="City" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_addressState','State','addressState', 'headerName="State" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_addressZip','Zip Code','addressZip', 'headerName="ZipCode" varType="string"'); ?><br/>
				</td>
			</tr>
			<tr class='bg'>
				<td> <?php echo createInputText('brokerId','',"size='10px'");?></td>
				<td> <?php echo createInputText('brokerPid','',"size='10px'");?></td>
				<td> <?php echo createInputText('brokerName','',"size='10px'");?></td>
				<td> <?php echo createInputText('addressLine1','',"size='10px'");?></td>
			</tr>
			<tr>
				<td class='first'>City:</td>
				<td class='first'>State:</td>
				<td class='first'>Zip Code:</td>
				<td></td>
			</tr>
			<tr class='bg'>
				<td> <?php echo createInputText('addressCity','',"size='10px'");?></td>
				<td> <?php echo createStateSelect($conexion);?></td>
				<td> <?php echo createInputText('addressZipCode','',"size='10px'");?></td>
				<td> <?php echo createSimpleButton('exportButton', 'Export');?></td>
			</tr>
			<tr>
				<td class='first'>Gender:</td>
				<td class='first'>Ethnicity:</td>
				<td class='first'>Phone</td>
				<td> <?php echo createSimpleButton('printButton', 'Print');?></td>
			</tr>
			<tr class='bg'>
				<td><?php echo arrayToSelect(array("0"=>"Any","male" => "Male","female"=>"Female"),"0",'gender','Gender',true); ?></td>
				<td>
				<?php
				$ethnicArray = ethnicsArray($conexion);
				echo arrayToSelect($ethnicArray, 0, 'ethnicId', 'Ethnic');
				?>
				</td>
				<td><?php echo createInputText('searchBrokerPhone','',"size='10px'");?></td>
				<td> <?php echo createSimpleButton('searchButton', 'Search');?></td>
			</tr>
		</table>
	</div>
	
	<div class='table' id='brokersTable' ></div>
</div>
<?include_once '../news.php';?>
</div>
<?
include_once '../app_footer.php';
?>
