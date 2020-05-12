<?php
class Country 
{
	protected $data;
	protected $year;

	public function __construct($data, $year) 
	{
		$this->data = $data;
		$this->year = $year;
	}
}
?>