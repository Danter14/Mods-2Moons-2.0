<?php

/**
 *  2Moons
 *   by Jan-Otto Krpke 2009-2016
 *
 * For the full copyright and license information, please view the LICENSE
 *
 * @package 2Moons
 * @author Jan-Otto Krpke <slaver7@gmail.com>
 * @copyright 2009 Lucky
 * @copyright 2016 Jan-Otto Krpke <slaver7@gmail.com>
 * @licence MIT
 * @version 2.0.0
 * @link https://github.com/jkroepke/2Moons
 * ADDON Mod Bonus By Danter14
 */

abstract class AbstractGamePage
{

	protected function getNavigationData()
	{
		global $PLANET, $LNG, $USER, $THEME, $resource, $reslist;

		/**
		 * Ajouter uniquement cette ligne 'bonus_att' dans la fonction
		 * Add only this line 'bonus_att' in the function
		 */
		$this->assign(array(
			'bonus_att'			=> $USER['bonus_attente_time'] > TIMESTAMP ? _date($LNG['php_tdformat'], $USER['bonus_attente_time'], $USER['timezone']) : 0, // Addon bonus navigation
		));
	}
}