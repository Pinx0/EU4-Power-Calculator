<?php
define('INFANTRY', 0);
define('CAVALRY', 1);
define('ARTILLERY', 2);

define('FIRE', 0);
define('SHOCK', 1);

class Country 
{
	public $data;
	public $save;
	public $off_quality;
	public $def_quality;
	public $quality;
	public $quantity;
	public $military_potential;
	public $economic_power;
	public $military_sustainable;
	public $free_economic_power;
	public $overall_strength;
	public $tag;
	public $name;
	public $player;
	public $phase_modifier;
	public $combat_ability_factor;
	public $tech_factor;
	public $discipline_factor;
	public $pips_factor;
	public $generals_factor;
	public $morale_factor;
	public $real_mil_tech;
	public $approx_mil_tech;
	public $effective_mil_tech;
	public $hex;
	public $at;
	public $leader_land_fire;
	public $leader_land_shock;
	public $fire_damage_received;
	public $fire_damage;
	public $shock_damage_received;
	public $shock_damage;
	public $discipline;
	public $infantry_combat_ability;
	public $cavalry_combat_ability;
	public $artillery_combat_ability;
	public $professionalism;
	public $land_morale_modifier;
	public $monthly_manpower_recovery;
	public $artillery_fire_modifier;
	public $max_manpower;
	public $force_limit;
	public $tech_group;
	public $monthly_income;
	public $effective_morale;
	public $effective_tactics;
	public $real_tactics;
	public $infantry_fire_total_modifier;
	public $infantry_shock_total_modifier;
	public $cavalry_fire_total_modifier;
	public $cavalry_shock_total_modifier;
	public $artillery_fire_total_modifier;
	public $artillery_shock_total_modifier;
	public $general_average_fire;
	public $general_average_shock;

