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

session_start();

$errormsg = '';
require_once("PortalConfig.php");
if(!isset($_SESSION['customer_id']) || $_SESSION['customer_id'] == '') {
	@header("Location: $Authenticate_Path/login.php");
	exit;
}
require_once("include/utils/utils.php");
$default_language = getPortalCurrentLanguage();
require_once("language/".$default_language.".lang.php");
global $default_charset;
header('Content-Type: text/html; charset='.$default_charset);

if($_REQUEST['fun'] != '' && $_REQUEST['fun'] == 'savepassword')
{
	include("include.php");
	require_once("HelpDesk/Utils.php");
	include("version.php");
	global $version;
	$errormsg = SavePassword($version);
}

if($_REQUEST['last_login'] != '')
{
	$last_login = portal_purify(stripslashes($_REQUEST['last_login']));
	$_SESSION['last_login'] = $last_login;
}
elseif($_SESSION['last_login'] != '')
{
	$last_login = $_SESSION['last_login'];
}

if($_REQUEST['support_start_date'] != '')
	$_SESSION['support_start_date'] = $support_start_date = portal_purify(stripslashes(
		$_REQUEST['support_start_date']));
elseif($_SESSION['support_start_date'] != '')
	$support_start_date = $_SESSION['support_start_date'];

if($_REQUEST['support_end_date'] != '')
	$_SESSION['support_end_date'] = $support_end_date = portal_purify(stripslashes(
		$_REQUEST['support_end_date']));
elseif($_SESSION['support_end_date'] != '')
	$support_end_date = $_SESSION['support_end_date'];

?>
<!-- added for popup My Settings -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
   <head>
	<title><?php echo getTranslatedString('LBL_MY_SETTINGS');?></title>
	<link href="css/style.css" rel="stylesheet" type="text/css">
   </head>

   <body>
	<table width="95%"  border="0" cellspacing="0" cellpadding="3" align="center">
	   <form name="savepassword" action="MySettings.php" method="post">
	   <input type="hidden" name="fun" value="savepassword">
	   <tr><td colspan="2"></td></tr>
	   <tr>
		<td height="30" align="left"><b style="text-decoration:underline"><?PHP echo getTranslatedString('LBL_MY_SETTINGS');?></b></td>
		<td align="right" ><a href="javascript:window.close();"><?php echo getTranslatedString('LBL_CLOSE');?></a></td>
	   </tr>
	   <tr><td colspan="2">&nbsp;</td></tr>
	   <tr>
		<td colspan="2" class="detailedViewHeader"><b><?php echo getTranslatedString('LBL_MY_DETAILS');?></b></td>
	   </tr>
	   <tr>
		<td class="dvtCellLabel" align="right"><?php echo getTranslatedString('LBL_LAST_LOGIN'); ?></td>
		<td class="dvtCellInfo"><b><?php echo $last_login; ?></b></td>
	   </tr>
	   <tr>
		<td class="dvtCellLabel" align="right"><?php echo getTranslatedString('LBL_SUPPORT_START_DATE'); ?></td>
		<td class="dvtCellInfo"><b><?php echo $support_start_date; ?></b></td>
	   </tr>
	   <tr>
		<td class="dvtCellLabel" align="right"><?php echo getTranslatedString('LBL_SUPPORT_END_DATE'); ?></td>
		<td class="dvtCellInfo"><b><?php echo $support_end_date; ?></b></td>
	   </tr>
	   <tr><td colspan="2">&nbsp;</td></tr>
	   <tr><td colspan="2"><?php echo $errormsg; ?></td></tr>
	   <tr>
		<td colspan="2" class="detailedViewHeader"><b><?php echo getTranslatedString('LBL_CHANGE_PASSWORD'); ?></b></td>
	   </tr>
	   <tr>
		<td class="dvtCellLabel" align="right"><?php echo getTranslatedString('LBL_OLD_PASSWORD'); ?></td>
		<td class="dvtCellInfo">
			<input type="password" name="old_password" class="detailedViewTextBox"  onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" value="">
		</td>
	   </tr>
	   <tr>
		<td class="dvtCellLabel" align="right"><?php echo getTranslatedString('LBL_NEW_PASSWORD'); ?></td>
		<td class="dvtCellInfo">
			<input type="password" name="new_password" class="detailedViewTextBox"  onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" value="">
		</td>
	   </tr>
	   <tr>
		<td class="dvtCellLabel" align="right"><?php echo getTranslatedString('LBL_CONFIRM_PASSWORD'); ?></td>
		<td class="dvtCellInfo">
			<input type="password" name="confirm_password" class="detailedViewTextBox"  onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" value="">
		</td>
	   </tr>
	   <tr><td colspan="2" class="dvtCellInfo">&nbsp;</td></tr>
	   <tr>
		<td colspan="2" align="center">
		   <input name="savepassword" type="submit" value="<?php echo getTranslatedString('LBL_SAVE'); ?>" onclick="return verify_data(this.form)">&nbsp;&nbsp;
		   <input name="Close" type="button" value="<?php echo getTranslatedString('LBL_CLOSE'); ?>" onClick="window.close();">
		</td>
	   </tr>
	   <tr>
		<td colspan="2">&nbsp;</td>
	   </tr>
	   </form>
	</table>

	<script>
		function verify_data(form)
		{
		        oldpw = trim(form.old_password.value);
		        newpw = trim(form.new_password.value);
		        confirmpw = trim(form.confirm_password.value);
		        if(oldpw == '')
		        {
				alert("Enter Old Password");
		                return false;
		        }
		        else if(newpw == '')
		        {
				alert("Enter New Password");
		                return false;
		        }
		        else if(confirmpw == '')
		        {
				alert("Confirm the New Password");
		                return false;
		        }
		        else
		        {
		                return true;
		        }
		}
		function trim(s)
		{
		        while (s.substring(0,1) == " ")
		        {
		                s = s.substring(1, s.length);
		        }
		        while (s.substring(s.length-1, s.length) == ' ')
		        {
		                s = s.substring(0,s.length-1);
		        }

		        return s;
		}
	</script>
   </body>
</html>


