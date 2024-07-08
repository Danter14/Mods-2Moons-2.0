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
	// MOD-TABLES VOUCHER
	'VOUCHER'			=> DB_PREFIX . 'voucher',
	'VOUCHER_REWARD'	=> DB_PREFIX . 'voucher_rewards',
	'VOUCHER_USER_USE'	=> DB_PREFIX . 'voucher_user_use',
);