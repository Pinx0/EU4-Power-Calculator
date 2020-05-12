<?php
require_once '_functions.php';
class Country 
{
	public $data;
	public $save;
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

	public function __construct($data, $save) 
	{
		$this->data = $data;
		$this->save = $save;
	}
	public function Calculate()
	{
		$at = 0;
		$leader_land_fire = 0;
		$leader_land_shock = 0;
		$fire_damage_received = 0;
		$fire_damage = 0;
		$shock_damage_received = 0;
		$shock_damage = 0;
		$discipline = 1;
		$infantry_combat_ability = 1;
		$cavalry_combat_ability = 1;
		$artillery_combat_ability = 1;
		$professionalism = 0;
		$land_morale = 0;
		$monthly_manpower_recovery = 0;
		$artillery_fire_modifier = 0;
		$max_manpower = 0;
		$force_limit = 0;
		$this->player = "";
		
		if(isset($this->data['at'])) $at = $this->data['at'];
		if(isset($this->data['leader_land_fire'])) $leader_land_fire = $this->data['leader_land_fire'];
		if(isset($this->data['leader_land_shock'])) $leader_land_shock = $this->data['leader_land_shock'];
		if(isset($this->data['quality']['fire_damage_received'])) $fire_damage_received = $this->data['quality']['fire_damage_received'];
		if(isset($this->data['quality']['fire_damage'])) $fire_damage = $this->data['quality']['fire_damage'];
		if(isset($this->data['quality']['shock_damage_received'])) $shock_damage_received = $this->data['quality']['shock_damage_received'];
		if(isset($this->data['quality']['shock_damage'])) $shock_damage = $this->data['quality']['shock_damage'];
		if(isset($this->data['quality']['discipline'])) $discipline = 1 + $this->data['quality']['discipline'];
		if(isset($this->data['quality']['infantry_power'])) $infantry_combat_ability = 1 + $this->data['quality']['infantry_power'];
		if(isset($this->data['quality']['cavalry_power'])) $cavalry_combat_ability = 1 + $this->data['quality']['cavalry_power'];
		if(isset($this->data['quality']['artillery_power'])) $artillery_combat_ability = 1 + $this->data['quality']['artillery_power'];
		if(isset($this->data['army_professionalism'])) $professionalism = $this->data['army_professionalism']; 
		if(isset($this->data['quality']['land_morale'])) $land_morale = $this->data['quality']['land_morale']; 
		if(isset($this->data['manpower_recovery'])) $monthly_manpower_recovery = $this->data['manpower_recovery'];
		if(isset($this->data['quality']['artillery_fire'])) $artillery_fire_modifier = $this->data['quality']['artillery_fire'];
		if(isset($this->data['max_manpower'])) $max_manpower = $this->data['max_manpower'];
		if(isset($this->data['FL'])) $force_limit = (float)$this->data['FL'];
		if(isset($this->data['player'])) $this->player = $this->data['player'];
		$mil_tech = $this->data['technology']['mil'];
		$this->tag = $this->data['tag'];
		$this->name = $this->data['countryName'];
		$tech_group = $this->data['technology_group'];
		
		$monthly_income = $this->data['monthly_income'];
		$morale = getBaseMorale($mil_tech) * (1 + $land_morale);
		
		
		$tactics = getTactics($mil_tech, $discipline);
		
		$fire_defense = 1 - $fire_damage_received;
		$fire_offense = 1 + $fire_damage;
		$fire_modifier = $fire_offense/$fire_defense;
		
		$shock_defense = 1 - $shock_damage_received;
		$shock_offense = 1 + $shock_damage;
		$shock_modifier = $shock_offense/$shock_defense;
		
		$tactics_factor = $tactics / $this->save->base_tactics;
		
		$army_cavalry = getCavalry($cavalry_combat_ability);
		$army_infantry = $this->save->base_width - $army_cavalry;
		$army_artillery = getArtillery($mil_tech);
		
		$generals_factor = ($at*0.08+4)*0.3 - (50*0.08+4)*0.3; //no es exacta, es una aproximación, mejorar
		$generals_factor_fire = max(0,$generals_factor + $leader_land_fire);
		$generals_factor_shock = max(0,$generals_factor + $leader_land_shock);
		
		$pips_factor_fire_infantry = getUnitPips($mil_tech, $tech_group, INFANTRY, FIRE) - getUnitPips($this->save->base_tech, 'Western', INFANTRY, FIRE);
		$pips_factor_shock_infantry = getUnitPips($mil_tech, $tech_group, INFANTRY, FIRE) - getUnitPips($this->save->base_tech, 'Western', INFANTRY, SHOCK);
		$pips_factor_fire_cavalry = getUnitPips($mil_tech, $tech_group, CAVALRY, FIRE) - getUnitPips($this->save->base_tech, 'Western', CAVALRY, FIRE);
		$pips_factor_shock_cavalry = getUnitPips($mil_tech, $tech_group, CAVALRY, FIRE) - getUnitPips($this->save->base_tech, 'Western', CAVALRY, SHOCK);
		$pips_factor_fire_artillery = getUnitPips($mil_tech, $tech_group, ARTILLERY, FIRE) - getUnitPips($this->save->base_tech, 'Western', INFANTRY, FIRE);
		$pips_factor_shock_artillery = getUnitPips($mil_tech, $tech_group, ARTILLERY, SHOCK) - getUnitPips($this->save->base_tech, 'Western', INFANTRY, SHOCK);
		
		$infantry_fire = getModifier($mil_tech, INFANTRY, FIRE);
		$infantry_shock = getModifier($mil_tech, INFANTRY, SHOCK);
		$cavalry_fire = getModifier($mil_tech, CAVALRY, FIRE);
		$cavalry_shock = getModifier($mil_tech, CAVALRY, SHOCK);
		$artillery_fire = getModifier($mil_tech, ARTILLERY, FIRE) + $artillery_fire_modifier;
		$artillery_shock = getModifier($mil_tech, ARTILLERY, SHOCK);
		
		$d_result_fire_base = 4.5 + $generals_factor_fire;
		$d_result_shock_base = 4.5 + $generals_factor_shock;
		
		$base_casualites_fire_phase_infantry = max(15 + 5*($d_result_fire_base + $pips_factor_fire_infantry),0);
		$base_casualites_shock_phase_infantry = max(15 + 5*($d_result_shock_base + $pips_factor_shock_infantry),0);
		$base_casualites_fire_phase_cavalry = max(15 + 5*($d_result_fire_base + $pips_factor_fire_cavalry),0);
		$base_casualites_shock_phase_cavalry = max(15 + 5*($d_result_shock_base + $pips_factor_shock_cavalry),0);
		$base_casualites_fire_phase_artillery = max(15 + 5*($d_result_fire_base + $pips_factor_fire_artillery),0);
		$base_casualites_shock_phase_artillery = max(15 + 5*($d_result_shock_base + $pips_factor_shock_artillery),0);
		

		$total_casualties_fire_phase_infantry = $base_casualites_fire_phase_infantry  * $fire_modifier  * $discipline * $tactics_factor * $infantry_combat_ability * $infantry_fire;
		$total_casualties_shock_phase_infantry = $base_casualites_shock_phase_infantry  * $shock_modifier  * $discipline * $tactics_factor * $infantry_combat_ability * $infantry_shock;
		$total_casualties_fire_phase_cavalry = $base_casualites_fire_phase_cavalry  * $fire_modifier  * $discipline * $tactics_factor * $cavalry_combat_ability * $cavalry_fire;
		$total_casualties_shock_phase_cavalry = $base_casualites_shock_phase_cavalry  * $shock_modifier  * $discipline * $tactics_factor * $cavalry_combat_ability * $cavalry_shock;
		$total_casualties_fire_phase_artillery = $base_casualites_fire_phase_artillery  * $fire_modifier  * $discipline * $tactics_factor * $artillery_combat_ability * $artillery_fire * 0.5; //segunda fila
		$total_casualties_shock_phase_artillery = $base_casualites_shock_phase_artillery  * $shock_modifier  * $discipline * $tactics_factor * $artillery_combat_ability * $artillery_shock * 0.5; //segunda fila
			
		$total_casualties_fire_phase = ($total_casualties_fire_phase_infantry * $army_infantry + $total_casualties_fire_phase_cavalry * $army_cavalry + $total_casualties_fire_phase_artillery * $army_artillery) / $this->save->base_width;
		$total_casualties_shock_phase = ($total_casualties_shock_phase_infantry * $army_infantry + $total_casualties_shock_phase_cavalry * $army_cavalry + $total_casualties_shock_phase_artillery * $army_artillery) / $this->save->base_width;
		
		$total_casualties = $total_casualties_fire_phase*0.52 + $total_casualties_shock_phase*0.48; //se le da esa mayor ponderacion porque la fase de fuego es la primera
		
		
		$morale_factor = $morale / $this->save->base_morale;
		
		$this->quality = $total_casualties * $morale_factor;
		
		$this->phase_modifier = $fire_modifier*0.52 + $shock_modifier*0.48;
		$this->combat_ability_factor = ($infantry_combat_ability* $army_infantry + $cavalry_combat_ability*$army_cavalry +  $artillery_combat_ability*$army_artillery) / $this->save->base_width;
		$this->tech_factor = (($infantry_fire*0.52+$infantry_shock*0.48)*$army_infantry + ($cavalry_fire*0.52+$cavalry_shock*0.48)*$army_cavalry + ($artillery_fire*0.52+$artillery_shock*0.48)* $army_artillery * 0.5) / $this->save->base_width;
		$this->discipline_factor = $discipline * $tactics_factor ;
		$this->pips_factor = (($pips_factor_fire_infantry*0.52+$pips_factor_shock_infantry*0.48)*$army_infantry + ($pips_factor_fire_cavalry*0.52+$pips_factor_shock_cavalry*0.48)*$army_cavalry + ($pips_factor_fire_artillery*0.52+$pips_factor_shock_artillery*0.48)* $army_artillery) / $this->save->base_width;
		$this->generals_factor = $generals_factor_fire*0.52 + $generals_factor_shock*0.48;
		
		$professionalism_ticks_available = $professionalism/5.0;
		
		$yearly_manpower_recovery = $monthly_manpower_recovery * 12;
		
		$this->quantity = ($force_limit*1000.0 + $max_manpower * 0.6 +  $professionalism_ticks_available * 2 * $yearly_manpower_recovery * 0.3)/1000.0;
		
		$this->military_potential = $this->quality * $this->quantity;
		$this->economic_power = $monthly_income;
		$this->military_sustainable = 0; //cuanto militar se puede pagar
		$this->free_economic_power = 0; //cuanto dinero le sobra después de pagarlo
		
		$this->overall_strength = $this->economic_power/1000.0 * $this->military_potential; //todo: cambiar formula
	}
}
?>