	public function __construct($data, $save) 
	{
		$this->data = $data;
		$this->save = $save;
		$this->initializeValues();
	}
	private function initializeValues() 
	{
		$this->at = 0;
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
		
		if(isset($this->data['quality']['at'])) $this->at = (float)$this->data['quality']['at'];
		if(isset($this->data['leader_land_fire'])) $this->leader_land_fire = $this->data['leader_land_fire'];
		if(isset($this->data['leader_land_shock'])) $this->leader_land_shock = $this->data['leader_land_shock'];
		if(isset($this->data['quality']['fire_damage_received'])) $this->fire_damage_received = $this->data['quality']['fire_damage_received'];
		if(isset($this->data['quality']['fire_damage'])) $this->fire_damage = $this->data['quality']['fire_damage'];
		if(isset($this->data['quality']['shock_damage_received'])) $this->shock_damage_received = $this->data['quality']['shock_damage_received'];
		if(isset($this->data['quality']['shock_damage'])) $this->shock_damage = $this->data['quality']['shock_damage'];
		if(isset($this->data['quality']['discipline'])) $this->discipline = 1 + $this->data['quality']['discipline'];
		if(isset($this->data['quality']['infantry_power'])) $this->infantry_combat_ability = $this->data['quality']['infantry_power'];
		if(isset($this->data['quality']['cavalry_power'])) $this->cavalry_combat_ability = $this->data['quality']['cavalry_power'];
		if(isset($this->data['quality']['artillery_power'])) $this->artillery_combat_ability = $this->data['quality']['artillery_power'];
		if(isset($this->data['army_professionalism'])) $this->professionalism = $this->data['army_professionalism']; 
		if(isset($this->data['quality']['land_morale'])) $this->land_morale_modifier = $this->data['quality']['land_morale']; 
		if(isset($this->data['manpower_recovery'])) $this->monthly_manpower_recovery = $this->data['manpower_recovery'];
		if(isset($this->data['quality']['artillery_fire'])) $this->artillery_fire_modifier = $this->data['quality']['artillery_fire'];
		if(isset($this->data['max_manpower'])) $this->max_manpower = $this->data['max_manpower'];
		if(isset($this->data['FL'])) $this->force_limit = (float)$this->data['FL'];
		if(isset($this->data['player'])) $this->player = $this->data['player'];
		if(isset($this->data['hex'])) $this->hex = $this->data['hex'];
		
		$this->tag = $this->data['tag'];
		$this->name = $this->data['countryName'];
		$this->tech_group = $this->data['technology_group'];
		$this->real_mil_tech = (int)$this->data['technology']['mil'];
		$this->approx_mil_tech = $this->real_mil_tech;
		if($this->real_mil_tech + 1 <= $this->save->base_tech) { $this->approx_mil_tech = $this->real_mil_tech + 1; }
		$this->monthly_income = $this->data['monthly_income'];
		$this->real_tactics = self::getTactics($this->real_mil_tech, $this->discipline);
	}
	protected function setEffectiveValues($approximate_tech, $use_at)
	{
		if($approximate_tech) 
		{
			$this->effective_mil_tech = $this->approx_mil_tech;
		}
		else 
		{
			$this->effective_mil_tech = $this->real_mil_tech;
		}
		if($use_at) 
		{
			$this->effective_at = $this->at;
		}
		else 
		{
			$this->effective_at = 50;
		}
		$this->effective_morale = self::getBaseMorale($this->effective_mil_tech) * (1 + $this->land_morale_modifier);
		$this->effective_tactics = self::getTactics($this->effective_mil_tech, $this->discipline);
		
		$this->infantry_fire_total_modifier = self::getModifier($this->effective_mil_tech, INFANTRY, FIRE);
		$this->infantry_shock_total_modifier = self::getModifier($this->effective_mil_tech, INFANTRY, SHOCK);
		$this->cavalry_fire_total_modifier = self::getModifier($this->effective_mil_tech, CAVALRY, FIRE);
		$this->cavalry_shock_total_modifier = self::getModifier($this->effective_mil_tech, CAVALRY, SHOCK);
		$this->artillery_fire_total_modifier = self::getModifier($this->effective_mil_tech, ARTILLERY, FIRE) + $this->artillery_fire_modifier;
		$this->artillery_shock_total_modifier = self::getModifier($this->effective_mil_tech, ARTILLERY, SHOCK);
		
		$this->general_average_fire = min(self::getAverageGeneralStat($this->effective_at,FIRE) + $this->leader_land_fire,6);
		$this->general_average_shock = min(self::getAverageGeneralStat($this->effective_at,SHOCK) + $this->leader_land_shock,6);
		
	}
	public function calculate($use_morale, $approximate_tech, $use_at)
	{
		$this->setEffectiveValues($approximate_tech, $use_at);
		
		if($use_morale) 
		{
			$this->morale_factor = $this->effective_morale / $this->save->base_country->effective_morale; 
		}
		else
		{
			$this->morale_factor = 1; 
		}
		$this->off_quality = self::getAverageCasualties($this,$this->save->base_country);
		$this->def_quality = self::getAverageCasualties($this->save->base_country,$this);
		$this->quality = $this->off_quality/$this->def_quality * $this->morale_factor;
		
		$professionalism_ticks_available = $this->professionalism/5.0;
		$yearly_manpower_recovery = $this->monthly_manpower_recovery * 12;
		$this->quantity = ($this->force_limit*1000.0 + $this->max_manpower * 0.6 +  $professionalism_ticks_available * 2 * $yearly_manpower_recovery * 0.3)/1000.0;
		
		$this->military_potential = $this->quality * $this->quantity;
		$this->economic_power = $this->monthly_income;
		$this->military_sustainable = 0; //cuanto militar se puede pagar
		$this->free_economic_power = 0; //cuanto dinero le sobra después de pagarlo
		
		$this->overall_strength = $this->economic_power/1000.0 * $this->military_potential; //todo: cambiar formula
	}
	private static function getAverageCasualties($attacker, $defender)
	{
		$army_cavalry = self::getCavalry($attacker->cavalry_combat_ability);
		$army_infantry = $attacker->save->base_width - $army_cavalry;
		$army_artillery = self::getArtillery($attacker->effective_mil_tech);
		
		$fire_dice_advantage = max(0,$attacker->general_average_fire - $defender->general_average_fire);
		$shock_dice_advantage = max(0,$attacker->general_average_shock - $defender->general_average_shock);
		
		$average_dice_roll = 4.5;
		
		$pips_factor_fire_infantry = self::getUnitPips($attacker->effective_mil_tech, $attacker->tech_group, INFANTRY, FIRE) - self::getUnitPips($defender->effective_mil_tech, $defender->tech_group, INFANTRY, FIRE);
		$pips_factor_shock_infantry = self::getUnitPips($attacker->effective_mil_tech, $attacker->tech_group, INFANTRY, FIRE) - self::getUnitPips($defender->effective_mil_tech, $defender->tech_group, INFANTRY, SHOCK);
		$pips_factor_fire_cavalry = self::getUnitPips($attacker->effective_mil_tech, $attacker->tech_group, CAVALRY, FIRE) - self::getUnitPips($defender->effective_mil_tech, $defender->tech_group, CAVALRY, FIRE);
		$pips_factor_shock_cavalry = self::getUnitPips($attacker->effective_mil_tech, $attacker->tech_group, CAVALRY, FIRE) - self::getUnitPips($defender->effective_mil_tech, $defender->tech_group, CAVALRY, SHOCK);
		$pips_factor_fire_artillery = self::getUnitPips($attacker->effective_mil_tech, $attacker->tech_group, ARTILLERY, FIRE) - self::getUnitPips($defender->effective_mil_tech, $defender->tech_group, INFANTRY, FIRE);
		$pips_factor_shock_artillery = self::getUnitPips($attacker->effective_mil_tech, $attacker->tech_group, ARTILLERY, SHOCK) - self::getUnitPips($defender->effective_mil_tech, $defender->tech_group, INFANTRY, SHOCK);
		
		$base_casualites_fire_phase_infantry = max(15 + 5*($average_dice_roll + $fire_dice_advantage + $pips_factor_fire_infantry),0);
		$base_casualites_shock_phase_infantry = max(15 + 5*($average_dice_roll + $shock_dice_advantage + $pips_factor_shock_infantry),0);
		$base_casualites_fire_phase_cavalry = max(15 + 5*($average_dice_roll + $fire_dice_advantage + $pips_factor_fire_cavalry),0);
		$base_casualites_shock_phase_cavalry = max(15 + 5*($average_dice_roll + $shock_dice_advantage + $pips_factor_shock_cavalry),0);
		$base_casualites_fire_phase_artillery = max(15 + 5*($average_dice_roll + $fire_dice_advantage + $pips_factor_fire_artillery),0);
		$base_casualites_shock_phase_artillery = max(15 + 5*($average_dice_roll + $shock_dice_advantage + $pips_factor_shock_artillery),0);
		
		$total_casualties_fire_phase_infantry = $base_casualites_fire_phase_infantry * $attacker->infantry_fire_total_modifier * (1 + $attacker->fire_damage) * (1 - $defender->fire_damage_received) * ($attacker->discipline / $defender->effective_tactics) * (1 + $attacker->infantry_combat_ability) ;
		$total_casualties_shock_phase_infantry = $base_casualites_shock_phase_infantry * $attacker->infantry_shock_total_modifier* (1 + $attacker->shock_damage) * (1 - $defender->shock_damage_received) * ($attacker->discipline / $defender->effective_tactics) * (1 + $attacker->infantry_combat_ability) ;
		$total_casualties_fire_phase_cavalry = $base_casualites_fire_phase_cavalry * $attacker->cavalry_fire_total_modifier * (1 + $attacker->fire_damage) * (1 - $defender->fire_damage_received) * ($attacker->discipline / $defender->effective_tactics) * (1 + $attacker->cavalry_combat_ability) ;
		$total_casualties_shock_phase_cavalry = $base_casualites_shock_phase_cavalry * $attacker->cavalry_shock_total_modifier * (1 + $attacker->shock_damage) * (1 - $defender->shock_damage_received) * ($attacker->discipline / $defender->effective_tactics) * (1 + $attacker->cavalry_combat_ability) ;
		$total_casualties_fire_phase_artillery = $base_casualites_fire_phase_artillery * $attacker->artillery_fire_total_modifier * (1 + $attacker->fire_damage) * (1 - $defender->fire_damage_received) * ($attacker->discipline / $defender->effective_tactics) * (1 + $attacker->artillery_combat_ability) * 0.5; //50% de daño desde segunda fila
		$total_casualties_shock_phase_artillery = $base_casualites_shock_phase_artillery * $attacker->artillery_shock_total_modifier * (1 + $attacker->shock_damage) * (1 - $defender->shock_damage_received) * ($attacker->discipline / $defender->effective_tactics) * (1 + $attacker->artillery_combat_ability) * 0.5; //50% de daño desde segunda fila
			
		$total_casualties_fire_phase = ($total_casualties_fire_phase_infantry * $army_infantry + $total_casualties_fire_phase_cavalry * $army_cavalry + $total_casualties_fire_phase_artillery * $army_artillery);
		$total_casualties_shock_phase = ($total_casualties_shock_phase_infantry * $army_infantry + $total_casualties_shock_phase_cavalry * $army_cavalry + $total_casualties_shock_phase_artillery * $army_artillery);
		
		$total_casualties = $total_casualties_fire_phase*0.52 + $total_casualties_shock_phase*0.48; //se le da esa mayor ponderacion porque la fase de fuego es la primera en cada batalla
		
		return $total_casualties;
	}
	protected static function getTactics($mil_tech, $discipline) 
	{
		$base_tactics = 0.5;
		if($mil_tech >= 4)	$base_tactics += 0.25;
		if($mil_tech >= 6)	$base_tactics += 0.25;
		if($mil_tech >= 7)	$base_tactics += 0.25;
		if($mil_tech >= 9)	$base_tactics += 0.25;
		if($mil_tech >= 12)	$base_tactics += 0.25;
		if($mil_tech >= 15)	$base_tactics += 0.25;
		if($mil_tech >= 19)	$base_tactics += 0.25;
		if($mil_tech >= 21)	$base_tactics += 0.25;
		if($mil_tech >= 23)	$base_tactics += 0.25;
		if($mil_tech >= 24)	$base_tactics += 0.25;
		if($mil_tech >= 30)	$base_tactics += 0.25;
		if($mil_tech >= 32)	$base_tactics += 0.25;
		return $base_tactics * $discipline;
	}
	public static function getCombatWidth($mil_tech) 
	{
		$width = 15;
		if($mil_tech >= 2)	$width += 5;
		if($mil_tech >= 5)	$width += 2;
		if($mil_tech >= 6)	$width += 2;
		if($mil_tech >= 9)	$width += 1;
		if($mil_tech >= 11)	$width += 2;
		if($mil_tech >= 14)	$width += 2;
		if($mil_tech >= 16)	$width += 1;
		if($mil_tech >= 18)	$width += 2;
		if($mil_tech >= 20)	$width += 2;
		if($mil_tech >= 22)	$width += 2;
		if($mil_tech >= 24)	$width += 2;
		if($mil_tech >= 26)	$width += 2;

		return $width ;
	}
	private static function getAverageGeneralStat($at,$type)
	{
		$score = 3;
		$score += $at/20.0;
		$score += $at/100.0;
		if($at>=20) $score += ($at-20)/100.0;
		if($at>=40) $score += ($at-40)/100.0;
		if($at>=60) $score += ($at-60)/100.0;
		if($at>=80) $score += ($at-80)/100.0;
		return $score*0.3;
		
	}
	protected static function getArtillery($mil_tech)
	{
		if($mil_tech == 7)  return 3;
		if($mil_tech == 8)  return 5;
		if($mil_tech == 9)  return 6;
		if($mil_tech == 10)  return 8;
		if($mil_tech == 11)  return 12;
		if($mil_tech == 12)  return 14;
		if($mil_tech == 13)  return 17;
		if($mil_tech == 14)  return 20;
		if($mil_tech == 15)  return 25;
		if($mil_tech >= 16) return self::getCombatWidth($mil_tech);
		return 0;
	}
	protected static function getCavalry($cavalry_combat_ability)
	{
		if($cavalry_combat_ability>=1.4) return 14; //todo: hay que capear esto según el ancho de combate y el % de cav permitido
		if($cavalry_combat_ability>=1.3) return 10;
		if($cavalry_combat_ability>=1.2) return 6;
		return 4;
	}
	protected static function getModifier($mil_tech, $unit_type, $phase) 
	{
		switch($phase)
		{
			case FIRE:
				switch($unit_type)
				{
					case INFANTRY:
						return self::getInfantryFire($mil_tech);
					case CAVALRY:
						return self::getCavalryFire($mil_tech);
					case ARTILLERY:
						return self::getArtilleryFire($mil_tech);
				}
				break;
			case SHOCK:
				switch($unit_type)
				{
					case INFANTRY:
						return self::getInfantryShock($mil_tech);
					case CAVALRY:
						return self::getCavalryShock($mil_tech);
					case ARTILLERY:
						return self::getArtilleryShock($mil_tech);
				}
				break;
		}
		return 0;
	}
	protected static function getUnitPips($mil_tech, $tech_group, $unit_type, $phase) 
	{
		return 0;
	}
	protected static function getInfantryFire($mil_tech) 
	{
		$base = 0.25;
		if($mil_tech >= 1) $base += 0.1;
		if($mil_tech >= 6) $base += 0.2;
		if($mil_tech >= 8) $base += 0.25;
		if($mil_tech >= 14) $base += 0.3;
		if($mil_tech >= 20) $base += 0.5;
		if($mil_tech >= 27) $base += 0.5;
		if($mil_tech >= 31) $base += 1;
		return $base;
	}
	protected static function getCavalryFire($mil_tech) 
	{
		$base = 0;
		if($mil_tech >= 11) $base += 0.5;
		if($mil_tech >= 22) $base += 0.5;
		return $base;
	}
	protected static function getArtilleryFire($mil_tech) 
	{
		$base = 0;
		if($mil_tech >= 7) $base += 1;
		if($mil_tech >= 13) $base += 0.4;
		if($mil_tech >= 16) $base += 1;
		if($mil_tech >= 22) $base += 2;
		if($mil_tech >= 25) $base += 2;
		if($mil_tech >= 32) $base += 2;
		return $base;
	}
	protected static function getInfantryShock($mil_tech) 
	{
		$base = 0.2;
		if($mil_tech >= 1) $base += 0.1;
		if($mil_tech >= 2) $base += 0.2;
		if($mil_tech >= 5) $base += 0.15;
		if($mil_tech >= 6) $base += 0.3;
		if($mil_tech >= 11) $base += 0.2;
		if($mil_tech >= 21) $base += 0.5;
		if($mil_tech >= 28) $base += 0.5;
		return $base;
	}
	protected static function getCavalryShock($mil_tech) 
	{
		$base = 0.8;
		if($mil_tech >= 2) $base += 0.2;
		if($mil_tech >= 5) $base += 0.2;
		if($mil_tech >= 8) $base += 0.8;
		if($mil_tech >= 17) $base += 1;
		if($mil_tech >= 23) $base += 1;
		if($mil_tech >= 31) $base += 1;
		return $base;
	}
	protected static function getArtilleryShock($mil_tech) 
	{
		$base = 0;
		if($mil_tech >= 7) $base += 0.05;
		if($mil_tech >= 13) $base += 0.1;
		if($mil_tech >= 16) $base += 0.1;
		if($mil_tech >= 22) $base += 0.1;
		if($mil_tech >= 25) $base += 0.1;
		if($mil_tech >= 32) $base += 0.1;

		return $base;
	}
	protected static function getBaseMorale($mil_tech) 
	{
		$base = 2;
		if($mil_tech >= 3) $base += 0.5;
		if($mil_tech >= 4) $base += 0.5;
		if($mil_tech >= 15) $base += 1;
		if($mil_tech >= 26) $base += 1;
		if($mil_tech >= 30) $base += 1;
		return $base;
	}
}
?>