<?
$title = "MFI";
$subtitle = "Customer";

$tab = "MAP";

include_once '../app_header.php';

$jsColorArray = "var colorArray = [
'#FF0000',
'#000099',
'#00CC00',
'#000000',
'#660066',
'#FF6600',
'#FF0066'
];";

$results = mysql_query("select hourlyRate from stateinfo", $conexion);
$result = mysql_fetch_assoc($results);

$hourly = $result['hourlyRate'];
$perminute = $hourly / 60;

$key="ABQIAAAAnfs7bKE82qgb3Zc2YyS-oBT2yXp_ZAY8_ufC3CFXhHIE1NvwkxSySz_REpPq-4WZA27OwgbtyR3VcA";
$alk="ABQIAAAAnfs7bKE82qgb3Zc2YyS-oBT2yXp_ZAY8_ufC3CFXhHIE1NvwkxSySz_REpPq-4WZA27OwgbtyR3VcA";
$googleMapsScript = "<script src='http://maps.google.com/maps?file=api&amp;v=2&amp;key=".$key."&sensor=false' type='text/javascript'></script>";
$gMapsV3 = "<script type='text/javascript' src='http://maps.googleapis.com/maps/api/js?sensor=false'></script>";
?>
<div id="middle">
<?include_once 'navigation.php';?>
<?echo $gMapsV3;?>
<script type="text/javascript">
var patternDigits = new RegExp(/[0-9]+/);
patternDigits.compile(patternDigits);

var patternLetters = new RegExp(/supplier/);
patternLetters.compile(patternLetters);
<?echo $jsColorArray;?>
var centerLat = 41.911098233333;
var centerLong = -87.640749033333;
var centerDeep = 10;

var perminute = <?echo $perminute;?>;

var markersArray = [];
var routesArray = [];

var directionsService = new google.maps.DirectionsService();
var directionsDisplay;
var map;

var lastSorted = 0;
var tableToSort = [];
var sortingUp = true;

var jobDumptime = 0;

var jobSite= new google.maps.Marker({
		position: new google.maps.LatLng(0,0),
		title: "Job"
	});
	jobSite.setMap(null);

$(document).ready(function() {
	
	//TODO: FINISH THIS ASAP
	//$('.newItemProposalIcon').on('click',function(){
	$(document).on('click','.newItemProposalIcon',function(){
		var data = getProjectParams();
		var url = '/mfi/php/nyros/newItemProposal.php?' + data + "&supplierId=" + $(this).attr('supplierId');
		$.nmManual(url);
	});
	
	//$('.newItemIcon').on('click',function(){
	$(document).on('click','.newItemIcon',function(){
		var data = getProjectParams();
		var url = '/mfi/php/nyros/newItem.php?' + data + "&supplierId=" + $(this).attr('supplierId');
		$.nmManual(url);
	});
	
	//$('.newProposalIcon').on('click',function(){
	$(document).on('click','.newProposalIcon',function(){
		var data = getEstimateParams();
		var url = '/mfi/php/nyros/newProposal.php?' + data + "&supplierId=" + $(this).attr('supplierId');
		$.nmManual(url);
	});
	
	//$('.sortable').on('click',function(){
	$(document).on('click','.sortable',function(){
		var index = $(this).attr('attributeToSort');
		console.log(index);
		sortTableByAttributeUp(index);
		/*var index = $(this).parent().children().index($(this));
		if(index == lastSorted){
			sortingUp = !sortingUp;
		}else{
			lastSorted = index;
			sortingUp = true;
		}
		sortTableBy(index);*/
	});
	
	$('#additionalDumptime').blur(function(){
		if(this.value != "")jobDumptime = this.value;
		else jobDumptime = 0;
	});
	
	$('#searchButton').click(function() {
		getSupplierAndPrice($('#pathFinderMaterialId').val(),$('#projectType').val());
	});
	
	$('#pathFinderProjectId').change(function() {
		getJobPosition($(this).val(),$('#projectType').val());
	});
	
	$('#pathFinderCustomerId').change(function() {
		if($('#projectType').val() == '1') {
			getEstimatesOptions('pathFinderProjectId',$(this).val());
		} else {
			getProjectsOptions('pathFinderProjectId',$(this).val());
		}
	});
	
	initializeMap(centerLat,centerLong,centerDeep);
});

