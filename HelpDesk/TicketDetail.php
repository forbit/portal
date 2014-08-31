<form class="form-inline form-actions" role="form" name="index" method="post" action="index.php">
    <input type="hidden" name="module">
    <input type="hidden" name="action">
    <input type="hidden" name="fun">
    <button class="btn btn-default" type="button" onclick="window.history.back();">
        <span class="glyphicon glyphicon-arrow-left"></span> <?= getTranslatedString('LBL_BACK_BUTTON') ?>
    </button>

    <button class="btn btn-info button-form" name="srch" type="button" onClick="showSearchFormNow('tabSrch');">
        <span class="glyphicon glyphicon-search"></span> <?= getTranslatedString('LBL_SEARCH') ?>
    </button>

	<button class="btn btn-primary button-form" name="newticket" type="submit" onclick="this.form.module.value='HelpDesk';this.form.action.value='index';this.form.fun.value='newticket'">
        <span class="glyphicon glyphicon-pencil"></span> <?= getTranslatedString('LBL_NEW_TICKET') ?>
    </button>
</form>

<?php

global $result;
global $client;
global $Server_Path;
$customerid = $_SESSION['customer_id'];
$sessionid = $_SESSION['customer_sessionid'];
if($ticketid != '') {
	$params = array('id' => "$ticketid", 'block'=>"$block",'contactid'=>$customerid,'sessionid'=>"$sessionid");
	$result = $client->call('get_details', $params, $Server_Path, $Server_Path);
	// Check for Authorization
	if (count($result) == 1 && $result[0] == "#NOT AUTHORIZED#") {
		echo '<tr>
			<td colspan="6" align="center"><b>'.getTranslatedString('LBL_NOT_AUTHORISED').'</b></td>
		</tr></table></td></tr></table></td></tr></table>';
		include("footer.html");
		die();
	}

	$ticketinfo = $result[0][$block];
	$params = Array(Array('id'=>"$customerid", 'sessionid'=>"$sessionid", 'ticketid' => "$ticketid"));
	$commentresult = $client->call('get_ticket_comments', $params, $Server_Path, $Server_Path);
	$ticketscount = count($result);
	$commentscount = count($commentresult);
	$params = Array(Array('id'=>"$customerid", 'sessionid'=>"$sessionid", 'ticketid' => "$ticketid"));

	//Get the creator of this ticket
	$creator = $client->call('get_ticket_creator', $params, $Server_Path, $Server_Path);

	$ticket_status = '';
	foreach ($ticketinfo as $key=>$value) {
		$fieldlabel = $value['fieldlabel'];
		$fieldvalue = $value['fieldvalue'];
		if ($fieldlabel == 'Status') {
			$ticket_status = $fieldvalue;
			break;
		}
	}
	//If the ticket is created by this customer and status is not Closed then allow him to Close this ticket otherwise not
	$ticket_close_link = '';
	if ($ticket_status != 'Closed' && $ticket_status != '') {
		$ticket_close_link = '<a href=index.php?module=HelpDesk&action=index&fun=close_ticket&ticketid='.$ticketid.'>'.getTranslatedString('LBL_CLOSE_TICKET').' <span class="glyphicon glyphicon-remove"></span></a>';
	}

    echo getblock_header('Ticket Information', $ticket_close_link);
	echo getblock_fieldlist($ticketinfo);

	if ($commentscount >= 1 && is_array($commentresult)) {
		$list .= '
		   <div class="panel panel-default">
   				<div class="panel-heading">'.getTranslatedString('LBL_TICKET_COMMENTS').'</div>
	   			<table class="table table-hover">';

		//Form the comments in between tr tags
		for($j = 0; $j < $commentscount; $j++) {
			$list .= '<tr>
						<td>'. ($commentscount - $j) .'</td>
						<td>'
							. $commentresult[$j]['comments'] . '<br>
							<small>'.getTranslatedString('LBL_COMMENT_BY').' : '.$commentresult[$j]['owner'].' '.getTranslatedString('LBL_ON').' '.$commentresult[$j]['createdtime'].'</small>
						</td>
				   	  </tr>';
		}

		$list .= '</table></div>';
	}

	if($ticket_status != 'Closed') {
		$list .= '
		   <div class="panel panel-default">
   				<div class="panel-heading">'.getTranslatedString('LBL_ADD_COMMENT').'</div>
	   			<div class="panel-body">
		   			<form name="comments" action="index.php" method="post">
			   			<input type="hidden" name="module">
			   			<input type="hidden" name="action">
		   				<input type="hidden" name="fun">
		   				<input type=hidden name=ticketid value='.$ticketid.'>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
					              <textarea name="comments" cols="55" rows="5" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12" style="text-align:right;">
		   				       <button class="btn btn-success" title="'.getTranslatedString('LBL_SUBMIT').'" accesskey="S"  name="submit" value="'.getTranslatedString('LBL_SUBMIT').'" type="submit" onclick="this.form.module.value=\'HelpDesk\';this.form.action.value=\'index\';this.form.fun.value=\'updatecomment\'; if(trim(this.form.comments.value) != \'\')	return true; else return false;">
                                    <span class="glyphicon glyphicon-hand-up"></span> ' . getTranslatedString('LBL_SUBMIT') .
                                '</button>
                            </div>
                        </div>
				   	</form>
			   	</div>
		   </div>';
	}

	$files_array = getTicketAttachmentsList($ticketid);
    if ($files_array[0] != "#MODULE INACTIVE#") {
		$list .= '
			   <div class="panel panel-default">
                    <div class="panel-heading">'.getTranslatedString('LBL_ATTACHMENTS').'</div>
                    <table class="table">';

		//Get the attachments list and form in the tr tag
		$attachments_count = count($files_array);
		if (is_array($files_array)) {
			for($j = 0; $j < $attachments_count; $j++) {
				$filename = $files_array[$j]['filename'];
				$filetype = $files_array[$j]['filetype'];
				$filesize = $files_array[$j]['filesize'];
				$fileid = $files_array[$j]['fileid'];
				$filelocationtype = $files_array[$j]['filelocationtype'];
				//To display the attachments title
				$attachments_title = '';
				if ($j == 0){
					$attachments_title = '<b>'.getTranslatedString('LBL_ATTACHMENTS').'</b>';
                }

				if ($filelocationtype == 'I') {
					$list .= '
					   		<tr>
								<td class="detail-label" align="right">'.$attachments_title.'</td>
								<td class="dvtCellInfo" colspan="3"><a href="index.php?downloadfile=true&fileid='.$fileid.'&filename='.$filename.'&filetype='.$filetype.'&filesize='.$filesize.'&ticketid='.$ticketid.'">'.ltrim($filename,$ticketid.'_').'</a></td>
					   		</tr>';
				} else {
					$list .='<tr>' .
								'<td class ="detail-label" align="right">'.$attachments_title.'</td>' .
								'<td class="dvtCellInfo" colspan="3"><a target="blank" href='.$filename.'>'.$filename.'</a></td>' .
							'</tr>';
				}
			}
		} else {
			$list .= '<tr><td colspan="4" align="center"><b>'.getTranslatedString('NO_ATTACHMENTS').'</b></td></tr>';
		}
	}

	//To display the file upload error
	if ($upload_status != '') {
		$list .= '<tr>
				    <td class="dvtCellLabel" align="right"><b>'.getTranslatedString('LBL_FILE_UPLOADERROR').'</b></td>
				    <td class="dvtCellInfo" colspan="3"><font color="red">'.$upload_status.'</font></td>
			   </tr>';
	}

	//Provide the Add Comment option if the ticket is not Closed
	if ($ticket_status != 'Closed' && $files_array[0] != "#MODULE INACTIVE#") {
		//To display the File Browse option to upload attachment
		$list .= '
		   <tr>
			<form name="fileattachment" method="post" enctype="multipart/form-data" action="index.php">
			<input type="hidden" name="module" value="HelpDesk">
			<input type="hidden" name="action" value="index">
			<input type="hidden" name="fun" value="uploadfile">
			<input type="hidden" name="ticketid" value="'.$ticketid.'">
				<td class="detail-label" align="right"><b>'.getTranslatedString('LBL_ATTACH_FILE').'</b></td>
				<td colspan=2 class="dvtCellInfo"><input type="file" size="50" name="customerfile" class="detailedViewTextBox" onchange="validateFilename(this)" />
				<input type="hidden" name="customerfile_hidden"/></td>
				<td class="dvtCellInfo" align="right">
                    <button class="btn btn-primary" name="Attach" type="submit" value="'.getTranslatedString('LBL_ATTACH').'">
                        <span class="glyphicon glyphicon-paperclip"></span>  ' . getTranslatedString('LBL_ATTACH') .
                    '</button>
                </td>
			</form>
		   </tr>';
	}

	$list .= '
		</table>
	 </td>
   </tr>
</table>
</td></tr>

';


	echo $list;
	echo '</table></td></tr>';
	echo '</table></td></tr></table></td></tr></table>';
	echo '<!-- --End--  -->';

}
else
	echo getTranslatedString('LBL_NONE_SUBMITTED');


