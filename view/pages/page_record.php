<?php
$totalPlannedDebit = 0;
if ($accountType == 0)
{
	?>
	<h1><?= $translator->getTranslation('Situation des comptes') ?></h1>

	<table class="summaryTable">
	<?php
	$accountsHandler = new AccountsHandler();
	$accounts = $accountsHandler->GetAllOrdinaryAccounts();
	
	foreach ($accounts as $account)
	{
		
		if ($account->get('type') != 2 && $account->get('type') != 4)
		{
			$balance = $account->GetBalance();
			?>
	
			<tr>
			<td><a href="#" onclick="javascript:ChangeAccount('<?= $account->get('accountId') ?>'); return false;"><?= $account->get('name') ?></a></td>
			<td style='text-align: right;<?php 
			if ($account->get('type') != 5)
			{
				if ($balance <= $account->get('expectedMinimumBalance'))
					echo 'background-color: #FF0000';
				else
					echo 'background-color: #00FF00';
			}
			?>'><?= $translator->getCurrencyValuePresentation($balance) ?></td>
			<td style='text-align: right; font-style:italic;'><?= $translator->getTranslation($account->getTypeDescription()) ?></td>
			</tr>

			<?php
			$totalPlannedDebit += $account->GetPlannedOutcome(10);
		}
	}
	?>
	</table>
	<br />
<?php
}
?>

<?php
$accountPlannedDebit = $activeAccount->GetPlannedOutcome(10);

// ------------ Affichage du solde d'un compte réel
if ($accountType == 4)
{
	$balance = $activeAccount->GetBalance();
?>
Epargne : <?= $translator->getCurrencyValuePresentation($balance) ?>
<?php
}
else if ($accountType != 2 && $accountType != 0)
{
	$accountExpectedMinimumBalance = $activeAccount->get('expectedMinimumBalance');
	$balance = $activeAccount->GetBalance();
	$balanceConfirmed = $activeAccount->GetBalanceConfirmed();

	$criticalAccountBalance = false;
	if ($accountExpectedMinimumBalance >= ($balance + $accountPlannedDebit))
		$criticalAccountBalance = true;

	if ($criticalAccountBalance)
		echo "<font color='red'>";
	else
		echo "<font color='green'>";
?>
Solde : <?= $translator->getCurrencyValuePresentation($balance) ?>
</font> / <?= $translator->getCurrencyValuePresentation($balanceConfirmed) ?> confirmé (Débit prévus pour les 10 prochains jours : <?= $translator->getCurrencyValuePresentation($accountPlannedDebit) ?>)
<?php
}
else
{
?>
Débit prévus pour les 10 prochains jours : <?= $translator->getCurrencyValuePresentation($totalPlannedDebit) ?>
<?php
}
?>
<br /><br />
<?php

$recordsHandler = new RecordsHandler();

if ($fullRecordsView)
	$result = $recordsHandler->GetAllRecords(12 * 5);
else
	$result = $recordsHandler->GetAllRecords(3);

$now = date('Y-m-d');

// ------ Display a title row
function AddTitleRow()
{
	global $translator, $activeAccount;
	?>
	<tr class="tableRowTitle">
	<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Date') ?></td>
	<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Désignation') ?></td>
	<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Effectuée par') ?></td>
	<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Compte') ?></td>
	<td style="vertical-align: top; text-align: center; font-style: italic;"></td>
	<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Montant') ?></td>
	<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Catégorie') ?></td>
	<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Prise en charge') ?></td>
	<td style="vertical-align: top; text-align: center; font-style: italic;"></td>
	</tr>
	<?php
}

