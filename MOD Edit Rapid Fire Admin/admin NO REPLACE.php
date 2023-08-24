<?php

/**
 *  2Moons 
 *   by Jan-Otto Kröpke 2009-2016
 *
 * For the full copyright and license information, please view the LICENSE
 *
 * @package 2Moons
 * @author Jan-Otto Kröpke <slaver7@gmail.com>
 * @copyright 2009 Lucky
 * @copyright 2016 Jan-Otto Kröpke <slaver7@gmail.com>
 * @licence MIT
 * @version 1.8.0
 * @link https://github.com/jkroepke/2Moons
 */

define('MODE', 'ADMIN');
define('DATABASE_VERSION', 'OLD');

define('ROOT_PATH', str_replace('\\', '/',dirname(__FILE__)).'/');

require 'includes/common.php';
require 'includes/classes/class.Log.php';

if ($USER['authlevel'] == AUTH_USR)
{
	HTTP::redirectTo('game.php');
}

$session	= Session::create();
if($session->adminAccess != 1)
{
	include_once('includes/pages/adm/ShowLoginPage.php');
	ShowLoginPage();
	exit;
}

$uni	= HTTP::_GP('uni', 0);

if($USER['authlevel'] == AUTH_ADM && !empty($uni))
{
	Universe::setEmulated($uni);
}

$page	= HTTP::_GP('page', '');
switch($page)
{
	// On ajoute les liens pour l'édition des rapides fires
	case 'edit-fire':
		include_once('includes/pages/adm/ShowEditFirePage.php');
		ShowEditFirePage();
	break;
	default:
		include_once('includes/pages/adm/ShowIndexPage.php');
		ShowIndexPage();
	break;
}
