<?php

/**
 * @mods Asteroids
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 */

require_once 'includes/classes/cronjob/CronjobTask.interface.php';

class AsteroidsCronjob implements CronjobTask
{

	function run()
	{

		$langObjects	= [];
		$db	= Database::get();
		$config	= Config::get(ROOT_UNI);

		if($config->asteroid_event < TIMESTAMP && $config->asteroid_actif == 1) {

			$renouvellement = TIMESTAMP + 5*60; // Ajout d'astéroïde toute les 5 minutes
			$asteroid_count = $config->asteroid_count; // Nombre d'astéroides créer dans la galaxy au total

			/**
			 * Configuration du nombre de galaxy, système et planète
			 */
			$galaxy         =    1;
        	$system         =    1;
        	$planet         =    1;
        	$galaxy2        =    $config->max_galaxy;
        	$system2        =    $config->max_system;
        	$planet2        =    $config->max_planets;

			$sql	= "UPDATE %%CONFIG%% SET 
				asteroid_event = :asteroid_event,
				asteroid_round = asteroid_round + 1 
				WHERE uni = :uni;";
			$db->update($sql, [
				':asteroid_event'	=> $renouvellement,
				':uni'	=> 1
			]);

			$sql	= "DELETE FROM %%PLANETS%% WHERE diameter = :diameter;";
			$db->delete($sql, [':diameter'	=> 9800]);
			
			$Example1 = 0;
			$Example2 = 0;
			$Example3 = 0;

			// On fait une boucle pour la création des astéroïdes
			for($i = 0; $i < $asteroid_count; $i++) {

				$gala         =    mt_rand($galaxy,$galaxy2);
                $syst         =    mt_rand($system,$system2);
                $plan         =    mt_rand($planet,$planet2);

				/**
				 * On vérifie si il y a déjà un astéroïd à ces coordonnée
				 */
				$sql_verif	= "SELECT * FROM %%PLANETS%% WHERE galaxy = :galaxy AND system = :system AND planet = :planet AND universe = :universe;";
				$verif = $db->select($sql_verif, [
					':galaxy'	=> $gala,
					':system'	=> $syst,
					':planet'	=> $plan,
					':universe'	=> 1
				]);

				if($db->rowCount($verif) > 0) {
					$i--;
				} else {
					$metal_rand = $config->asteroid_metal + round($config->asteroid_metal / 100 * (1*$config->asteroid_round));
					$crystal_rand = $config->asteroid_crystal + round($config->asteroid_crystal / 100 * (1*$config->asteroid_round));
					$deuterium_rand= $config->asteroid_deuterium + round($config->asteroid_deuterium / 100 * (1*$config->asteroid_round));
						
					$sql = "INSERT INTO %%PLANETS%% SET
						name			= :name,
						id_owner		= :id_owner,
						universe		= :universe,
						galaxy			= :galaxy,
						system			= :system,
						planet			= :planet,
						planet_type		= :planet_type,
						image			= :image,
						diameter		= :diameter,
						metal			= :metal,
						crystal			= :crystal,
						deuterium		= :deuterium,
						last_update		= :last_update;";

					$db->insert($sql, [
						':name'				=> 'Asteroid',
						':id_owner'			=> NULL,
						':universe'			=> 1,
						':galaxy'			=> $gala,
						':system'			=> $syst,
						':planet'			=> $plan,
						':planet_type'		=> 1,
						':image'			=> 'asteroid',
						':diameter'			=> 9800,
						':metal'			=> $metal_rand,
						':crystal'			=> $crystal_rand,
						':deuterium'		=> $deuterium_rand,
						':last_update'		=> TIMESTAMP
					]);
						
					$Example1 = $gala;
					$Example2 = $syst;
					$Example3 = $plan;
				}
			}
			
			$sql_messaging	= "SELECT DISTINCT id, lang FROM %%USERS%%";
			$result_messaging = $db->select($sql_messaging);

			foreach($result_messaging as $userInfo){
				
				if(!isset($langObjects[$userInfo['lang']]))
				{
					$langObjects[$userInfo['lang']]	= new Language($userInfo['lang']);
					$langObjects[$userInfo['lang']]->includeData(array('L18N', 'INGAME', 'TECH', 'CUSTOM'));
				}
				
				$LNG	= $langObjects[$userInfo['lang']];
				
				$message = '<span class="admin">'.sprintf($LNG['custom_asteroid'], $Example1, $Example2, $Example3).'
				</span>';
				PlayerUtil::sendMessage($userInfo['id'], 0, $LNG['cronjob_asteroid_msg_from'], 50, $LNG['cronjob_asteroid_msg_to'], $message, TIMESTAMP);
			}
			
		} else if ($config->asteroid_actif == 0 && $config->asteroid_event > 0) {
			$sql	= "DELETE FROM %%PLANETS%% WHERE image = :image;";
			$db->delete($sql, [':image'	=> 'asteroid']);

			$sql	= "UPDATE %%CONFIG%% SET 
				asteroid_event = :asteroid_event,
				asteroid_round = :asteroid_round
				WHERE uni = :uni;";
			$db->update($sql, [
				':asteroid_event'	=> 0,
				':asteroid_round'	=> 0,
				':uni'	=> 1
			]);

			$sql	= "SELECT DISTINCT id, lang FROM %%USERS%%";
			$result_messaging = $db->select($sql);

			foreach($result_messaging as $userInfo){
				
				if(!isset($langObjects[$userInfo['lang']]))
				{
					$langObjects[$userInfo['lang']]	= new Language($userInfo['lang']);
					$langObjects[$userInfo['lang']]->includeData(array('L18N', 'INGAME', 'TECH', 'CUSTOM'));
				}
				
				$LNG	= $langObjects[$userInfo['lang']];
				
				$message = '<span class="admin">'.$LNG['cronjob_asteroid_msg_user_event_close'].'</span>';
				PlayerUtil::sendMessage($userInfo['id'], 0, $LNG['cronjob_asteroid_msg_from'], 50, $LNG['cronjob_asteroid_msg_to'], $message, TIMESTAMP);
			}
		}
		
	}
}