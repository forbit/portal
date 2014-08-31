<?php
$mine_selected = '';
$all_selected = 'selected';
$onlymine=$_REQUEST['onlymine'];

if($onlymine == 'true') {
    $mine_selected = 'selected';
    $all_selected = '';
}
?>
<form class="form-inline form-actions" role="form" name="index" method="post" action="index.php">
    <input type="hidden" name="module">
    <input type="hidden" name="action">
    <input type="hidden" name="fun">
    <div class="form-group">
        <div class="form-group">
            <label class="control-label" for="ticket_status_combo"><?= getTranslatedString('SHOW') ?></label>
            <select id="ticket_status_combo" class="form-control" onchange="this.form.module.value='HelpDesk';this.form.action.value='index';this.form.fun.value='home'; showTickets(index)">
                <?= getStatusComboList($show); ?>
            </select>
        </div>
    </div>

	<?php
        $show = $client->call('show_all',array('module'=>'HelpDesk'), $Server_Path, $Server_Path);
        if($show == 'true') {
		echo '<div class="form-group">
                <div class="form-group">
                    <select  class="form-control" id="only_mine_combo" name="list_type" onchange="getList(this, \'HelpDesk\');">
				        <option value="mine" '. $mine_selected .'>'.getTranslatedString('MINE').'</option>
				        <option value="all"'. $all_selected .'>'.getTranslatedString('ALL').'</option>
				    </select>
                </div>
            </div>';
		}
	?>
    <button class="btn btn-info button-form" name="srch" type="button" onClick="showSearchFormNow('tabSrch');">
        <span class="glyphicon glyphicon-search"></span>  <?= getTranslatedString('LBL_SEARCH');?>
    </button>
    <button class="btn btn-primary button-form" name="newticket" type="submit" onclick="this.form.module.value='HelpDesk';this.form.action.value='index';this.form.fun.value='newticket'">
        <span class="glyphicon glyphicon-pencil"></span> <?= getTranslatedString('LBL_NEW_TICKET');?>
    </button>
</form>

<?php
global $result;
$list = '';
$closedlist = '';
if($result == '') {
    $list .= '<div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">' . getTranslatedString('LBL_TICKETS') . ' ' . getTranslatedString($_REQUEST['showstatus']) .'</h3>
                </div>
                <div class="panel-body">
                ' . getTranslatedString('LBL_NONE_SUBMITTED') . '
                </div>
            </div>';
} else {
	$header = $result[0]['head'][0];
	$nooffields = count($header);
	$data = $result[1]['data'];
	$rowcount = count($data);
	$showstatus = $_REQUEST['showstatus'];
	if($showstatus != '' && $rowcount >= 1) {
        $list .= '<div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">' . getTranslatedString('LBL_TICKETS') . ' ' . getTranslatedString($showstatus) .'</h3>
                    </div>
                    <div class="panel-body">';

		$list .= '<table class="table table-bordered table-hover">';
		$list .= '<thead>';
		for($i = 0; $i < $nooffields; $i++) {
			$header_value = $header[$i]['fielddata'];
			$list .= "<th>{$header_value}</th>";
		}
		$list .= '</thead>';

		$ticketexist = 0;
		for($i = 0; $i < count($data); $i++) {
			$ticketlist = '';

			$ticket_status = '';
			for($j = 0; $j < $nooffields; $j++) {
				$ticketlist .= '<td>'.getTranslatedString($data[$i][$j]['fielddata']).'</td>';
				if ($header[$j]['fielddata'] == 'Status') {
					$ticket_status = $data[$i][$j]['fielddata'];
				}
			}
			$ticketlist .= '</tr>';

			if($ticket_status == $showstatus) {
				$list .= $ticketlist;
				$ticketexist++;
			}
		}
		if($ticketexist == 0) {
			$list .= '<tr><td colspan="'.$nooffields.'">'.getTranslatedString('LBL_NONE_SUBMITTED').'</td></tr>';
		}

		$list .= '</table></div></div>';

	} else {
		$list .= '<div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">' . getTranslatedString('LBL_MY_OPEN_TICKETS') . '</h3>
                    </div>
                    <div class="panel-body">';

		$list .= '<table class="table table-bordered table-hover">';
		$list .= '<thead>';

        $closedlist .= '<div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">' . getTranslatedString('LBL_CLOSED_TICKETS') . '</h3>
                            </div>
                            <div class="panel-body">';

		$closedlist .= '<table class="table table-bordered table-hover">';
		$closedlist .= '<thead>';

		for($i = 0; $i < $nooffields; $i++) {
			$header_value = $header[$i]['fielddata'];
			$headerlist .= '<th class="detailedViewHeader" align="center">'.getTranslatedString($header_value).'</th>';
		}
		$headerlist .= '</thead>';

		$list .= $headerlist;
		$closedlist .= $headerlist;

		for($i = 0; $i < count($data); $i++) {
			$ticketlist = '<tr>';
			$ticket_status = '';

			for($j = 0; $j < $nooffields; $j++) {
				$ticketlist .= '<td>'.$data[$i][$j]['fielddata'].'</td>';
				if ($header[$j]['fielddata'] == 'Status') {
					$ticket_status = $data[$i][$j]['fielddata'];
				}
			}
			$ticketlist .= '</tr>';

			if($ticket_status == getTranslatedString('LBL_STATUS_CLOSED')) {
				$closedlist .= $ticketlist;
            } elseif($ticket_status != '') {
				$list .= $ticketlist;
            }
		}

		$list .= '</table></div></div>';
		$closedlist .= '</table></div></div>';

		$list .= $closedlist;
	}
}

echo $list;