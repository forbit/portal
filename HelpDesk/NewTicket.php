<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/

global $client;

$customerid = $_SESSION['customer_id'];
$sessionid = $_SESSION['customer_sessionid'];

$params = Array(Array('id'=>"$customerid", 'sessionid'=>"$sessionid"));
$result = $client->call('get_combo_values', $params, $Server_Path, $Server_Path);

$_SESSION['combolist'] = $result;
$combolist = $_SESSION['combolist'];
for($i=0;$i<count($result);$i++)
{
	if($result[$i]['productid'] != '')
	{
		$productslist[0] = $result[$i]['productid'];
	}
	if($result[$i]['productname'] != '')
	{
		$productslist[1] = $result[$i]['productname'];
	}
	if($result[$i]['ticketpriorities'] != '')
	{
		$ticketpriorities = $result[$i]['ticketpriorities'];
	}
	if($result[$i]['ticketseverities'] != '')
	{
		$ticketseverities = $result[$i]['ticketseverities'];
	}
	if($result[$i]['ticketcategories'] != '')
	{
		$ticketcategories = $result[$i]['ticketcategories'];
	}
	if($result[$i]['servicename'] != ''){
		$servicename = $result[$i]['servicename'];
	}
	if($result[$i]['serviceid'] != ''){
		$serviceid= $result[$i]['serviceid'];
	}
}

if($productslist[0] != '#MODULE INACTIVE#'){
	$noofrows = count($productslist[0]);

	for($i=0;$i<$noofrows;$i++)
	{
		if($i > 0)
			$productarray .= ',';
		$productarray .= "'".$productslist[1][$i]."'";
	}
}
if($servicename == '#MODULE INACTIVE#' || $serviceid == '#MODULE INACTIVE#'){
	unset($servicename);
	unset($serviceid);
}

?>
<form class="row" role="form" name="Save" method="post" action="index.php">
	<div class="col-sm-offset-3">
		<div class="col-sm-8">
            <h1 class="page-header"><?= getTranslatedString('LBL_NEW_TICKET') ?></h1>
        </div>
	   	<input type="hidden" name="module" value="HelpDesk">
	   	<input type="hidden" name="action" value="index">
	   	<input type="hidden" name="fun" value="saveticket">
		<input type="hidden" name="projectid" value="<?php echo $_REQUEST['projectid'] ?>" />
		<div class="row">
		  	<div class="col-sm-8">
				<div class="form-group">
				  	<label for="title" class="control-label"><font color="red">*</font> <?= getTranslatedString('TICKET_TITLE') ?></label>
			  		<input type="text" id="title" name="title" class="form-control">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-8">
				<div class="form-group">
					<!-- Product auto drop down - start -->
					<style>@import url( css/dropdown.css );</style>
					<script src="js/modomt.js"></script>
					<script src="js/getobject2.js"></script>
					<script src="js/acdropdown.js"></script>
					<script language="javascript">var products = new Array(<?= $productarray ?>);</script>
					<!-- Product auto drop down - end -->

			  		<label for="inputer2" class="control-label"><?= getTranslatedString('LBL_PRODUCT_NAME') ?></label>
			  		<input class="form-control" autocomplete="off" name="productid" id="inputer2" acdropdown="true" autocomplete_list="array:products" autocomplete_list_sort="false" autocomplete_matchsubstring="true">
				</div>
			</div>
		</div>
		<div class="row">
		  	<div class="col-sm-4">
		  		<div class="form-group">
		  			<label for="priority" class="control-label"><?= getTranslatedString('LBL_TICKET_PRIORITY') ?></label>
	  				<?= getComboList('priority', $ticketpriorities) ?>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
			  		<label for="severity" class="control-label"><?= getTranslatedString('LBL_TICKET_SEVERITY') ?></label>
		  			<?= getComboList('severity', $ticketseverities) ?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
				  	<label for="servicename" class="control-label"><?= getTranslatedString('LBL_SERVICE_CONTRACTS') ?></label>
					<?php
						$list = '<option value="">'.getTranslatedString('NONE').'</option>';
						for($i = 0; $i < count($servicename); $i++) {
							$list .= '<option value="'.$serviceid[$i].'" >'.$servicename[$i].'</option>';
						}
					?>
			  		<select class="form-control" id="servicename	" name="servicename">
			  			<?= $list ?>
		  			</select>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
			  		<label for="category" class="control-label"><?= getTranslatedString('LBL_TICKET_CATEGORY') ?></label>
		  			<?= getComboList('category',$ticketcategories) ?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-8">
				<div class="form-group">
				  	<label for="description" class="control-label"><?= getTranslatedString('LBL_DESCRIPTION') ?></label>
					<textarea name="description" id="description" cols="55" rows="5" class="form-control"></textarea>
				</div>
			</div>
		</div>
		<div class="row">
		  	<div class="col-sm-offset-3">
		    	<button class="btn btn-primary" title="<?= getTranslatedString('LBL_SAVE_ALT');?>" accesskey="S" name="button" type="submit" onclick="return formvalidate(this.form)">
		    		<span class="glyphicon glyphicon-send"></span> <?= getTranslatedString('LBL_SAVE') ?>
				</button>
		    	<button class="btn btn-default" title="<?= getTranslatedString('LBL_CANCEL_ALT');?>" accesskey="X" name="button" type="button" onclick="window.history.back()";>
		    		<span class="glyphicon glyphicon-remove-sign"></span> <?= getTranslatedString('LBL_CANCEL');?>
	    		</button>
			</div>
		</div>
	</div>
</form>
<script>
function formvalidate(form) {
	if(trim(form.title.value) == '') {
		alert("Ticket Title is empty");
		return false;
	}

	return true;
}

function trim(s) {
	while (s.substring(0,1) == " ") {
		s = s.substring(1, s.length);
	}

	while (s.substring(s.length-1, s.length) == ' ') {
		s = s.substring(0,s.length-1);
	}

	return s;
}
</script>