// ------ Add a data row
function AddRow($index, $row, $mergeRow)
{
	global $activeAccount, $now, $translator, $activeUser, $partnerUser;

	$tr = '<tr class="tableRow';
	if ($row['marked_as_deleted']) $tr .= 'Deleted';
	else if ($row['record_date'] > $now) $tr .= 'ToCome';
	else if ($row['record_type'] == 2) $tr .= 'Remark';
	echo $tr;

	if ($index % 2 == 0) echo '0">'; else echo '1">';

	if (!$mergeRow)
	{
		echo '<td>'.($row['record_date'] == 0 ? '' : $row['record_date']).'</td>';
		echo '<td style="text-align: left;">'.$row['designation'].'</td>';
		echo '<td style="text-align: right;">'.$row['user_name'].'</td>';
		echo '<td style="text-align: right;">'.$row['account_name'].'</td>';
	}
	else
	{
		echo '<td></td>';
		echo '<td></td>';
		echo '<td></td>';
		echo '<td></td>';
	}

	//if (!$mergeRow && !($row['record_date'] > $now))
	if (!$mergeRow)
	{
		echo '<td><input type="checkbox" '.($row['confirmed'] == 1 ? 'checked' : '').' onclick="ConfirmRecord(\''.$row['record_id'].'\', this);"></span></td>';
	}
	else
		echo "<td></td>";

	echo '<td style="text-align: right;" ondblclick="ModifyRecord(\''.$row['record_id'].'\', \''.$row['amount'].'\', this);">';
	if ($row['record_type'] == 0 || $row['record_type'] == 3|| $row['record_type'] == 12)
		echo '<font color="blue">';
	else if ($row['record_type'] == 10 || $row['record_type'] == 11)
		echo '<font color="DarkBlue">';
	else if ($row['record_type'] == 20 || $row['record_type'] == 21)
		echo '<font color="DarkRed">';
	else
		echo '<font color="red">';

	if ($row['record_type'] < 2 || $row['record_type'] >= 3)
		echo $translator->getCurrencyValuePresentation($row['amount']);
	
	echo '</font>';
	echo '</td>';

	// Category
	if (isset($row['category']))
	{
		echo "<td style='text-align: left;'>";
	
		if ($row['link_type'] == 'USER') echo "<font color='DarkGreen'>";
		else if ($row['link_type'] == 'DUO') echo "<font color='MediumVioletRed'>";
	
		echo $row['category'];
	
		echo "</font>";
		echo "</td>";
	}
	else if (isset($row['category_id']) && substr($row['category_id'], 0, 5) == "USER/")
	{
		echo "<td style='text-align: left;'>";
	
		$usersHandler = new UsersHandler();
		$user = $usersHandler->GetUser(substr($row['category_id'], 5, 36));

		echo "<font color='DarkGreen'>";
	
		echo 'Non définie / '.$user->getName();
	
		echo "</font>";
		echo "</td>";
	}
	else
		echo "<td></td>";

	// Charge level
	echo '<td style="text-align: left;">';

	if (isset($row['category']) || (isset($row['category_id']) && substr($row['category_id'], 0, 5) == "USER/"))
	{
		echo "<img src='../media/information.png' title='";
		echo $activeUser->get('name')."=".$translator->getCurrencyValuePresentation($row['part_actor1'])." / ".$partnerUser->get('name')."=".$translator->getCurrencyValuePresentation($row['part_actor2']);
		echo "'>";
		echo "&nbsp;";
	}

	if ($row['link_type'] == 'DUO' || $row['link_type'] == 'USER' || (isset($row['category_id']) && substr($row['category_id'], 0, 5) == "USER/"))
		echo $row['charge'].'&nbsp;%';
	echo '</td>';

	// Trash bin
	echo "<td style='text-align: center;'><span class='ui-icon ui-icon-trash' onclick='if (confirm(\"".$translator->getTranslation('Etes-vous sûr de vouloir supprimer cette entrée ?')."\")) { DeleteRecord(\"".$row['record_id']."\"); }'></span></td>";

	echo '</tr>';
}

