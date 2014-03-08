<table class="blankTable">
<thead>
<th>
<b><?= $translator->getTranslation('Patrimoine personnel') ?></b>
</th>
<th>
<b><?= $translator->getTranslation('Patrimoine partagé') ?></b>
</th>
</thead>
<tr>

<td>



<table class="summaryTable">
<tr>
<td colspan="7"><b><?= $translator->getTranslation('Gestion courante') ?></b></td>
</tr>
<?php
$accountsManager = new AccountsManager();
$sum = 0;
$sumGlobal = 0;

$accounts = $accountsManager->GetAllPrivateAccounts();

foreach ($accounts as $account)
{
	global $sum;

	$balance = $account->GetBalance();
	?>
<tr>
<td><a href="#" onclick="javascript:ChangeContext('records','<?= $account->get('accountId')?>',''); return false;"><?= $account->get('name') ?></a></td>
<td style='text-align: right;'><?= $translator->getCurrencyValuePresentation($balance) ?></td>
</tr>
<?php
	$sum += $balance;
}
?>
<tr>
<td><i><?= $translator->getTranslation('Sous-total') ?></i></td>
<td style='text-align: right;'><i><?= $translator->getCurrencyValuePresentation($sum) ?></i></td>
</tr>



<tr><td colspan="7"><b><?= $translator->getTranslation('Placements') ?></b></td></tr>
<?php
$accounts = $accountsManager->GetAllPrivateInvestmentAccounts();

$sumGlobal += $sum;
$sum = 0;

foreach ($accounts as $account)
{
	global $sum;

	$valueToUpdate = ($account->GetInvestmentLastValueDate() != '' && strtotime($account->GetInvestmentLastValueDate()) < strtotime("-".$account->get('minimumCheckPeriod')." days"));
	$openingYear = date("Y", strtotime($account->get('creationDate')));
	$openingDateToDisplay = ($account->get('creationDate') != '' && $account->get('creationDate') != '0000-00-00');
	$availabilityYear = date("Y", strtotime($account->get('availabilityDate')));
	$availabilityDateToDisplay = ($account->get('availabilityDate') != '' && $availabilityYear > date("Y"));

?>
<tr>
<td><a href="#" onclick="javascript:ChangeContext('records','<?= $account->get('accountId')?>',''); return false;"><?= $account->get('name') ?></a></td>
<td style='text-align: right;'><?= $valueToUpdate ? '<i>' : '' ?><?= $translator->getCurrencyValuePresentation($account->GetInvestmentLastValue()) ?><?= $valueToUpdate ? '</i>' : '' ?></td>
<td><?= $account->get('description') ?></td>
<td style='text-align: right;'><?= $openingDateToDisplay ? $openingYear : '' ?></td>
<td style='text-align: right;' <?= $account->GetInvestmentLastYield() < 0 ? 'bgcolor="red"' : '' ?>><?= $translator->getPercentagePresentation($account->GetInvestmentLastYield()) ?></td>
<td style='text-align: right;' <?= $account->GetInvestmentLastYieldAverage() < 0 ? 'bgcolor="red"' : '' ?>><?= $translator->getPercentagePresentation($account->GetInvestmentLastYieldAverage()) ?></td>
<td style='text-align: right;'><?= $availabilityDateToDisplay ? $availabilityYear : '' ?></td>
</tr>
<?php
	$sum += $account->GetInvestmentLastValue();
}

$sumGlobal += $sum;
?>
<tr>
<td><i><?= $translator->getTranslation('Sous-total') ?></i></td>
<td style='text-align: right;'><i><?= $translator->getCurrencyValuePresentation($sum) ?></i></td>
</tr>
<tr>
<td><b>Total</b></td>
<td style='text-align: right;'><b><?= $translator->getCurrencyValuePresentation($sumGlobal) ?></b></td>
</tr>
</table>


</td>
<td>

<table class="summaryTable">

<tr>
<td colspan="2"><b><?= $translator->getTranslation('Gestion courante') ?></b></td>
</tr>
<?php
$accountsManager = new AccountsManager();
$sum = 0;
$sumGlobal = 0;

$accounts = $accountsManager->GetAllDuoAccounts();

foreach ($accounts as $account)
{
	$balance = $account->GetBalance();
	?>
	<tr>
	<td><a href="#" onclick="javascript:ChangeContext('records','<?= $account->get('accountId')?>',''); return false;"><?= $account->get('name') ?></a></td>
	<td style='text-align: right;'><?= $translator->getCurrencyValuePresentation($balance) ?></td>
	</tr>
	<?php
	$sum += $balance;
}

$sumGlobal = $sum;
?>
<tr>
<td><i><?= $translator->getTranslation('Sous-total') ?></i></td>
<td style='text-align: right;'><i><?= $translator->getCurrencyValuePresentation($sum) ?></i></td>
</tr>

<tr><td colspan="5"><b><?= $translator->getTranslation('Placements') ?></b></td></tr>
<?php
$accounts = $accountsManager->GetAllSharedInvestmentAccounts();

$sum = 0;

foreach ($accounts as $account)
{
	?>
	<tr>
	<td><a href="#" onclick="javascript:ChangeContext('records','<?= $account->get('accountId')?>',''); return false;"><?= $account->get('name') ?></a></td>
	<td style='text-align: right;'><?= $translator->getCurrencyValuePresentation($account->GetInvestmentLastValue()) ?></td>
	</tr>
	<?php
	$sum += $account->GetInvestmentLastValue();
}

$sumGlobal += $sum;
?>
<tr>
<td><i><?= $translator->getTranslation('Sous-total') ?></i></td>
<td style='text-align: right;'><i><?= $translator->getCurrencyValuePresentation($sum) ?></i></td>
</tr>

<tr>
<td colspan="2"><b><?= $translator->getTranslation('Emprunts') ?></b></td>
</tr>
<?php
$accountsManager = new AccountsManager();
$accounts = $accountsManager->GetAllSharedLoans();
$sum = 0;
foreach ($accounts as $account)
{
	$balance = $account->GetBalance();
	?>
	<tr>
	<td><a href="#" onclick="javascript:ChangeContext('records','<?= $account->get('accountId')?>',''); return false;"><?= $account->get('name') ?></a></td>
	<td style='text-align: right;'><?= $translator->getCurrencyValuePresentation($balance) ?></td>
	</tr>
	<?php
	$sum += $balance;
}
$sumGlobal += $sum;
?>
<tr>
<td><i><?= $translator->getTranslation('Sous-total') ?></i></td>
<td style='text-align: right;'><i><?= $translator->getCurrencyValuePresentation($sum) ?></i></td>
</tr>

<tr>
<td><b>Total</b></td>
<td style='text-align: right;'><b><?= $translator->getCurrencyValuePresentation($sumGlobal) ?></b></td>
</tr>

</table>

</td>
</tr>
</table>