function getProjectParams() {
	var dataArray = new Array();
	dataArray['customerId'] = getVal('pathFinderCustomerId');
	dataArray['projectId'] = getVal('pathFinderProjectId');
	dataArray['materialId'] = getVal('pathFinderMaterialId');
	return arrayToDataString(dataArray);
}

function getEstimateParams() {
	var dataArray = new Array();
	dataArray['customerId'] = getVal('pathFinderCustomerId');
	dataArray['estimateId'] = getVal('pathFinderProjectId');
	dataArray['materialId'] = getVal('pathFinderMaterialId');
	return arrayToDataString(dataArray);
}

function getSupplierAndPrice(materialId, type){
	$.ajax({
		type: "GET",
		url: "../retrieve/getPathFinderSupplierAndPrice.php",
		data: "materialId="+materialId+"&pathType="+type,
		success:function(data){
			var obj=jQuery.parseJSON(data);
			//console.log(obj);
			if(obj.error==null){
				if(obj.lat!=null && obj.lng!=null){
					var i=0;
					deleteOverLays()
					deleteRoutes();
					for(i=0;i<obj.lat.length;i++){
						//console.log("getting supplier route");
						setSupplierPoint(obj.lat[i],obj.lng[i],obj.supplierName[i],obj.supplierId[i]);
						setRoute(obj.lat[i],obj.lng[i],obj.supplierId[i],colorArray[i]);
					}
				}
				if(obj.table!=null){
					if($('#priceList').length==0){
						$('#mapCanvas').after(obj.table);
					}else{
						$('#priceList').replaceWith(obj.table);
					}
				}
			}else{alert('Error: '+obj.error);}
		},
		async: false
	});
}

function getJobPosition(objectId, type) {
	$.ajax({
		type: "GET",
		url: "../retrieve/getPathFinderJobPosition.php",
		data: "projectId="+objectId + "&pathType="+type,
		success:function(data){
			var obj=jQuery.parseJSON(data);
			if(obj.error==null){
				if(obj.lat!=null && obj.lng!=null){
					setJob(obj.lat,obj.lng);
				}else{jobSite.setMap(null);}
			}else{alert('Error: '+obj.error);}
		},
		async: false
	});
}

function doAfterClose() { 
	//searchCustomerInvoices(); 
}

function searchCustomerInvoices() {
	var data = getParams();
	var url = '../retrieve/getCustomerInvoicesInteractive.php';
	getExternalTable(url, data);
}

function disableButton() {
	$('#submitButton').attr('disabled','disabled');
}

function enableButton() {
	$('#submitButton').removeAttr('disabled');
}

function sortTableByAttributeUp(attr) {
	console.log("sorting");
	var rows = $('#toSort tr:gt(0)');
	
	var size = rows.length;
	var aux;
	var sortedRows = new Array();
	
	for(i = 0; i < size - 1; i++) {
		for(j = i +1; j < size; j++){
			firstAtt = parseFloat($(rows[i]).attr(attr));
			secondAtt = parseFloat($(rows[j]).attr(attr));
			if(firstAtt > secondAtt) {
				aux = rows[i];
				rows[i] = rows[j];
				rows[j] = aux;
			}
		}
	}
	console.log(rows);
	$("#toSort tr:gt(0)").remove();
	for(i = 0; i< size; i++) {
		console.log(rows[i]);
		$('#toSort').append(rows[i]);
	}
	
}

