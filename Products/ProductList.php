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

global $result;
$customerid = $_SESSION['customer_id'];
$username = $_SESSION['customer_name'];
$sessionid = $_SESSION['customer_sessionid'];

$onlymine=$_REQUEST['onlymine'];
if($onlymine == 'true') {
    $mine_selected = 'selected';
    $all_selected = '';
} else {
    $mine_selected = '';
    $all_selected = 'selected';
}

$block = 'Products';	

		$params = Array('id'=>$customerid,'block'=>$block,'sessionid'=>$sessionid,'onlymine'=>$onlymine);
		$result = $client->call('get_product_list_values', $params, $Server_Path, $Server_Path);

	echo '<tr>
	 			<td><span class="lvtHeaderText">'.getTranslatedString("LBL_PRODUCT_INFORMATION").'</span</td>';    
		$allow_all = $client->call('show_all',array('module'=>'Products'),$Server_Path, $Server_Path);
	
	    if($allow_all == 'true'){
	      		echo '<td align="right" style="padding-right:50px;"> <b>'.getTranslatedString('SHOW').'</b>&nbsp; <select name="list_type" onchange="getList(this, \'Products\');">
	 			<option value="mine" '. $mine_selected .'>'.getTranslatedString('MINE').'</option>
				<option value="all"'. $all_selected .'>'.getTranslatedString('ALL').'</option>
				</select></td></tr>';
	    		}
	      		
	      		echo '<tr><td colspan="2"><hr noshade="noshade" size="1" width="100%" align="left">
	      		<table width="95%"  border="0" cellspacing="0" cellpadding="5" align="center">';;

	echo getblock_fieldlistview_product($result,$block);
	echo '</table></td></tr></table></td></tr></table>';
echo '<!-- --End--  -->';


?>




