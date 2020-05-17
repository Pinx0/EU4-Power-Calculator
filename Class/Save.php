<?php
require_once 'Country.php';
require_once 'BaseCountry.php';
class Save 
{
	public $current_year;
	public $base_tech_level;
	public $base_tech;
	public $base_country;
	private $db;
	
	function __construct($date, $db) 
	{
		$this->db = $db; 
		$this->current_year = (int)substr($date,0,4); 
		$this->base_tech_level = $db->getBaseTech($this->current_year + 3);
		$this->base_tech = $db->getTech($this->base_tech_level);
		$this->createBaseCountry();
	}
	private function createBaseCountry() 
	{
		$this->base_country = new BaseCountry($this, $this->db);
	}
}
?>