function sortTable(index){
	var rows = $("#toSort tr:gt(0)");
	var tbody = document.getElementById('toSort').tBodies[0];
	var newBody = tbody;
	var size = rows.length;
	var aux;
	var i = 0;
	for( i=1;i<size;i++){
		
		//console.log(newBody.rows[i]);
		var td = newBody.rows[i].cells[index];
		//console.log(td.textContent);
		var getCompare = td.textContent.split(' ');
		var toCompare = 0;
		//console.log(getCompare);
		if(index == 2 || index == 7){
			toCompare = getCompare[1];
		}else{
			toCompare = getCompare[0];
		}
		//console.log(toCompare);
		//var toSet = rows[i].find("td:eq("+index+")").text();
		var j=0;
		for(j=i+1;j<=size;j++){
			var td2 = newBody.rows[j].cells[index];
			var getMove = td2.textContent.split(' ');
			var toMove = 0;
			if(index == 2 || index == 7){
				toMove = getMove[1];
			}else{
				toMove = getMove[0];
			}
			
			//console.log(toCompare+" > "+toMove);
			if(sortingUp){
				if(parseFloat(toCompare) > parseFloat(toMove)){
					var tmpNode = tbody.replaceChild(tbody.rows[i],tbody.rows[j]);
					tbody.insertBefore(tmpNode,tbody.rows[i]);
				}
			}else{
				if(parseFloat(toCompare) < parseFloat(toMove)){
					var tmpNode = tbody.replaceChild(tbody.rows[i],tbody.rows[j]);
					tbody.insertBefore(tmpNode,tbody.rows[i]);
				}
			}
		}
		console.log(newBody);
	}
}

function setRoute(lat,lng,sId,color){
//console.log("setting route");
	if(jobSite.getMap()!=null){
		var orig = new google.maps.LatLng(lat,lng);
		var dest = jobSite.getPosition();
		var request = {
			origin: orig,
			destination: dest,
			travelMode: google.maps.TravelMode.DRIVING
		};
		directionsService.route(request, function(response,status){
			//console.log(status);
			if(status == google.maps.DirectionsStatus.OK){
				//directionsDisplay.setDirections(response);
				//console.log(response.routes[0].legs[0]);
				var title = "";
				var route = response.routes[0].legs[0];
				console.log(route);
				var materialPriceText = $('#matprice'+sId).text().split(' ');
				var dumptime = $('#dptm'+sId).text();
				var tons = 1;
				var tonsTitle = "";
				
				materialPrice = materialPriceText[1];
				title = title + " [ "+materialPrice+" ]";
				//title = title + " * 1 ] ";
				
				if($('#byLoad').attr('checked')){
					
				}else{
					tons = 20;
					tonsTitle = "/20";
				}
				
				var etaVal = route.duration.value;
				var eta = Math.round(etaVal/60);
				
				$('#dist'+sId).text(route.distance.text);
				$('#row' + sId).attr('atdistance', route.distance.value);
				$('#row' + sId).attr('ateta', etaVal);
				//$('#eta'+sId).text(route.duration.text);
				$('#eta'+sId).text(eta + " mins");
				//console.log(perminute+" * [("+eta+" * 2 ) + "+dumptime+" + "+jobDumptime+" ]");
				var roundPricex2 = perminute * ((eta * 2) + parseFloat(dumptime) + parseFloat(jobDumptime));
				//console.log(parseFloat(materialPrice)+" + "+parseFloat(roundPricex2/tons));
				var etax2 = new Number(parseFloat(materialPrice) + parseFloat(roundPricex2/tons));
				$('#eta'+sId+'x2p').text("$ "+etax2.toFixed(2));
				$('#row' + sId).attr('atx2', etax2.toFixed(2));
				$('#eta'+sId+'x2p').attr("title",title + " + [ "+perminute+" * [( "+eta+" * 2 ) + "+jobDumptime+" + "+dumptime+" ] ]"+tonsTitle);
				var roundPricex25 = perminute * ((eta*(2.5)) + parseFloat(dumptime) + parseFloat(jobDumptime));
				var etax25 = new Number(parseFloat(materialPrice) + parseFloat(roundPricex25/tons));
				$('#eta'+sId+'x25p').text("$ "+etax25.toFixed(2));
				$('#eta'+sId+'x25p').attr("title",title + " + [ "+perminute+" * [( "+eta+" * 2.5 ) + "+jobDumptime+" + "+dumptime+" ] ]"+tonsTitle);
				var roundPricex3 = perminute * ((eta*3) + parseFloat(dumptime) + parseFloat(jobDumptime));
				var etax3 = new Number(parseFloat(materialPrice) + parseFloat(roundPricex3/tons));
				$('#eta'+sId+'x3p').text("$ "+etax3.toFixed(2));
				$('#eta'+sId+'x3p').attr("title",title + " + [ "+perminute+" * [( "+eta+" * 3 ) + "+jobDumptime+" + "+dumptime+" ] ]"+tonsTitle);
				//console.log(route);
				var i=0;
				for(i=0;i<route.steps.length;i++){
					//route.steps[i].polyline.setMap(map);
					var truckPath = new google.maps.Polyline({
						path: route.steps[i].lat_lngs,
						strokeColor: color,
						strokeOpacity: 1.0,
						strokeWeight: 2
					});
					truckPath.setMap(map);
					routesArray.push(truckPath);
				}
			}else if(status === google.maps.GeocoderStatus.OVER_QUERY_LIMIT){
				setTimeout(function(){
					setRoute(lat,lng,sId,color);
				},200);
			}
		});
	}else{console.log('map null');}
}

