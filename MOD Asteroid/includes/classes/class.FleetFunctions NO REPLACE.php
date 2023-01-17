<?php

/**
 * @mods Asteroids
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 */

class FleetFunctions 
{
	public static function GetAvailableMissions($USER, $MissionInfo, $GetInfoPlanet)
	{	
		$YourPlanet				= (!empty($GetInfoPlanet['id_owner']) && $GetInfoPlanet['id_owner'] == $USER['id']) ? true : false;
		$UsedPlanet				= (!empty($GetInfoPlanet['id_owner'])) ? true : false;
		$availableMissions		= [];
		
		if ($MissionInfo['planet'] == (Config::get($USER['universe'])->max_planets + 1) && isModuleAvailable(MODULE_MISSION_EXPEDITION))
			$availableMissions[]	= 15;	
		elseif ($MissionInfo['planettype'] == 2) {
			if ((isset($MissionInfo['Ship'][209]) || isset($MissionInfo['Ship'][219])) && isModuleAvailable(MODULE_MISSION_RECYCLE) && !($GetInfoPlanet['der_metal'] == 0 && $GetInfoPlanet['der_crystal'] == 0))
				$availableMissions[]	= 8;
		} else {
			if (!$UsedPlanet) {
				if (isset($MissionInfo['Ship'][208]) && $MissionInfo['planettype'] == 1 && isModuleAvailable(MODULE_MISSION_COLONY))
					$availableMissions[]	= 7;

				// ADDON Astéroides
				if((isset($MissionInfo['Ship'][209]) || isset($MissionInfo['Ship'][219])) && $MissionInfo['planettype'] == 1)
					$availableMissions[]	= 16;

			} else {
				if(isModuleAvailable(MODULE_MISSION_TRANSPORT))
					$availableMissions[]	= 3;
					
				if (!$YourPlanet && self::OnlyShipByID($MissionInfo['Ship'], 210) && isModuleAvailable(MODULE_MISSION_SPY))
					$availableMissions[]	= 6;

				if (!$YourPlanet) {
					if(isModuleAvailable(MODULE_MISSION_ATTACK))
						$availableMissions[]	= 1;
					if(isModuleAvailable(MODULE_MISSION_HOLD))
						$availableMissions[]	= 5;
				}
						
				elseif(isModuleAvailable(MODULE_MISSION_STATION)) {
					$availableMissions[]	= 4;
				}
					
				if (!empty($MissionInfo['IsAKS']) && !$YourPlanet && isModuleAvailable(MODULE_MISSION_ATTACK) && isModuleAvailable(MODULE_MISSION_ACS))
					$availableMissions[]	= 2;

				if (!$YourPlanet && $MissionInfo['planettype'] == 3 && isset($MissionInfo['Ship'][214]) && isModuleAvailable(MODULE_MISSION_DESTROY))
					$availableMissions[]	= 9;

				if ($YourPlanet && $MissionInfo['planettype'] == 3 && self::OnlyShipByID($MissionInfo['Ship'], 220) && isModuleAvailable(MODULE_MISSION_DARKMATTER))
					$availableMissions[]	= 11;
			}
		}
		
		return $availableMissions;
	}
}