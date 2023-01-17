<?php

/**
 * @mods Loterie
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 */

define('DB_VERSION_REQUIRED', 4);
define('DB_NAME'			, $database['databasename']);
define('DB_PREFIX'			, $database['tableprefix']);

// Data Tabells
$dbTableNames	= array(
	//MODS TABLES ADDON
	'LOTTERY'			=> DB_PREFIX.'lottery',
	'LOTTERYLOGS'		=> DB_PREFIX.'lottery_log',
);