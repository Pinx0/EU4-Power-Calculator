<?php
require_once 'Country.php';
require_once 'BaseCountry.php';
class Save 
{
	public $current_year;
	public $base_tech;
	public $base_width;
	public $base_country;
	
	function __construct($date) 
	{
		$this->current_year = (int)substr($date,0,4); 
		$this->base_tech = self::getBaseTech($this->current_year + 5);
		$this->base_width = Country::getCombatWidth($this->base_tech);
	}
	public function createBaseCountry() 
	{
		$this->base_country = new BaseCountry($this);
	}
	private static function getBaseTech($year) 
	{
		$base_tech = 4;
		if($year >= 1453)	$base_tech ++;
		if($year >= 1466)	$base_tech ++;
		if($year >= 1479)	$base_tech ++;
		if($year >= 1492)	$base_tech ++;
		if($year >= 1505)	$base_tech ++;
		if($year >= 1518)	$base_tech ++;
		if($year >= 1531)	$base_tech ++;
		if($year >= 1544)	$base_tech ++;
		if($year >= 1557)	$base_tech ++;
		if($year >= 1570)	$base_tech ++;
		if($year >= 1583)	$base_tech ++;
		if($year >= 1596)	$base_tech ++;
		if($year >= 1609)	$base_tech ++;
		if($year >= 1622)	$base_tech ++;
		if($year >= 1635)	$base_tech ++;
		if($year >= 1648)	$base_tech ++;
		if($year >= 1661)	$base_tech ++;
		if($year >= 1674)	$base_tech ++;
		if($year >= 1687)	$base_tech ++;
		if($year >= 1700)	$base_tech ++;
		if($year >= 1715)	$base_tech ++;
		if($year >= 1730)	$base_tech ++;
		if($year >= 1745)	$base_tech ++;
		if($year >= 1760)	$base_tech ++;
		if($year >= 1775)	$base_tech ++;
		if($year >= 1790)	$base_tech ++;
		if($year >= 1805)	$base_tech ++;
		if($year >= 1820)	$base_tech ++;

		return $base_tech;
	}
}
?>