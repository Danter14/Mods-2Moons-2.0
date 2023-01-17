<?php

/**
 * @mods Bank
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 */

class ShowBankPage extends AbstractGamePage
{
	public static $requireModule = 0;

	function __construct() 
	{
		parent::__construct();
	}
	
	function show() 
	{
		global $USER, $LNG;

		/**
		 * On vérifie si le joueur n'est pas en mode vacance
		 * We check if the player is not in vacation mode
		 */
		if(!$USER['urlaubs_modus'] == 0) {
			$this->printMessage("Impossible en mode vacance !!", true, array('game.php?page=overview', 3));
		}

		// Connexion BDD
		$db = Database::get();

        // Config du jeu
        $config	= Config::get();

		/**
		 * Récupération de la commision du banqier
		 */
		$comm = ($config->commision_bank / 100) * 100;

		/**
		 * Permet de mettre à jour les ressources des planètes dans la base de donnée (non obligatoire)
		 * Allows to update the resources of the planets in the database (not required)
		**/
		$PlanetRess	= new ResourceUpdate();

		/**
		 * On vérifie si le joueur existe dans notre base de donnée
		 * We check if the player exists in our database
		**/
		$sql = "SELECT * FROM %%BANK%% WHERE user_id = :idUser ;";
		$response = $db->selectSingle($sql, ["idUser" => $USER['id']]);

		/**
		 * Si le joueur existe pas on force nos variable à 0
		 * If the player does not exist we force our variables to 0
		**/
		if (!$response) {
			$response['bank_metal'] = 0;
			$response['bank_cristal'] = 0;
			$response['bank_deuterium'] = 0;
			$response['bank_darkmatter'] = 0;
			$response['bank_time_update'] = TIMESTAMP;
			$response['bank_time_retrait'] = TIMESTAMP;
		}

		$this->tplObj->assign_vars([
			'bank_metal' => $response['bank_metal'],
			'bank_cristal' => $response['bank_cristal'],
			'bank_deuterium' => $response['bank_deuterium'],
			'bank_darkmatter' => $response['bank_darkmatter'],
			'commision_bank' => sprintf($LNG['bank_commision'], $comm),
			'bank_commision_exoneration' => sprintf($LNG['bank_commision_exoneration'], $LNG['tech'][921]),
			'dernier_depot' => $response['bank_time_update'] == TIMESTAMP ? $LNG['bank_date_no'] : sprintf($LNG['bank_date_depot'], _date($LNG['php_tdformat'], $response['bank_time_update'], $USER['timezone'])),
			'dernier_retrait' => $response['bank_time_retrait'] == TIMESTAMP ? $LNG['bank_date_no_r'] : sprintf($LNG['bank_date_retrait'], _date($LNG['php_tdformat'], $response['bank_time_retrait'], $USER['timezone'])),
		]);
		
		$this->display('page.bank.default.tpl');
	}

	function updateBank()
	{
		global $PLANET, $USER, $LNG;

		// Connexion BDD
		$db = Database::get();

        // Config du jeu
        $config	= Config::get();

		/**
		 * Récupération de la commission du banquier
		 * Recovery of the banker's commission
		 */
		$comm = ($config->commision_bank / 100) * 100;

		/**
		 * On mes les boutons pour la reception des ressource mis en banque
		 * I have the buttons for the reception of the resources put in the bank
		**/
		$depot_metal = HTTP::_GP('depot_metal', 0);
		$depot_cristal = HTTP::_GP('depot_cristal', 0);
		$depot_deuterium = HTTP::_GP('depot_deuterium', 0);
		$depot_darkmatter = HTTP::_GP('depot_darkmatter', 0);

		/**
		 * On vérifie que l'un des champs est remplis
		 * One verifies that one of the fields is filled
		**/
		if (empty($depot_metal) && empty($depot_cristal) && empty($depot_deuterium) && empty($depot_darkmatter)) {
			$this->printMessage($LNG['bank_error_empty']);
		}

		/**
		 * On fait les vérification pour être sur que le joueur dispose des ressources
		 * We make the checks to be sure that the player has the resources
		**/
		if ($PLANET['metal'] < $depot_metal || $PLANET['crystal'] < $depot_cristal || $PLANET['deuterium'] < $depot_deuterium || $USER['darkmatter'] < $depot_darkmatter) {
			$this->printMessage($LNG['bank_error_resource']);
		}

		/**
		 * On crée le total pour les ressources mis en banque moins la commision
		 * We create the total for the resources put in the bank less the commission
		**/
		$total_metal_bank = $depot_metal  - ($depot_metal * $comm / 100);
		$total_cristal_bank = $depot_cristal - ($depot_cristal * $comm / 100);
		$total_deuterium_bank = $depot_deuterium - ($depot_deuterium * $comm / 100);
		$total_darkmatter_bank = $depot_darkmatter;

		/**
		 * On vérifie si le joueur à fait un premier dépot sinon on fait un INSERT INTO
		 * We check if the player made a first deposit otherwise we do an INSERT INTO
		**/
		$sql_controle = "SELECT user_id FROM %%BANK%% WHERE user_id = :idUser;";
		$response_controle = $db->selectSingle($sql_controle, ["idUser" => $USER['id']]);

		$sql = "UPDATE ";
		if (!$response_controle) {
			$sql = "INSERT INTO ";
		}

		/**
		 * On insert le tout dans notre base de donnée pour le ressources mise en bank
		 * We insert the whole in our database for resources put in bank
		**/
		$sql .= "%%BANK%% SET 
			bank_metal = bank_metal + :totalM,
			bank_cristal = bank_cristal + :totalC,
			bank_deuterium = bank_deuterium + :totalD,
			bank_darkmatter = bank_darkmatter + :totalDm,
			bank_time_update = :timeUp";

		if (!$response_controle) {
			$sql .= ", user_id = :idUser;";
		} else {
			$sql .= " WHERE user_id = :idUser;";
		}

		$tab_sql = [
			"totalM" => $total_metal_bank,
			"totalC" => $total_cristal_bank,
			"totalD" => $total_deuterium_bank,
			"totalDm" => $total_darkmatter_bank,
			"timeUp" => TIMESTAMP,
			"idUser" => $USER['id'],
		];

		if (!$response_controle) {
			$db->insert($sql, $tab_sql);
		} else {
			$db->update($sql, $tab_sql);
		}

		/**
		 * On déduit de la planète ou se trouve le joueur les ressource mise dans la banque
		 * We deduce from the planet where the player is located the resource put in the bank
		**/
		$PLANET['metal'] -= $depot_metal;
		$PLANET['crystal'] -= $depot_cristal;
		$PLANET['deuterium'] -= $depot_deuterium;
		$USER['darkmatter'] -= $depot_darkmatter;

		$this->printMessage($LNG['bank_update_ok']);
	}

