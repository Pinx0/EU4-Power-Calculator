<?php
require_once 'Country.php';
class BaseCountry extends Country
{
	public function __construct($save, $db) 
	{
		$this->save = $save;
		$this->db = $db;
		$this->initializeValues();
	}
	private function initializeValues() 
	{
		$this->at = 50;
		$this->leader_land_fire = 0;
		$this->leader_land_shock = 0;
		$this->fire_damage_received = 0;
		$this->fire_damage = 0;
		$this->shock_damage_received = 0;
		$this->shock_damage = 0;
		$this->discipline = 1;
		$this->infantry_combat_ability = 0;
		$this->cavalry_combat_ability = 0;
		$this->artillery_combat_ability = 0;
		$this->professionalism = 0;
		$this->land_morale_modifier = 0;
		$this->monthly_manpower_recovery = 0;
		$this->artillery_fire_modifier = 0;
		$this->max_manpower = 0;
		$this->force_limit = 0;
		$this->player = "";
		$this->hex = "#000000";
		$this->tech_group = 'Western';
		$this->real_mil_tech = $this->save->base_tech;
		$this->approx_mil_tech = $this->real_mil_tech;
		$this->real_tactics = $this->real_mil_tech->tactics;
		
		$this->setEffectiveValues(false, false);
	}
}

?>