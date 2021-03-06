<?php
class Operation_Record_Income extends Operation_Record
{
	public function Validate()
	{
		$this->ValidateAmount();
		$this->ValidateToAccount();
		$this->ValidateDesignation();
		$this->ValidateRecordDate();
		$this->ValidatePeriodicity();
		$this->ValidatePeriodicityNumber();
		$this->ValidateConfirmed();
	}

	public function Save()
	{
		$toAccountId = $this->_toAccount;
		$usersHandler = new UsersHandler();
		$user = $usersHandler->GetCurrentUser();

		if (substr($toAccountId, 0, 5) == "USER/")
		{
			$toAccountId = '';
		}

		for ($currentMonth = 0; $currentMonth < $this->_periodicityNumber; $currentMonth++)
		{
			$currentDate = Date('Y-m-d', strtotime($this->_date." +".$currentMonth." month"));
			$uuid = $this->_db->GenerateUUID();

			foreach ($this->_categories as $categoryIndex=>$categoryData)
			{
				if (is_numeric($categoryData['amount']) && $categoryData['amount'] > 0)
				{
					$newRecord = new Record_Transfer_Income(
							$toAccountId,
							$user->get('userId'),
							$currentDate,
							$categoryData['amount'],
							$this->_designation,
							$categoryData['chargeLevel'],
							$categoryData['categoryId'],
							$this->_confirmed,
							$uuid
					);
					$this->_recordsHandler->Insert($newRecord);
				}
			}
		}
	}
}