// ------ Add a subtotal line in case of merged row
function AddSubTotalRow($index, $row, $subtotal)
{
	global $activeAccount, $now, $translator;

	$tr = '<tr class="tableRow';
	if ($row['marked_as_deleted']) $tr .= 'Deleted';
	else if ($row['record_date'] > $now) $tr .= 'ToCome';
	else if ($row['record_type'] == 2) $tr .= 'Remark';
	echo $tr;

	if ($index % 2 == 0) echo '0">'; else echo '1">';

	echo '<td></td><td></td>';
	echo '<td></td>';
	echo '<td></td>';	
	echo "<td></td>";

	echo '<td style="text-align: right;">';
	if ($row['record_type'] == 0 || $row['record_type'] == 3) echo '<font color="blue">';
	else if ($row['record_type'] == 10) echo '<font color="DarkBlue">';
	else if ($row['record_type'] == 20) echo '<font color="DarkRed">';
	else echo '<font color="red">';

	echo '<i>= '.$translator->getCurrencyValuePresentation($subtotal).'</i>';
	echo '</font>';
	echo '</td>';

	echo '<td></td><td></td>';

	echo '<td style="text-align: center;"><span class="ui-icon ui-icon-trash" onclick="if (confirm(\''.$translator->getTranslation('Etes-vous sûr de vouloir supprimer cette entrée ?').'\')) { DeleteRecord(\''.$row['record_id'].'\'); }"></span></td>';
	echo '</tr>';
}

$index = 0;
$previousRow = null;
$subTotal = 0;
$mergeRow = false;
?>
<table id="recordsTable">
<thead>
<?php AddTitleRow(); ?>
</thead>
<tbody>
<?php
while ($row = $result->fetch())
{
	// Display only deleted rows in full view
	if ($row['marked_as_deleted'] && !$fullRecordsView)
		continue;

	// ------ Merging of rows if similar group
	if ($row['record_group_id'] == '' || $previousRow == null || $previousRow['record_group_id'] != $row['record_group_id'] || $previousRow['record_type'] != $row['record_type'])
	{
		if ($mergeRow)
		{
			AddSubTotalRow($index, $previousRow, $subTotal);
		}
		$index++;
		$mergeRow = false;
		$subTotal = 0;
	}
	else
	{
		$mergeRow = true;
	}

	if ($row['record_date'] <= $now)
	{
		if ($previousRow != null && $previousRow['record_date_month'] != $row['record_date_month'])
		{
			AddTitleRow();
		}
	}

	AddRow($index, $row, $mergeRow);
	$subTotal += $row['amount'];

	$previousRow = $row;
}
?>
  </tbody>
</table>

<br />
<?php if (!$fullRecordsView) { ?>
<button onclick="LoadRecords_All();">Voir toutes les lignes</button>
<?php } else { ?>
<button onclick="LoadRecords_Normal();">Revenir à la vue normale</button>
<?php } ?>

<script type="text/javascript">

$(function() {

	var amount = $( "#amount" );
	var recordId = $( "#recordId" );

	$( "#dialog-form" ).dialog({
	    autoOpen: false,
	    height: 160,
	    width: 350,
	    modal: true,
	    buttons: {
	      "Modifier": function() {
	    		$.post (
	    				'../controller/controller.php?action=record_amount_modify',
	    				{ recordId: recordId.val() , amount: amount.val() },
	    				function(response, status) {
	    					LoadRecords();
	    					$( "#dialog-form" ).dialog( "close" );
						}
	    			);
				
	      },
	      Cancel: function() {
	        $( this ).dialog( "close" );
	      }
	    },
	    close: function() {
	    }
	  });

	$("#dialog-form").keydown(function (event) {
        if (event.keyCode == 13) {
            $(this).parent()
                   .find("button:eq(1)").trigger("click");
            return false;
        }
    });

	$( "#amount" ).focus(function() { $(this).select(); } );
});

function ModifyRecord(recordIdToModify, amount, sender)
{
	$( "#recordId" ).val(recordIdToModify);
	$( "#amount" ).val(amount);
	$( "#dialog-form" ).dialog( "open" );
}

</script>

<div id="dialog-form" title="Modifier un montant">
  <form>
  <fieldset>
    <label for="amount">Nouveau montant</label>
    <input type="text" name="amount" autocomplete="off" id="amount" class="text ui-widget-content ui-corner-all">
    <input type="hidden" name="recordId" id="recordId">
    </fieldset>
  </form>
</div>