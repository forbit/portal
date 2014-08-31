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

require_once("PortalConfig.php");
require_once("language/$default_language.lang.php");
include("version.php");
include_once('include/utils/utils.php');

@session_start();
if(isset($_SESSION['customer_id']) && isset($_SESSION['customer_name']))
{
	header("Location: index.php?action=index&module=.'$module'");
	exit;
}
if($_REQUEST['close_window'] == 'true')
{
   ?>
	<script language="javascript">
        	window.close();
	</script>
   <?php
}
global $default_charset;
header('Content-Type: text/html; charset='.$default_charset);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>vtiger CRM 5 - CustomerPortal</title>
<link href="css/style.css" rel="stylesheet" type="text/css">
</head>

<body>
	<table cellspacing="0" cellpadding="0" class="outerTab">
	   <tr>
		<td width="15%"><br><br><br></td>
		<td width="70%">&nbsp;</td>
		<td width="15%">&nbsp;</td>
	   </tr>
	   <tr>
		<td>&nbsp;</td>
		<td>
			<table class="innerTab"  cellspacing="0" cellpadding="0">
			   <tr>
				<th align="left"><img src="images/loginVtigerCRM.gif" width="169" height="49"></th>
				<th>&nbsp;</th>
				<th align="right">&nbsp;</th>
			   </tr>
			   <tr class="tableTop"><td colspan="3"></td></tr>
			   <tr>
				<td colspan="3" class="tableMidone">
					<table class="loginTab"  cellspacing="0" cellpadding="0" align="center">
					   <tr>
						<td width="6" height="5"><img src="images/loginSITopLeft.gif"></td>
						<td bgcolor="#FFFFFF"></td>
						<td width="6" height="5"><img src="images/loginSITopRight.gif"></td>
					   </tr>
					   <tr bgcolor="#FFFFFF">
						<td height="150">&nbsp;</td>
						<td valign="top">
							<table width="100%"  border="0" cellspacing="0" cellpadding="3">
							<form name="login" action="CustomerAuthenticate.php" method="post">
							   <tr>
								<?php
								   //Display the login error message 
								   if($_REQUEST['login_error'] != '')
									echo getTranslatedString(base64_decode($_REQUEST['login_error'])); 
								?>
							   </tr>
							   <tr>
						   <td colspan="2" class="detailedViewHeader"><b><?php echo getTranslatedString('customerportal');echo " ".$version; ?></b></td>
							   </tr>
							   <tr>
								<td class="dvtCellLabel"  align="right" width="50%"><?php echo getTranslatedString('LBL_EMAILID');?></td>
								<td class="dvtCellInfo"><input type="text" id="username" name="username" class="detailedViewTextBox"></td>
							   </tr>
							   <tr>
								<td class="dvtCellLabel" align="right"><?php echo getTranslatedString('LBL_PASSWORD');?></td>
								<td class="dvtCellInfo"><input type="password" id="pw" name="pw" class="detailedViewTextBox"></td>
							   </tr>
							   <tr>
								<td class="dvtCellLabel" align="right"><?php echo getTranslatedString('LBL_LANGUAGE');?></td>
								<td class="dvtCellInfo">
									<select name='login_language' style="width:70%" >
										<?PHP echo getPortalLanguages(); ?>	
									</select>
								</td>
							   </tr>
							   <tr>
								<td>&nbsp;</td>
								<td align="right"><a href='javascript:;' onclick='window.open("supportpage.php?param=forgot_password","ForgotPassword","width=400,height=250");'><?php  echo getTranslatedString('LBL_FORGOT_LOGIN');?></a></td>
							   </tr>
							   <tr>
								<td colspan="2" align="center"><input type="image" src="images/loginBtnSignin.gif" onclick="return validateLoginDetails();"></td>
							   </tr>
							   <tr>
								<td class="dvtCellInfo" colspan="2"></td>
							   </tr>
							   <tr>
							   <td class="dvtCellInfo" colspan="2" ><font color="gray" size="1"><?php  echo getTranslatedString('LBL_LOGIN_NOTE');?></font></td>
							   </tr>
							</table>
						</td>
						<td>&nbsp;</td>
					   </tr>
					   <tr>
						<td width="6" height="6"><img src="images/loginSIBottomLeft.gif"></td>
						<td bgcolor="#FFFFFF"></td>
						<td width="6" height="6"><img src="images/loginSIBottomRight.gif"></td>
					   </tr>
					</table>
					</form>
				</td>
			   </tr>
			  <tr>
			    <td colspan="3" class="tableBtm">&nbsp;</td>
		      </tr>
			</table>

		</td>
		<td>&nbsp;</td>
	   </tr>
	   <tr>
		<td>&nbsp;</td>
		<td align="left"><img src="images/loginBottomURL.gif" width="100" height="21"></td>
		<td>&nbsp;</td>
	   </tr>
	  </table>

</body>
</html>

<script language="javascript">
function validateLoginDetails()
{
	var user = trim(document.getElementById("username").value);
	var pass = trim(document.getElementById("pw").value);
	if(user != '')
	{
		if(pass != '')
			return true;
		else
		{
			alert("Please enter a valid password.");
			return false;
		}
	}
	else
	{
		alert("Please enter valid username.");
		return false;
	}
}
function trim(s)
{
	while (s.substring(0,1) == " " || s.substring(0,1) == "\n")
	{
		s = s.substring(1, s.length);
	}
	while (s.substring(s.length-1, s.length) == " " || s.substring(s.length-1,s.length) == "\n") {
		s = s.substring(0,s.length-1);
	}
	return s;
}

</script>

<?php
?>
