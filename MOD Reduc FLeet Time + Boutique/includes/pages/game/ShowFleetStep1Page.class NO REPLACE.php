<?php

/**
 * @mods Shop and Fleet reduc Time
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 */

class ShowFleetStep1Page extends AbstractGamePage
{
	public static $requireModule = MODULE_FLEET_TABLE;

	function __construct() 
	{
		parent::__construct();
	}
	
	public function show()
	{
		global $USER, $PLANET, $pricelist, $reslist, $LNG;
		
		// Search Line 55
		$FleetData	= array(
			'fleetroom'			=> floatToString($FleetRoom),
			'gamespeed'			=> FleetFunctions::GetGameSpeedFactor(),
			'fleetspeedfactor'	=> max(0, 1 + $USER['factor']['FlyTime']),
			'planet'			=> array('galaxy' => $PLANET['galaxy'], 'system' => $PLANET['system'], 'planet' => $PLANET['planet'], 'planet_type' => $PLANET['planet_type']),
			'maxspeed'			=> FleetFunctions::GetFleetMaxSpeed($Fleet, $USER),
			'ships'				=> FleetFunctions::GetFleetShipInfo($Fleet, $USER),
			'fleetMinDuration'	=> MIN_FLEET_TIME,
		);

		// Replace
		$FleetData	= array(
			'fleetroom'			=> floatToString($FleetRoom),
			'gamespeed'			=> FleetFunctions::GetGameSpeedFactor(),
			'fleetspeedfactor'	=> max(0, 1 + $USER['factor']['FlyTime']),
			'planet'			=> array('galaxy' => $PLANET['galaxy'], 'system' => $PLANET['system'], 'planet' => $PLANET['planet'], 'planet_type' => $PLANET['planet_type']),
			'maxspeed'			=> FleetFunctions::GetFleetMaxSpeed($Fleet, $USER),
			'ships'				=> FleetFunctions::GetFleetShipInfo($Fleet, $USER),
			'fleetMinDuration'	=> MIN_FLEET_TIME,
			'fleetReducSpeed'	=> $USER["fleet_reduc_speed"], //Line Addon
		);
	}
	
}