$filevalidation_script = <<<JSFILEVALIDATION
<script type="text/javascript">

function getFileNameOnly(filename) {
	var onlyfilename = filename;
  	// Normalize the path (to make sure we use the same path separator)
 	var filename_normalized = filename.replace(/\\\\/g, '/');
  	if(filename_normalized.lastIndexOf("/") != -1) {
    	onlyfilename = filename_normalized.substring(filename_normalized.lastIndexOf("/") + 1);
  	}
  	return onlyfilename;
}
/* Function to validate the filename */
function validateFilename(form_ele) {
if (form_ele.value == '') return true;
	var value = getFileNameOnly(form_ele.value);
	// Color highlighting logic
	var err_bg_color = "#FFAA22";
	if (typeof(form_ele.bgcolor) == "undefined") {
		form_ele.bgcolor = form_ele.style.backgroundColor;
	}
	// Validation starts here
	var valid = true;
	/* Filename length is constrained to 255 at database level */
	if (value.length > 255) {
		alert(alert_arr.LBL_FILENAME_LENGTH_EXCEED_ERR);
		valid = false;
	}
	if (!valid) {
		form_ele.style.backgroundColor = err_bg_color;
		return false;
	}
	form_ele.style.backgroundColor = form_ele.bgcolor;
	form_ele.form[form_ele.name + '_hidden'].value = value;
	return true;
}
</script>
JSFILEVALIDATION;

echo $filevalidation_script;
?>