<?php

/**
 * @mods Shop and Fleet reduc Time
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 */

class FleetFunctions 
{
	// Search Line 101
	public static function GetMissionDuration($SpeedFactor, $MaxFleetSpeed, $Distance, $GameSpeed, $USER)
	{
		$SpeedFactor	= (3500 / ($SpeedFactor * 0.1));
		$SpeedFactor	*= pow($Distance * 10 / $MaxFleetSpeed, 0.5);
		$SpeedFactor	+= 10;
		$SpeedFactor	/= $GameSpeed;
		
		if(isset($USER['factor']['FlyTime']))
		{
			$SpeedFactor	*= max(0, 1 + $USER['factor']['FlyTime']);
		}
		
		return max($SpeedFactor, MIN_FLEET_TIME);
	}

	// Repace
	public static function GetMissionDuration($SpeedFactor, $MaxFleetSpeed, $Distance, $GameSpeed, $USER)
	{
		$SpeedFactor	= (3500 / ($SpeedFactor * 0.1));
		$SpeedFactor	*= pow($Distance * 10 / $MaxFleetSpeed, 0.5);
		$SpeedFactor	+= 10;
		$SpeedFactor	/= (1 + ($USER["fleet_reduc_speed"] / 100)); //Addon line
		$SpeedFactor	/= $GameSpeed;
		
		if(isset($USER['factor']['FlyTime']))
		{
			$SpeedFactor	*= max(0, 1 + $USER['factor']['FlyTime']);
		}
		
		return max($SpeedFactor, MIN_FLEET_TIME);
	}
}
