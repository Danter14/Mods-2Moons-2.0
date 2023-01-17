<?php

/**
 * @mods Extra Planet
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 */

class ShowExtraPlanetPage extends AbstractGamePage
{
	public static $requireModule = 0;
	
	function __construct() 
	{
		parent::__construct();
	}
	
	function show()
	{
      
    	global $USER, $LNG, $resource;

		/**
		 * On vérifie si le joueur n'est pas en mode vacance
		 * We check if the player is not in vacation mode
		 */
		if (!$USER['urlaubs_modus'] == 0) {
			$this->printMessage($LNG['error_mv'], true, array('game.php?page=overview', 3));
		}

		// Connexion BDD
		$db = Database::get();

		// Connexion Config
		$config = Config::get();

		$action = HTTP::_GP('action', '');

		/**
		 * Configuration des prix + limit extra planète
		 * Price configuration + extra planet limit
		 */
		$prize = 1500;
		$prize_full = 500;
		$extra_planet_count_buy = 1;
		$limit_extra_planet = 5;

		/**
		 * Récupération du nombre de planète coloniser par le joueur
		 * Recovery of the number of planets colonized by the player
		 */
		$iPlanets 	= $db->selectSingle("SELECT * FROM %%PLANETS%% WHERE `id_owner` = :userId AND `planet_type` = '1' AND `destruyed` = '0';", [":userId" => $USER['id']]);
		$iPlanetCount = $db->rowCount($iPlanets);
            
    	if ($action == "extraPlanetColo") {
            
			/**
			 * On vérifie si le joueur à assez de matière noir
			 * We check if the player has enough dark matter
			 */
			if ($USER[$resource[921]] < $prize) {
				$this->printMessage(sprintf($LNG['extra_planet_error_dm'], $LNG['tech'][921]), true, array('game.php?page=extraPlanet', 3));
			}
			
			/**
			 * Blocage d'achat des extra planètes si la limite de planète est pas atteinte (Recherches)
			 * Block purchase of extra planets if the planet limit is not reached (Research)
			 */
			if ($iPlanetCount <= $config->planets_tech) {		
				$this->printMessage(sprintf($LNG['extra_planet_error_colo'], $config->planets_tech), true, array('game.php?page=extraPlanet', 3));
			}
			
			/**
			 * On vérifie si le joueur n'a pas atteint le limite autoriser d'achat
			 * We check if the player has not reached the limit allowed to buy
			 */
			if ($USER['extra_planet'] <= $limit_extra_planet) {

				$db->update("UPDATE %%USERS%% SET `extra_planet` = `extra_planet` + :count_extra_planet WHERE `id` = :userId ;", [
					":count_extra_planet" => $extra_planet_count_buy,
					":userId" => $USER['id'],
				]);	

				$USER[$resource[921]]	-= $prize;
						
				$this->printMessage(sprintf($LNG['extra_planet_rep_success'], $extra_planet_count_buy), true, array('game.php?page=extraPlanet', 3));
			} else {
				$this->printMessage(sprintf($LNG['extra_planet_error_limit'], $limit_extra_planet), true, array('game.php?page=extraPlanet', 3));
			}

		}

		if ($action == "extraPlanetFull") {

			/**
			 * On vérifie si le joueur à assez de matière noir
			 * We check if the player has enough dark matter
			 */
			if ($USER[$resource[921]] < $prize_full) {
				$this->printMessage(sprintf($LNG['extra_planet_error_dm'], $LNG['tech'][921]), true, array('game.php?page=extraPlanet', 3));
			}

			/**
			 * On vérifie si nous avons bien un extra planète de disponible
			 * We check if we have an extra planet available
			 */
			if ($USER['extra_planet_create'] >= $USER['extra_planet']) {
				$this->printMessage($LNG['extra_planet_error_create'], true, array('game.php?page=extraPlanet', 3));
			}

			/**
			 * On vérifie si nous avons atteint le quota maxi de colonisation (Sécurité suplémentaire)
			 * We check if we have reached the maximum colonization quota (Additional security)
			 */
			if ($iPlanetCount >= PlayerUtil::maxPlanetCount($USER)) {	
				$this->printMessage($LNG['extra_planet_error_limit_colo'], true, array('game.php?page=extraPlanet', 3));
			}
			
			/**
			 * On vérifie si le joueur n'a pas atteint le limite autoriser d'achat
			 * We check if the player has not reached the limit allowed to buy
			 */
			if ($USER['extra_planet_create'] <= $limit_extra_planet) {

				$galaxy = HTTP::_GP('galaxy', 0);
				$system = HTTP::_GP('system', 0);
				$position = HTTP::_GP('position', 0);

				$created_planet = PlayerUtil::createPlanet($galaxy, $system, $position, Universe::current(), $USER['id'], NULL, false, 0, true);

				/**
				 * Si une erreur se produit on informe le joueur
				 * If an error occurs, the player is informed
				 */
				if (!$created_planet) {
					$this->printMessage(sprintf($LNG['extra_planet_error_position'], $galaxy, $system, $position), true, array('game.php?page=extraPlanet', 3));
				}

				$db->update("UPDATE %%USERS%% SET `extra_planet_create` = `extra_planet_create` + :count_extra_planet_create WHERE `id` = :userId ;", [
					":count_extra_planet_create" => 1,
					":userId" => $USER['id'],
				]);

				$USER[$resource[921]]	-= $prize_full;
						
				$this->printMessage(sprintf($LNG['extra_planet_rep_success_create'], $galaxy, $system, $position), true, array('game.php?page=extraPlanet', 3));
			} else {
				$this->printMessage(sprintf($LNG['extra_planet_error_limit_create'], $limit_extra_planet), true, array('game.php?page=extraPlanet', 3));
			}

		}

		$this->tplObj->assign_vars([
			'requiredDarkMatter'			=> $USER[$resource[921]] < $prize ? sprintf($LNG['tr_not_enought'], $LNG['tech'][921]) : false,
            'extra_planet_create'       	=> abs($USER['extra_planet_create'] - $USER['extra_planet']),
			'max_galaxy'					=> $config->max_galaxy,
			'max_system'					=> $config->max_system,
			'max_planets'					=> $config->max_planets,
			'content_text'					=> sprintf($LNG['extra_planet_content'], $iPlanetCount, PlayerUtil::maxPlanetCount($USER), $config->planets_tech, $USER['extra_planet'], $limit_extra_planet),
			'extra_planet_btn_buy' 			=> sprintf($LNG['extra_planet_btn_buy'], number_format($prize), $LNG['tech'][921]),
			'extra_planet_btn_buy_creted' 	=> sprintf($LNG['extra_planet_btn_buy_creted'], number_format($prize_full), $LNG['tech'][921]),
			'extra_planet_content_create' 	=> sprintf($LNG['extra_planet_content_create'], abs($USER['extra_planet_create'] - $USER['extra_planet']), $USER['extra_planet']),
		]);

		$this->display('page.extra.default.tpl');
	}
}