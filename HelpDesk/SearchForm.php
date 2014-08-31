<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
?>		
<table  cellpadding="5" cellspacing="0" width="100%" border="0">
	<tr>
		<td colspan="7" align="right"><a href="javascript:fnDown('tabSrch');" class="hdr"><?php echo getTranslatedString('LBL_CLOSE'); ?></a></td>
	</tr>
	<tr>
		<td width="20%">
			<?PHP echo getTranslatedString('TICKETID');?> <br>
			<input name="search_ticketid" type="text" class="inputTxt" value="">
		</td>
		<td width="20%">
			<?PHP echo getTranslatedString('TICKET_TITLE');?><br>
			<input name="search_title" type="text" class="inputTxt" value="">
		</td>
		<td width="10%">
			<?PHP echo getTranslatedString('TICKET_STATUS');?><br>
			<?php
				$status_array = getPicklist('ticketstatus');
				echo getComboList('search_ticketstatus',$status_array,' ');
			?>
		</td>
		<td width="10%">
			<?PHP echo getTranslatedString('TICKET_PRIORITY');?><br>
			<?php
				$priority_array = getPicklist('ticketpriorities');
				echo getComboList('search_ticketpriority',$priority_array,' ');
			?>
		</td>
		<td width="10%">
			<?PHP echo getTranslatedString('TICKET_CATEGORY');?> <br>
			<?php
				$category_array = getPicklist('ticketcategories');
				echo getComboList('search_ticketcategory',$category_array,' ');
			?>
		</td>
		<td width="15%">
			<?PHP echo getTranslatedString('TICKET_MATCH');?> <br>
			<select name="search_match">
				<option value="all"><?php echo getTranslatedString('LBL_ALL'); ?></option>
				<option value="any"><?php echo getTranslatedString('LBL_ANY'); ?></option>
			</select>
		</td> 
		<td width="15%">
			&nbsp; <br>
			<input name="Search" type="submit" value="<?php echo getTranslatedString('LBL_SEARCH'); ?>" class="inputTxt" onclick="fnDown('tabSrch');this.form.module.value='HelpDesk';this.form.action.value='index';this.form.fun.value='search'">
		</td>
	</tr>
   <tr>
		<td colspan="7">&nbsp;</td>
	</tr>
</table> 
