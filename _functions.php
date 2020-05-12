<?php
function getTactics($mil_tech, $discipline) 
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
function getCombatWidth($mil_tech) 
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

function getBaseTech($year) 
{
    $base_tech = 3;
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
function getArtillery($mil_tech)
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
	if($mil_tech >= 16) return getCombatWidth($mil_tech);
	return 0;
}
function getCavalry($cavalry_combat_ability)
{
	if($cavalry_combat_ability>=1.2) return 6;
	return 4;
}
function getModifier($mil_tech, $unit_type, $phase) 
{
	switch($phase)
	{
		case FIRE:
			switch($unit_type)
			{
				case INFANTRY:
					return getInfantryFire($mil_tech);
				case CAVALRY:
					return getCavalryFire($mil_tech);
				case ARTILLERY:
					return getArtilleryFire($mil_tech);
			}
			break;
		case SHOCK:
			switch($unit_type)
			{
				case INFANTRY:
					return getInfantryShock($mil_tech);
				case CAVALRY:
					return getCavalryShock($mil_tech);
				case ARTILLERY:
					return getArtilleryShock($mil_tech);
			}
			break;
	}
	return 0;
}
function getUnitPips($mil_tech, $tech_group, $unit_type, $phase) 
{
	return 0;
}
function getInfantryFire($mil_tech) 
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
function getCavalryFire($mil_tech) 
{
	$base = 0;
	if($mil_tech >= 11) $base += 0.5;
	if($mil_tech >= 22) $base += 0.5;
	return $base;
}
function getArtilleryFire($mil_tech) 
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
function getInfantryShock($mil_tech) 
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
function getCavalryShock($mil_tech) 
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
function getArtilleryShock($mil_tech) 
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
function getBaseMorale($mil_tech) 
{
	$base = 2;
	if($mil_tech >= 3) $base += 0.5;
	if($mil_tech >= 4) $base += 0.5;
	if($mil_tech >= 15) $base += 1;
	if($mil_tech >= 26) $base += 1;
	if($mil_tech >= 30) $base += 1;
	return $base;
}
?>