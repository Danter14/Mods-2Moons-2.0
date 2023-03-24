<?php

/**
 * @mods Asteroids
 * @package 2Moons
 * @author Danter14
 * @licence MIT
 * @version 1.8 - 1.9 - 2.0
 */

class MissionCaseAsteroid extends MissionFunctions implements Mission
{
	function __construct($Fleet)
	{
		$this->_fleet	= $Fleet;
	}
	
	function TargetEvent()
	{	
		global $USER, $pricelist, $resource;
		
		$sql	= "SELECT id_owner, metal, crystal, deuterium FROM %%PLANETS%% WHERE galaxy = :fleet_end_galaxy AND system = :fleet_end_system AND planet = :fleet_end_planet AND planet_type = :fleet_end_type;";
		$Target = Database::get()->selectSingle($sql, array(
			':fleet_end_galaxy'	=> $this->_fleet['fleet_end_galaxy'],
			':fleet_end_system'	=> $this->_fleet['fleet_end_system'],
			':fleet_end_planet'	=> $this->_fleet['fleet_end_planet'],
			':fleet_end_type'	=> $this->_fleet['fleet_end_type']
		));
		
		$UsedPlanet		= ($Target['id_owner'] == NULL) ? false : true;
		$config			= Config::get($this->_fleet['fleet_universe']);
		$sql			= "SELECT * FROM %%PLANETS%% WHERE id = :planetId;";
		$targetPlanet 	= Database::get()->selectSingle($sql, array(
			':planetId'	=> $this->_fleet['fleet_end_id']
		));

		
        if($UsedPlanet || !empty($targetPlanet)){
			$this->setState(FLEET_RETURN);
			$this->SaveFleet();
		} else {
			$FleetRecord         = explode(";", $this->_fleet['fleet_array']);
			$RecyclerCapacity    = 0;
			$OtherFleetCapacity  = 0; 
			foreach ($FleetRecord as $Item => $Group)
			{
				if (empty($Group))
					continue;
				
				$Class        = explode (",", $Group);
				if ($Class[0] == 209 || $Class[0] == 219)
					$RecyclerCapacity   += $pricelist[$Class[0]]['capacity'] * $Class[1];
				else
					$OtherFleetCapacity += 0;
			}
			
			$db			= Database::get();
			$sql		= "SELECT * FROM %%USERS%% WHERE id = :userId;";
			$playerInfo	= $db->selectSingle($sql, array(
				':userId'	=> $this->_fleet['fleet_owner']
			));
			
		
			$temporary = [
				'metal' => $Target['metal'],
				'crystal' => $Target['crystal'],
				'deuterium' => $Target['deuterium']
			];
			
			$IncomingFleetGoods = $this->_fleet['fleet_resource_metal'] + $this->_fleet['fleet_resource_crystal'] + $this->_fleet['fleet_resource_deuterium'];
			if ($IncomingFleetGoods > $OtherFleetCapacity)
				$RecyclerCapacity -= ($IncomingFleetGoods - $OtherFleetCapacity);
			
			$firstResource	= 901;
			$secondResource	= 902;
			$thirdResource	= 903;
					
			$stealResource	= [
				$firstResource => 0,
				$secondResource => 0,
				$thirdResource => 0
			];
			
			$AllCapacity		= $RecyclerCapacity;
			
			if($AllCapacity > 0){
				// Step 1
				$stealResource[$firstResource]		= min($AllCapacity, $temporary['metal']);
				$AllCapacity	-= $stealResource[$firstResource];
			 
				// Step 2
				$stealResource[$secondResource] 	= min($AllCapacity, $temporary['crystal']);
				$AllCapacity	-= $stealResource[$secondResource];
			 
				// Step 3
				$stealResource[$thirdResource] 		= min($AllCapacity, $temporary['deuterium']);
				$AllCapacity	-= $stealResource[$thirdResource];
				 
				// Step 4
				$oldMetalBooty  					= $stealResource[$firstResource];
				$stealResource[$firstResource] 		+= min($AllCapacity, $temporary['metal'] - $stealResource[$firstResource]);
				$AllCapacity	-= $stealResource[$firstResource] - $oldMetalBooty;
				 
				// Step 5
				$stealResource[$secondResource] 	+= min($AllCapacity, $temporary['crystal'] - $stealResource[$secondResource]);	

				// Step 6
				$stealResource[$thirdResource] 	+= min($AllCapacity, $temporary['deuterium'] - $stealResource[$thirdResource]);
				
				if($stealResource[$firstResource] <= 0) { 
					$stealResource[$firstResource] = 0;
				}
				if($stealResource[$secondResource] <= 0) { 
					$stealResource[$secondResource] = 0;
				}
				if($stealResource[$thirdResource] <= 0) { 
					$stealResource[$thirdResource] = 0;
				}
				
				$db	= Database::get();
				
				$sql	= "UPDATE %%FLEETS%% SET
				`fleet_resource_metal` = `fleet_resource_metal` + '".($stealResource[$firstResource])."',
				`fleet_resource_crystal` = `fleet_resource_crystal` + '".($stealResource[$secondResource])."',
				`fleet_resource_deuterium` = `fleet_resource_deuterium` + '".($stealResource[$thirdResource])."'
				WHERE fleet_id = :fleetId;";

				$db->update($sql, array(
				':fleetId'	=> (int) $this->_fleet['fleet_id'],
				));
			}
							
			
			$sql	= "DELETE FROM %%PLANETS%% WHERE galaxy = :fleet_end_galaxy AND system = :fleet_end_system AND planet = :fleet_end_planet AND planet_type = :fleet_end_type;";
			Database::get()->delete($sql, array(
				':fleet_end_galaxy'	=> $this->_fleet['fleet_end_galaxy'],
				':fleet_end_system'	=> $this->_fleet['fleet_end_system'],
				':fleet_end_planet'	=> $this->_fleet['fleet_end_planet'],
				':fleet_end_type'	=> $this->_fleet['fleet_end_type']
			));

			$LNG		= $this->getLanguage(NULL, $this->_fleet['fleet_owner']);

			$Message = "";
			
			if($stealResource[$firstResource] >= Config::get()->asteroid_metal && $stealResource[$secondResource] >= Config::get()->asteroid_crystal && $stealResource[$thirdResource] >= Config::get()->asteroid_deuterium){

				$sql	= "UPDATE %%USERS%% SET darkmatter = darkmatter + :darkmatter WHERE id = :id;";
				Database::get()->update($sql, array(
					':darkmatter'	=> 500,
					':id'	=> $this->_fleet['fleet_owner']
				));

				$Message 	.= sprintf($LNG['total_recycle_asteroid'], $LNG['tech'][921]);
			}
			
			$Message 	.= sprintf($LNG['sys_recy_gotten'], 
				pretty_number($stealResource[$firstResource]), $LNG['tech'][901],
				pretty_number($stealResource[$secondResource]), $LNG['tech'][902],
				pretty_number($stealResource[$thirdResource]), $LNG['tech'][903]
			);

			PlayerUtil::sendMessage($this->_fleet['fleet_owner'], 0, $LNG['sys_mess_tower'], 5,
				$LNG['sys_recy_report'], $Message, $this->_fleet['fleet_start_time'], NULL, 1, $this->_fleet['fleet_universe']);
				
			$this->setState(FLEET_RETURN);
			$this->SaveFleet();
		}
	}
	
	function EndStayEvent()
	{
		return;
	}
	
	function ReturnEvent()
	{
		$LNG		= $this->getLanguage(NULL, $this->_fleet['fleet_owner']);
		
		$sql		= 'SELECT name FROM %%PLANETS%% WHERE id = :planetId;';
		$planetName	= Database::get()->selectSingle($sql, array(
			':planetId'	=> $this->_fleet['fleet_start_id'],
		), 'name');
	
		$Message	= sprintf($LNG['sys_tran_mess_owner'],
			$planetName, GetTargetAddressLink($this->_fleet, ''),
			pretty_number($this->_fleet['fleet_resource_metal']), $LNG['tech'][901],
			pretty_number($this->_fleet['fleet_resource_crystal']), $LNG['tech'][902],
			pretty_number($this->_fleet['fleet_resource_deuterium']), $LNG['tech'][903]
		);

		PlayerUtil::sendMessage($this->_fleet['fleet_owner'], 0, $LNG['sys_mess_tower'], 5, $LNG['sys_mess_fleetback'], $Message, $this->_fleet['fleet_end_time'], NULL, 1, $this->_fleet['fleet_universe']);

		$this->RestoreFleet();
	}
}
?>