	function debitBank()
	{
		global $PLANET, $USER, $LNG;

		// Connexion BDD
		$db = Database::get();

        // Config du jeu
        $config	= Config::get();

		/**
		 * On mes les boutons pour la reception des ressource mis en banque
		 * I have the buttons for the reception of the resources put in the bank
		**/
		$debit_metal = HTTP::_GP('debit_metal', 0);
		$debit_cristal = HTTP::_GP('debit_cristal', 0);
		$debit_deuterium = HTTP::_GP('debit_deuterium', 0);
		$debit_darkmatter = HTTP::_GP('debit_darkmatter', 0);

		/**
		 * On vérifie si le joueur existe dans notre base de donnée
		 * We check if the player exists in our database
		**/
		$sql = "SELECT * FROM %%BANK%% WHERE user_id = :idUser;";
		$response = $db->selectSingle($sql, ["idUser" => $USER['id']]);

		if (!$response) {
			$this->printMessage($LNG['bank_error_joueur']);
		}

		/**
		 * On vérifie que l'un des champs est remplis
		 * One verifies that one of the fields is filled
		**/
		if (empty($debit_metal) && empty($debit_cristal) && empty($debit_deuterium) && empty($debit_darkmatter)) {
			$this->printMessage($LNG['bank_error_empty']);
		}

		/**
		 * On fait les vérifications pour être sur que le joueur dispose des ressources
		 * We make the checks to be sure that the player has the resources
		**/
		if ($response['bank_metal'] < $debit_metal || $response['bank_cristal'] < $debit_cristal || $response['bank_deuterium'] < $debit_deuterium || $response['bank_darkmatter'] < $debit_darkmatter) {
			$this->printMessage($LNG['bank_error_resource']);
		}

		/**
		 * On fait notre requête sql pour déduire les ressource à la bank
		 * We make our sql query to deduce the resource to the bank
		**/
		$sql_debit = "UPDATE %%BANK%% SET 
			bank_metal = bank_metal - :debitM,
			bank_cristal = bank_cristal - :debitC,
			bank_deuterium = bank_deuterium - :debitD,
			bank_darkmatter = bank_darkmatter - :debitDm,
			bank_time_retrait = :timeUp WHERE user_id = :idUser ;";
		$db->update($sql_debit, [
			"debitM" => $debit_metal,
			"debitC" => $debit_cristal,
			"debitD" => $debit_deuterium,
			"debitDm" => $debit_darkmatter,
			"timeUp" => TIMESTAMP,
			"idUser" => $USER['id'],
		]);

		/**
		 * On ajoute le retrait des ressources de la banque à la planète
		 * We add the withdrawal of resources from the bank to the planet
		**/
		$PLANET['metal'] += $debit_metal;
		$PLANET['crystal'] += $debit_cristal;
		$PLANET['deuterium'] += $debit_deuterium;
		$USER['darkmatter'] += $debit_darkmatter;

		$this->printMessage($LNG['bank_debit_ok']);
	}
}