<?php

/**
 * @mods Tournoi
 * @package 2Moons
 * @author yamilrh
 * @modification Danter14
 * @licence MIT
 * @version 1.8 - 1.9 - 2.0
 */

define('DB_VERSION_REQUIRED', 4);
define('DB_NAME'			, $database['databasename']);
define('DB_PREFIX'			, $database['tableprefix']);

// Data Tabells
$dbTableNames	= array(
	//MODS TABLES ADDON
	'QUESTS_CATEGORIES'			=> DB_PREFIX.'quests_categories',
	'QUESTS_LISTS'				=> DB_PREFIX.'quests_lists',
	'QUESTS_USERS'				=> DB_PREFIX.'quests_users',
);