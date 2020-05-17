<?php
require_once 'Technology.php';
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
	
	/*public function getTech2($chat_id, $message) 
	{
		$result = $this->db->query("CALL bot_build_phrase('$message',$chat_id)");
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