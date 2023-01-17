<?php

/**
 * @mods Extra Planet
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 */

class PlayerUtil
{
	/**
	 * Rechercher cette fonction createPlanet avec le code suivant, Attention ne pas remplacer toute la fonction
	 * Find this createPlanet function with the following code, be careful not to replace the whole function
	 */
	static public function createPlanet($galaxy, $system, $position, $universe, $userId, $name = NULL, $isHome = false, $authlevel = 0, $isMod = false)
	{
		global $LNG;

		/**
		 * Recherche le code à remplacer
		 * Look for the code to replace
		 */
		if (self::checkPosition($universe, $galaxy, $system, $position) === false)
		{
			throw new Exception(sprintf("Try to create a planet at position: %s:%s:%s!", $galaxy, $system, $position));
		}

		if (self::isPositionFree($universe, $galaxy, $system, $position) === false)
		{
			throw new Exception(sprintf("Position is not empty: %s:%s:%s!", $galaxy, $system, $position));
		}

		/**
		 * Remplacer le code par ce code ci
		 * Replace the code with this code
		 */
		if (self::checkPosition($universe, $galaxy, $system, $position) === false)
		{
			if ($isMod) {
				return false;
			}
			
			throw new Exception(sprintf("Try to create a planet at position: %s:%s:%s!", $galaxy, $system, $position));
		}

		if (self::isPositionFree($universe, $galaxy, $system, $position) === false)
		{
			if ($isMod) {
				return false;
			}

			throw new Exception(sprintf("Position is not empty: %s:%s:%s!", $galaxy, $system, $position));
		}

		return $db->lastInsertId();
	}

	/**
	 * Rechercher cette fonction maxPlanetCount avec le code suivant, Attention ne pas remplacer toute la fonction
	 * Find this maxPlanetCount function with the following code, be careful not to replace the whole function
	 */
	static public function maxPlanetCount($USER)
	{
		/**
		 * Recherche le code à remplacer
		 * Look for the code to replace
		 */
		return (int) ceil($config->min_player_planets + min($planetPerTech, $USER[$resource[124]] * $config->planets_per_tech) + min($planetPerBonus, $USER['factor']['Planets']));

		/**
		 * Remplacer le code par ce code ci
		 * Replace the code with this code
		 */
		return (int) ceil($config->min_player_planets + min($planetPerTech, $USER[$resource[124]] * $config->planets_per_tech) + min($planetPerBonus, $USER['factor']['Planets']) + $USER['extra_planet']);
	}
}
