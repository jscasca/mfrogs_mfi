<?php
include_once '../nyro_header.php';
$projectId = $_GET['projectId'];
$projectInfo = objectQuery($conexion, '*',$projectExtendedTables,'projectId = '.$projectId);
?>
<script type="text/javascript">
function deleteSuccess() {
	closeNM();
}
</script>
<div id='actions' class="top-bar">
	<?php echo createActionIcon(IMG_NEW_ITEM_PROPOSAL, 'addItemProposal', 'Add Item Proposal', '../nyros/newItemProposal.php', "projectId=".$projectId."&customerId=".$projectInfo['customerId'], 'show');?>
	<?php echo createActionIcon(IMG_NEW_ITEM, 'addItem', 'Add Item', '../nyros/newItem.php', "projectId=".$projectId."&customerId=".$projectInfo['customerId'], 'show');?>
	<?php echo createEditIcon('editProject','../nyros/editProject.php',"projectId=$projectId", "Project");?>
	<?php echo createDeleteIcon('deleteProject','../submit/deleteProject.php',"projectId=$projectId", "Project");?>
</div>
<div id='formDiv' class='table'>
	<img src="/mfi/img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
	<img src="/mfi/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
	<table class="listing form" cellpadding="0" cellspacing="0">
		<tr>
			<th colspan='2'>Project Information</th>
		</tr>
		<?php
		$flag = true;
		$customerLink = printImgLink(IMG_VIEW, NYRO_CLASS, TITLE_VIEW_CUSTOMER, createGenericNyroableAttributesSmall($projectInfo['customerId'],'customer'));
		echo printTuple(($flag?'':"class='bg'"),'Project Id',$projectInfo['projectId']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Project Name',$projectInfo['projectName']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Address',$customerInfo['addressLine1']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Address',$customerInfo['addressCity'].", ".$customerInfo['addressState']." ".$customerInfo['addressZip']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Customer',$projectInfo['customerId']." ".$projectInfo['customerName'], $customerLink);$flag = !$flag;
		?>
	</table>
</div>
