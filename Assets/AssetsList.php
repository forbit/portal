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
global $client;

echo '<tr>
	 	<td><span class="lvtHeaderText">'.getTranslatedString("LBL_ASSET_INFORMATION").'</span</td>';
echo '<tr><td colspan="2"><hr noshade="noshade" size="1" width="100%" align="left">
		<table width="95%"  border="0" cellspacing="0" cellpadding="5" align="center">';

if ($customerid != '' ) {
	$params = array('id' => "$customerid", 'block'=>"$block",'sessionid'=>$sessionid);
	$result = $client->call('get_list_values', $params, $Server_Path, $Server_Path);
	echo getblock_fieldlistview($result,$block);
}
?>