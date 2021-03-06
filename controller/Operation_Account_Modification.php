<?php
class Operation_Account_Modification extends Operation_Account
{
	protected $_accountId;
	protected $_name;
	protected $_description;
	protected $_information;
	protected $_type;
	protected $_owner;
	protected $_openingBalance;
	protected $_expectedMinimumBalance;
	protected $_creationDate;
	protected $_availabilityDate;
	protected $_closingDate;
	protected $_minimumCheckPeriod;
	protected $_recordConfirmation;
	protected $_notDisplayedInMenu;
	protected $_noColorInDashboard;
	protected $_generateIncome;

	protected $_delete;

	protected $_sortOrder;

	public function Validate()
	{
		if (!empty($this->_recordConfirmation))
			$this->_recordConfirmation = "1";
		else
			$this->_recordConfirmation = "0";

		if (!empty($this->_notDisplayedInMenu))
			$this->_notDisplayedInMenu = "1";
		else
			$this->_notDisplayedInMenu = "0";

		if (!empty($this->_noColorInDashboard))
			$this->_noColorInDashboard = "1";
		else
			$this->_noColorInDashboard = "0";

		if (!empty($this->_generateIncome))
			$this->_generateIncome = "1";
		else
			$this->_generateIncome = "0";
	}

	public function Save()
	{
		$handler = new AccountsHandler();

		if ($this->_accountId == '')
		{
			$handler->InsertAccount(
					$this->_name,
					$this->_owner,
					$this->_type,
					$this->_openingBalance,
					$this->_expectedMinimumBalance,
					$this->_sortOrder,
					$this->_minimumCheckPeriod,
					$this->_recordConfirmation,
					$this->_notDisplayedInMenu,
					$this->_noColorInDashboard,
					$this->_generateIncome);
		}
		else
		{
			$account = $handler->GetAccount($this->_accountId);
			if ($account->get('ownerUserId') == $this->_userId) // Current user is account first owner
			{
				if ($this->_delete == 'on')
					$handler->DeleteAccount($this->_accountId);
				else
					$handler->UpdateAccount(
							$this->_accountId,
							$this->_name,
							$this->_description,
							$this->_openingBalance,
							$this->_expectedMinimumBalance,
							$this->_sortOrder,
							$this->_minimumCheckPeriod,
							$this->_creationDate,
							$this->_availabilityDate,
							$this->_recordConfirmation,
							$this->_notDisplayedInMenu,
							$this->_noColorInDashboard,
							$this->_generateIncome);
			}
			else
			{
				if ($this->_delete == 'on')
					throw new Exception("Vous n'êtes pas le titulaire principal de ce compte, vous ne pouvez pas le supprimer.");
				else
					$handler->UpdateAccountSortOrder($this->_accountId, $this->_sortOrder);
			}
		}
	}
}