<?php

/**
 * @mods Tournoi
 * @version 1.0
 * @author yamilrh
 * @modification Danter14
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
	'TOURNEY'			=> DB_PREFIX.'tourney',
	'TOURNEYLOGS'		=> DB_PREFIX.'tourney_logs',
	'TOURNEYPARTICI'	=> DB_PREFIX.'tourney_participante',
);