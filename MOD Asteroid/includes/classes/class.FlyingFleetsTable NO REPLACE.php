<?php

/**
 * @mods Asteroids
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 */

class FlyingFleetsTable
{
	
	public function getEventData($fleetRow, $Status)
	{
		global $LNG;
		
		$Owner			= $fleetRow['fleet_owner'] == $this->userId;
		$FleetStyle  = array (
			1 => 'attack',
			2 => 'federation',
			3 => 'transport',
			4 => 'deploy',
			5 => 'hold',
			6 => 'espionage',
			7 => 'colony',
			8 => 'harvest',
			9 => 'destroy',
			10 => 'missile',
			11 => 'transport',
			15 => 'transport',
			16 => 'asteroid', // ADDON
		);

		return array($Rest, $EventString, $Time);
	}
}
