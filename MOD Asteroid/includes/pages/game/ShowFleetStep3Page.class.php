<?php

/**
 * @mods Asteroids
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 */

class ShowFleetStep3Page extends AbstractGamePage
{
	public static $requireModule = MODULE_FLEET_TABLE;

	function __construct() 
	{
		parent::__construct();
	}
	
	public function show()
	{
		
		// Search
		if ($targetMission == 7 || $targetMission == 15)
		{
			$targetPlanetData	= array('id' => 0, 'id_owner' => 0, 'planettype' => 1);
		}

		// Replace
		if ($targetMission == 7 || $targetMission == 15 || $targetMission == 16) // Addon Astéroïdes
		{
			$targetPlanetData	= array('id' => 0, 'id_owner' => 0, 'planettype' => 1);
		}

		// Search
		if($targetMission == 7 || $targetMission == 15) {
			$targetPlayerData	= array(
				'id'				=> 0,
				'onlinetime'		=> TIMESTAMP,
				'ally_id'			=> 0,
				'urlaubs_modus'		=> 0,
				'authattack'		=> 0,
				'total_points'		=> 0,
			);
		}

		//Replace
		if($targetMission == 7 || $targetMission == 15 || $targetMission == 16) { // Addon Astéroïdes
			$targetPlayerData	= array(
				'id'				=> 0,
				'onlinetime'		=> TIMESTAMP,
				'ally_id'			=> 0,
				'urlaubs_modus'		=> 0,
				'authattack'		=> 0,
				'total_points'		=> 0,
			);
		}

	}
}