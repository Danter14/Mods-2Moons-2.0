<?php

/**
 * @mods Extra Moon
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 */

class ShowMoonbuyPage extends AbstractGamePage
{
	public static $requireModule = 0;
	
	function __construct() 
	{
		parent::__construct();
	}
	
	function show()
	{
		global $USER,$PLANET, $LNG, $resource;

		/**
		 * On vérifie si le joueur n'est pas en mode vacance
		 * We check if the player is not in vacation mode
		 */
		if(!$USER['urlaubs_modus'] == 0) {
			$this->printMessage("Impossible en mode vacance !!", true, array('game.php?page=overview', 3));
		}

		// Connexion BDD
		$db = Database::get();
	
		/**
		 * Coût pour l'achat d'une nouvelle lune
		 * Cost for buying a new moon
		 */
		$prize = 1000;

		$action = HTTP::_GP('action', '');

		if($action == "createMoon") {

			/**
			 * On vérifie si nous avons assez de matière noir
			 * We check if we have enough dark matter
			 */
			if ($USER[$resource[921]] < $prize) {
				$this->printMessage("You don't have enough ".$LNG["tech"][921].", it costs you ".number_format($prize)." ".$LNG["tech"][921].".", true, array('game.php?page=moonBuy', 3));
			} else {
				/**
				 * On vérifie si nous n'avon pas déjà une lune sur notre planète
				 * We check if we don't already have a moon on our planet
				 */
				if ($PLANET['planet_type'] == 1 && $PLANET['id_luna'] == 0) {
				$u_have_moon = PlayerUtil::createMoon(Universe::current(), $PLANET['galaxy'], $PLANET['system'], $PLANET['planet'], $USER['id'], 100 /*$taille*/);
					/**
					 * On vérifie si notre lune est bien créer
					 * We check if our moon is well created
					 */
					if ($u_have_moon) {
						$USER[$resource[921]] -= $prize;
						$this->printMessage("You have bought a moon",true, array('game.php?page=overview', 3));
					} else {
						$this->printMessage("error create a moon",true, array('game.php?page=moonBuy', 3));
					}
				} else {
					$this->printMessage("You already have a moon at this planet coords", true, array('game.php?page=overview', 2));
				}
			}
		}

		$this->tplObj->assign_vars([
			'prize' => number_format($prize),
		]);

		$this->display('page.moonbuy.default.tpl');
	}
}