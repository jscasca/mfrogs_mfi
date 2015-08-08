<?php
include_once '../nyro_header.php';
$supplierId = $_GET['supplierId'];
$supplierInfo = objectQuery($conexion, '*',$supplierExtendedTables,'supplierId = '.$supplierId);
?>
<script type="text/javascript">
var supplier = '<?php echo $supplierId;?>';
$(document).ready(function() {
	
	$('#saveNewPriceList').click(function() {
		saveNewPriceList();
	});
	
	$('#vendorId').change(function() {
		getSuppliersOptions('supplierId',$(this).val());
		$('#previewButton').removeAttr('disabled');
	});
});

function saveNewPriceList() {
	var data = getNewPriceParams();
	var url = '../submit/submitSupplierMaterial.php';
	submitNewPrice(url, data);
}

function getNewPriceParams() {
	var dataArray = new Array();
	dataArray['supplier'] = supplier;
	dataArray['material'] = getVal('newPriceListMaterial');
	dataArray['price'] = getVal('newPriceListPrice');
	dataArray['info'] = getVal('newPriceListInfo');
	
	return arrayToDataString(dataArray);
}

function submitNewPrice(url, data) {
	$.ajax({
		type:	"GET",
		url:	url,
		data:	data,
		success: function(data) {
			try{
				var obj = jQuery.parseJSON(data);
				switch(obj.code){
					case 0:
					var newLine = obj.newLine;
					$('#newPriceListItem').before(newLine);
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
		},
		async:	true
	});
}
</script>
<div id='formDiv' class='table'>
	<img src="/mfi/img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
	<img src="/mfi/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
	<table class="listing form" cellpadding="0" cellspacing="0">
		<tr>
			<th colspan='4'>Supplier Information</th>
		</tr>
		<?php
		$flag = true;
		//$vendorLink = printImgLink(IMG_VIEW, NYRO_CLASS, TITLE_VIEW_VENDOR, createGenericNyroableAttributesSmall($supplierInfo['vendorId'],'vendor'));
		$materials = mysql_query("SELECT * FROM suppliermaterial JOIN material USING (materialId) WHERE supplierId = '$supplierId'", $conexion);
		while($material = mysql_fetch_assoc($materials)) {
			$delImg = createActionIcon(IMG_DELETE,'delete'.$supplierId."-".$material['materialId'],'Delete Price','../submit/deleteSupplierMaterial.php','supplier='.$supplierId.'&material='.$material['materialId'],'delete'," width='22' height='22'");
			echo printRow(($flag ? "": "class='bg'"),array($material['materialName'],decimalPad($material['supplierMaterialPrice']), to_MDY($material['supplierMaterialLastModified']),$delImg));
			$flag = !$flag;
		}
		echo printRow(" id='newPriceListItem' ".($flag ? "": "class='bg'"),array(arrayToSelect(materialsArray($conexion),'0','newPriceListMaterial','Material'),createInputText('newPriceListPrice','',''),'',createSimpleButton('saveNewPriceList','Submit')));
		?>
	</table>
</div>
