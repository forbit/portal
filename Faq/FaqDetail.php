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

require_once('include/Zend/Json.php');
	
if($_REQUEST['faqid'] != ''){
	$faqid = Zend_Json::decode($_REQUEST['faqid']);
   
}	

$faq_array = $_SESSION['faq_array'];
$customerid = $_SESSION['customer_id'];
$sessionid = $_SESSION['customer_sessionid'];

$list = '<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="dummy">';
for($i=0;$i<count($faq_array);$i++)
{

	if($faqid == $faq_array[$i]['id'])
	{
		$faq_id = $faq_array[$i]['id'];
		$faq_module_no = $faq_array[$i]['faqno'];
		$faq_createdtime = $faq_array[$i]['faqcreatedtime'];
		$faq_modifiedtime = $faq_array[$i]['faqmodifiedtime'];
		$faq_productid = $faq_array[$i]['product_id'];
		$faq_category = $faq_array[$i]['category'];

		$comments_array = $_SESSION['faq_array'][$i]['comments'];
		$createdtime_array = $_SESSION['faq_array'][$i]['createdtime'];

		$comments_count = count($comments_array);

		$list .= '<tr><td colspan="2" class="detailedViewHeader">'.getTranslatedString('LBL_FAQ_TITLE').'</td><td align="right" class="detailedViewHeader">
			  <span id="faq" class="lnkHdr" onMouseOver="fnShow(this)" onMouseOut="fnHideDiv(\'faqDetail\')">'.getTranslatedString('LBL_FAQ_DETAIL').'</span></td></tr>';
		$list .= '<tr><td width="75%" valign="top" style="padding-right:5px;" colspan="3">'.$faq_array[$i]['question'];
		$list .= '<br><br><b>'.getTranslatedString('LBL_ANSWER').'</b><br>'.$faq_array[$i]['answer'].'</td>
			    </tr>';

		$list .= '<tr><td colspan="3" class="detailedViewHeader">'.getTranslatedString('LBL_COMMENTS').'</td></tr>';

		$list .= '
			   <tr>
				<td colspan="3">
				   <div id="scrollTab2">
					<table width="98%"  border="0" cellspacing="5" cellpadding="5">';

		for($j=0;$j<$comments_count;$j++)
		{
			$list .= '
					   <tr>
						<td width="5%" valign="top"> '.($comments_count-$j).' </td>
						<td width="95%">
							'.$comments_array[$j];

			if ($createdtime_array[$j]!="0000-00-00 00:00:00")
				$list .= '<br><span class="hdr">'.getTranslatedString('LBL_ADDED_ON').$createdtime_array[$j].'</span>';

			$list .= '
						</td>
					   </tr>';
		}
		$list .= '
					</table>
				   </div>
				</td>
			   </tr>
			   <tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			   </tr>
				<tr><td colspan="3" class="detailedViewHeader">'.getTranslatedString('LBL_DOCUMENTS').'</td></tr>';   		
					$module = 'Documents';
					$params = array('id' => "$faqid",'module'=>"$module", 'contactid'=>"$customerid",'sessionid'=>"$sessionid");
					$result = $client->call('get_documents', $params, $Server_Path, $Server_Path);
					$list .=  getblock_fieldlistview($result,$module);	   	
	   $list .='<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			   </tr>
			   	' .
			   		'<form name="comments" method="POST" action="index.php">
				   <input type="hidden" name="module">
				   <input type="hidden" name="action">
				   <input type="hidden" name="fun">
				   <input type=hidden name=faqid value="'.$faqid.'">
			   <tr>
				<td colspan="3" class="detailedViewHeader">'.getTranslatedString('LBL_ADD_COMMENT').'</td>
			   </tr>
			   <tr>
				<td colspan="3" class="dvtCellInfo">
					<textarea name="comments" cols="80" rows="5" class="detailedViewTextBox">&nbsp;</textarea>
				</td>
			   </tr>
				<tr>
				<td colspan="3" class="dvtCellInfo">
					<input title="'.getTranslatedString('LBL_SAVE_ALT').'" accesskey="S" class="small"  name="submit" value="'.getTranslatedString('LBL_SUBMIT').'" style="width: 70px;" type="submit" onclick="this.form.module.value=\'Faq\';this.form.action.value=\'index\';this.form.fun.value=\'faq_updatecomment\'; if(trim(this.form.comments.value) != \'\') return true; else return false;"/>
				</td>
			   </tr>
			   	<tr>
				<td colspan="2">&nbsp;</td>
			   </tr>
				</form>

			   <tr>
				<td style="padding:3px;">'.getPageOption().'</td>
			   </tr>';
	}
}

$list .= '		</table>';

//This is added to get the FAQ details as a Popup on Mouse over
$list .= getArticleIdTime($faq_module_no,$faq_productid,$faq_category,$faq_createdtime,$faq_modifiedtime);

echo $list;





?>
