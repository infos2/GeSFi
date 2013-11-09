<?php
include_once '../security/security_manager.php';

function __autoload($class_name)
{
	include '../class/'.$class_name . '.php';
}

$accountsManager = new AccountsManager();

if ($_SESSION['account_id'] != 'asset_management')
	echo '<a href="#" onclick="javascript:ChangeAccount(\'asset_management\'); return false;">';
echo 'Gestion patrimoniale';
if ($_SESSION['account_id'] != 'asset_management')
	echo '</a>';
echo ' / ';

$accounts = $accountsManager->GetAllInvestmentAccounts();

foreach ($accounts as $account)
{
	if ($account->getAccountId() != $_SESSION['account_id'])
		echo '<a href="#" onclick="javascript:ChangeAccount(\''.$account->getAccountId().'\'); return false;">';
	echo $account->getName();
	if ($account->getAccountId() != $_SESSION['account_id'])
		echo '</a>';
	echo ' / ';
}

?>