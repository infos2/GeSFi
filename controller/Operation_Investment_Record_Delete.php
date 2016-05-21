<?php
class Operation_Investment_Record_Delete extends Operation_Investment_Record
{
	public function Validate()
	{
		$this->ValidateRecordId();
	}

	public function Execute()
	{
		$this->_recordsHandler->DeleteRecord($this->_recordId);
	}
}