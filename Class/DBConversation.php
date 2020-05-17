<?php
require_once 'Technology.php';
require_once 'Unit.php';
class DBConversation 
{
	private $db;
	public function __construct($host, $user, $password, $database) 
	{
		$this->db = new \mysqli($host, $user, $password, $database);
		if ($this->db->connect_errno) 
		{
			printf("Falló la conexión: %s\n", $this->db->connect_error);
			exit();
		}
	}
	public function getBaseTech($year) 
	{
		$year = (int)$year;
		$result = $this->db->query("SELECT tech_id FROM technologies where year >= $year ORDER BY year ASC LIMIT 0,1");
		if($result)
		{
			$row = $result->fetch_object();
			$tech_id = $row->tech_id;
			$result->close();
		}
		if($tech_id > 32) $tech_id = 32;
		if($tech_id < 0) $tech_id = 0;
		return $tech_id;
	}
	public function getTech($tech_id) 
	{
		$tech_id = (int)$tech_id;
		$result = $this->db->query("SELECT tech_id, tactics, combat_width, morale, infantry_fire, infantry_shock, cavalry_fire, cavalry_shock, artillery_fire, artillery_shock FROM technologies where tech_id = $tech_id");
		$tech = new Technology();
		if($result)
		{
			$row = $result->fetch_object();
			$tech->tech_level = $row->tech_id;
			$tech->infantry_fire = $row->infantry_fire;
			$tech->infantry_shock = $row->infantry_shock;
			$tech->cavalry_fire = $row->cavalry_fire;
			$tech->cavalry_shock = $row->cavalry_shock;
			$tech->artillery_shock = $row->artillery_shock;
			$tech->artillery_fire = $row->artillery_fire;
			$tech->tactics = $row->tactics;
			$tech->combat_width = $row->combat_width;
			$tech->morale = $row->morale;
			$result->close();
		}
		return $tech;
	}
	public function getUnit($tech_id, $tech_group, $unit_type) 
	{
		$tech_id = (int)$tech_id;
		$tech_group = addslashes($tech_group);
		$unit_type = addslashes($unit_type);
		$result = $this->db->query("SELECT tech_id, tech_group, unit_type, priority, name, fire_off, fire_def, shock_off, shock_def, morale_off, morale_def FROM units where tech_id <= $tech_id AND tech_group = '$tech_group' AND unit_type = '$unit_type' ORDER BY tech_id DESC, priority ASC LIMIT 0,1");
		$unit = new Unit();
		if($result && $result->num_rows == 1)
		{
			$row = $result->fetch_object();
			$unit->tech_level = $row->tech_id;
			$unit->tech_group = $row->tech_group;
			$unit->unit_type = $row->unit_type;
			$unit->priority = $row->priority;
			$unit->name = $row->name;
			$unit->fire_off = $row->fire_off;
			$unit->fire_def = $row->fire_def;
			$unit->shock_off = $row->shock_off;
			$unit->shock_def = $row->shock_def;
			$unit->morale_off = $row->morale_off;
			$unit->morale_def = $row->morale_def;
			$result->close();
		}
		return $unit;
	}
	
	/*public function getMultipleRows($chat_id, $message) 
	{
		$result = $this->db->query("");
		$response = "";
		if($result)
		{
			while ($row = $result->fetch_object())
			{
				$response = new Response($row->my_words, $row->message_type); 
			}
			$result->close();
			$this->db->next_result();
		}
		return $response;
	} */
}
?>