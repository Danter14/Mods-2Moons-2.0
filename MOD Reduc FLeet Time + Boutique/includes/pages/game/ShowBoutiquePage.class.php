<?php

/**
 * @mods Shop
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 */

class ShowBoutiquePage extends AbstractGamePage
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
		 * Prix de base de la réduction du temps de flotte
		 * Fleet Time Reduction Base Price
		 */
		$prize_base = 500;

		if ($action == "bonus_fleet_reduc_time") {
			$time_reduc = HTTP::_GP('time_reduc', 0);

			/**
			 * On indique une erreur du montant si 0 ou négatif
			 * An error in the amount is indicated if 0 or negative
			 */
			if ($time_reduc == null || $time_reduc <= 0) {
				$this->printMessage($LNG['boutique_redu_fleet_error_number'], true, array('game.php?page=boutique', 3));
			}

			/**
			 * Formule pour le coût d'achat
			 * Formula for purchase cost
			 */
			$prize = floor($prize_base * (1 + $time_reduc / 100));

			/**
			 * On vérifie si le joueur a assez de matière noir
			 * We check if the player has enough dark matter
			 */
			if ($prize > $USER[$resource[921]]) {
				$this->printMessage(sprintf($LNG['boutique_redu_fleet_error_dm'], $LNG['tech'][921]), true, array('game.php?page=boutique', 3));
			}

			$sql = "UPDATE %%USERS%% SET fleet_reduc_speed = :fleet_reduc_speed WHERE id = :userId;";
			$params = [
				":fleet_reduc_speed" => $time_reduc,
				":userId" => $USER['id'],
			];

			$db->update($sql, $params);

			$USER[$resource[921]] -= $prize;

			$this->printMessage(sprintf($LNG['boutique_redu_fleet_success'], number_format($prize), $LNG['tech'][921], $time_reduc), true, array('game.php?page=boutique', 3));
		}

		$this->tplObj->assign_vars([
			"prize_base" => $prize_base,
		]);

		$this->display('page.boutique.default.tpl');
	}
}