function setSupplierPoint(lat,lng,name,id){
	//alert('algo');
	//console.log("setting point");
	var mLatLng = new google.maps.LatLng(lat,lng);
		var marker = new google.maps.Marker({
			position: mLatLng,
			title: name
		});
		
		marker.setMap(map);
		markersArray.push(marker);	
		
		google.maps.event.addListener(marker, 'click', function() {
		  alert(id);  
		});
}

function deleteOverLays(){
		if(markersArray){
			for(i in markersArray){
				markersArray[i].setMap(null);
			}
			markersArray.length = 0;
		}
}

function deleteRoutes(){
		if(routesArray){
			for(i in routesArray){
				routesArray[i].setMap(null);
			}
			routesArray.length = 0;
		}
}

function deleteOverLays(){
		if(markersArray){
			for(i in markersArray){
				markersArray[i].setMap(null);
			}
			markersArray.length = 0;
		}
}

function newMarker(lat,lng){
	var mLatLng = new google.maps.LatLng(lat,lng);
		var marker = new google.maps.Marker({
			position: mLatLng,
			title: "Supplier"
		});
		
		marker.setMap(map);
		markersArray.push(marker);
}

function setJob(lat,lng){
	var mLatLng = new google.maps.LatLng(lat,lng);
	jobSite.setMap(null);
	jobSite = new google.maps.Marker({
		position: mLatLng,
		title: "Job"
	});
	jobSite.setMap(map);
}

function initializeMap(lat,lng,deep){
	directionsDisplay = new google.maps.DirectionsRenderer();
		var latlng = new google.maps.LatLng(lat,lng);
		var myOptions ={
			zoom: deep,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		map = new google.maps.Map(document.getElementById("mapCanvas"),myOptions);
		directionsDisplay.setMap(map);
}
</script>
<div id="center-column">
	<div class="table">
		<table class="listing form" cellpadding="0" cellspacing="0">
			<tr>
				<th class="full" colspan="6">View Customer Balance</th>
			</tr>
			<tr>
				<td>Type:</td>
				<td>Customer/Project:</td>
				<td>Estimate Dumptime:</td>
				<td>Material:</td>
				<td>Load:</td>
				<td></td>
			</tr>
			<tr class='bg'>
				<td rowspan='2'><?php echo arrayToSelect(array("Projects","Estimates"),0,'projectType','',true);?></td>
				<td><?php echo arrayToSelect(customersArray($conexion), 0, 'pathFinderCustomerId', 'Customer'); ?></td>
				<td rowspan='2'><?php echo createInputText('additionalDumptime','',"size='4px'");?></td>
				<td rowspan='2'><?php echo arrayToSelect(materialsArray($conexion),"0",'pathFinderMaterialId','Material', false);?></td>
				<td rowspan='2'><input type="checkbox" id="byLoad" name="byLoad" /><label for="byLoad" >By Load</label></td>
				<td rowspan='2'><?php  echo createSimpleButton('searchButton', 'Search','');?></td>
			</tr>
			<tr>
				<td><?php echo emptySelect("pathFinderProjectId","Project/Estimate");?></td>
			</tr>
			<?php 
			?>
		</table>
	</div>
	
	<div id='mapCanvas' class='mapCanvas'>
	</div>
	<div id='priceList'>
	</div>
	
</div>
	
<?include_once '../news.php';?>
</div>
<?
include_once '../app_footer.php';
?>
