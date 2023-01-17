<?php

/**
 * @mods Asteroids
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 */

require_once 'includes/pages/game/ShowPhalanxPage.class.php';

class GalaxyRows
{	
	protected function getAllowedMissions()
	{
		global $PLANET, $resource;
		
		$this->galaxyData[$this->galaxyRow['planet']]['missions']	= array(
			1	=> !$this->galaxyData[$this->galaxyRow['planet']]['ownPlanet'] && isModuleAvailable(MODULE_MISSION_ATTACK),
			3	=> isModuleAvailable(MODULE_MISSION_TRANSPORT),
			4	=> $this->galaxyData[$this->galaxyRow['planet']]['ownPlanet'] && isModuleAvailable(MODULE_MISSION_STATION),
			5	=> !$this->galaxyData[$this->galaxyRow['planet']]['ownPlanet'] && isModuleAvailable(MODULE_MISSION_HOLD),
			6	=> !$this->galaxyData[$this->galaxyRow['planet']]['ownPlanet'] && isModuleAvailable(MODULE_MISSION_SPY),
			8	=> isModuleAvailable(MODULE_MISSION_RECYCLE),
			9	=> !$this->galaxyData[$this->galaxyRow['planet']]['ownPlanet'] && $PLANET[$resource[214]] > 0 && isModuleAvailable(MODULE_MISSION_DESTROY),
			10	=> !$this->galaxyData[$this->galaxyRow['planet']]['ownPlanet'] && $PLANET[$resource[503]] > 0 && isModuleAvailable(MODULE_MISSION_ATTACK) && isModuleAvailable(MODULE_MISSILEATTACK) && $this->inMissileRange(),
			16	=> $this->galaxyRow['id_owner'] == NULL, // ADDON
		);
	}

	protected function getPlanetData()
	{
		$this->galaxyData[$this->galaxyRow['planet']]['planet']	= array(
			'id'			=> $this->galaxyRow['id'],
			'id_owner'		=> $this->galaxyRow['id_owner'], // ADDON
			'name'			=> htmlspecialchars($this->galaxyRow['name'], ENT_QUOTES, "UTF-8"),
			'image'			=> $this->galaxyRow['image'],
			'phalanx'		=> isModuleAvailable(MODULE_PHALANX) && ShowPhalanxPage::allowPhalanx($this->galaxyRow['galaxy'], $this->galaxyRow['system']),
		);
	}
}
