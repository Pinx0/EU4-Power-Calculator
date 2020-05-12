<?php
require_once '_functions.php';
class Save 
{
	public $current_year;
	public $base_tech;
	public $base_tactics;
	public $base_width;
	public $base_morale;
	
	function __construct($date) 
	{
		$this->current_year = (int)substr($date,0,4); 
		$this->base_tech = getBaseTech($this->current_year);
		$this->base_tactics = getTactics($this->base_tech, 1);
		$this->base_width = getCombatWidth($this->base_tech);
		$this->base_morale = getBaseMorale($this->base_tech);
	}
	
}
?>