<?php
include_once '../nyro_header.php';
$estimateId = $_GET['estimateId'];
$estimateInfo = objectQuery($conexion, '*',$estimateExtendedTables,'fakeprojectId = '.$estimateId);
?>
<script type="text/javascript">
function deleteSuccess() {
	closeNM();
}
</script>
<div id='actions' class="top-bar">
	<?php echo createActionIcon(IMG_NEW_ITEM_PROPOSAL, 'addProposal', 'Add Proposal', '../nyros/newProposal.php', "estimateId=".$estimateId."&customerId=".$estimateInfo['customerId'], 'show');?>
	<?php /*echo createEditIcon('editProject','../nyros/editProject.php',"projectId=$projectId", "Project");*/?>
	<?php echo createDeleteIcon('deleteProject','../submit/deleteEstimate.php',"estimateId=$estimateId", "Estimate");?>
</div>
<div id='formDiv' class='table'>
	<img src="/mfi/img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
	<img src="/mfi/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
	<table class="listing form" cellpadding="0" cellspacing="0">
		<tr>
			<th colspan='2'>Estimate Information</th>
		</tr>
		<?php
		$flag = true;
		$customerLink = printImgLink(IMG_VIEW, NYRO_CLASS, TITLE_VIEW_CUSTOMER, createGenericNyroableAttributesSmall($estimateInfo['customerId'],'customer'));
		
		echo printTuple(($flag?'':"class='bg'"),'Customer',$estimateInfo['customerId']." ".$estimateInfo['customerName'],$customerLink);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Estimate ID',$estimateInfo['fakeprojectId']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Estimate Name',$estimateInfo['fakeprojectName']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Address',$estimateInfo['addressLine1']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Address',$estimateInfo['addressCity'].", ".$estimateInfo['addressState']." ".$estimateInfo['addressZip']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'P.O. Box',$estimateInfo['addressPOBox']);$flag = !$flag;
		?>
	</table>
</div>
