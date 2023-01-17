<?php

/**
 * MOD BONUS
 * @Author Danter14
 * @date 20/10/2022
 * @version 2moons 1.8-1.9-2.0
 */

class ShowBonusPage extends AbstractGamePage
{
	public static $requireModule = MODULE_SUPPORT;

	function __construct()
	{
		parent::__construct();
	}

	function show()
	{
		global $USER, $PLANET, $LNG, $resource;

		/**
		 * On vérifie si le joueur n'est pas en mode vacance
		 * We check if the player is not in vacation mode
		 */
		if(!$USER['urlaubs_modus'] == 0) {
			$this->printMessage("Impossible de récupérer le bonus en mode vacance !!", true, array('game.php?page=overview', 3));
		}

		// Connexion BDD
		$db = Database::get();

        // Config du jeu
        $config	= Config::get();

		/** 
		 * Récupération de la matière noir du joueur
		 * Player's Dark Matter Recovery
		 */
		$darkmatter = $USER['darkmatter'];

		/** 
		 * Récupération pour le nombre de ligne de bonus dans la table
		 * Payback for the number of bonus rows in the table
		 */
        $sql = "SELECT * FROM uni1_bonus ORDER BY bonusID DESC";
        $result_select = $db->select($sql);
		$restult_count = $db->rowCount($result_select);

		/**
		 * On vérifie si il y a des bonus disponible dans la BDD
		 * We check if there are bonuses available in the database
		 */
		if($restult_count < 1) {
			$this->printMessage("Aucun bonus n'est disponible actuellement !!", true, array('game.php?page=overview', 3));
		}

		/**
		 * On vérifie si le joueur à déjà récupéré son bonus
		 * We check if the player has already collected his bonus
		 */
		if($USER['bonus_attente_time'] > TIMESTAMP) {
			$this->printMessage("Votre temps d'attente n'est pas encore écouler !!", true, array('game.php?page=overview', 3));
		}

		/**
		 * On récupère l'id le plus petit de notre table
		 * We get the smallest id of our table
		 */
		foreach($result_select as $response) {
			$id_min = $response['bonusID'];
		}

		/**
		 * On choisi au hasard l'un des bonus
		 * We randomly choose one of the bonus
		 */
		$rand = mt_rand($id_min , $restult_count);

		/**
		 * On récupère les informations du bonus
		 * We retrieve the bonus information
		 */
		$sql_single = "SELECT * FROM uni1_bonus WHERE bonusID = :id";
		$result = $db->selectSingle($sql_single, ["id" => $rand]);

		/**
		 * On donne les ressources aléatoirement entre notre min et max * la vitesse des ressources du serveur
		 * We give the resources randomly between our min and max * the speed of the server resources
		 */
		$metal = mt_rand($result["metal_min"], $result["metal_max"]) * $config->resource_multiplier;
		$cristal = mt_rand($result["crystal_min"], $result["crystal_max"]) * $config->resource_multiplier;
		$deuterium = mt_rand($result["deuterium_min"], $result["deuterium_max"]) * $config->resource_multiplier;
		$darkmatter_add = mt_rand($result["darkmatter_min"], $result["darkmatter_max"]);
		$time_att = $result["time_att_bonus"] + TIMESTAMP;
		$attente = _date($LNG['php_tdformat'], $time_att, $USER['timezone']);


		/**
		 * On ajoute le temps d'attent du prochain bonus dans le table users
		 * We add the waiting time for the next bonus in the users table
		 */
		$sql_update = "UPDATE %%USERS%% SET bonus_attente_time = :att WHERE id = :userId";
		$db->update($sql_update, ["att" => $time_att, "userId" => $USER['id']]);

		/**
		 * On ajoute les ressources reçu aléatoirement précédemment dans le BDD (table planets et users)
		 * We add the resources received randomly previously in the BDD (table planets and users)
		 */
		$PLANET[$resource[901]] += $metal;
		$PLANET[$resource[902]] += $cristal;
		$PLANET[$resource[903]] += $deuterium;
		$USER[$resource[921]] += $darkmatter_add;

		/**
		 * On informe le joueur par un message dans le jeu de ces gain du bonus
		 * The player is informed by a message in the game of these bonus wins
		 */
		$this->printMessage(
			$LNG['tech'][901]." :".pretty_number($metal).
			"<br>".$LNG['tech'][902]." :".pretty_number($cristal).
			"<br>".$LNG['tech'][903]." :".pretty_number($deuterium).
			"<br>".$LNG['tech'][921]." :".pretty_number($darkmatter_add).
			"<br>Prochain bonus : ".$attente, true, array('game.php?page=overview', 3));
		
		$this->tplObj->assign_vars([]);
	}
}