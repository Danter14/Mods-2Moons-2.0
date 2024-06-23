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

 function tournement($playerId, $tourneyEvent, $addUnits){

	$db = Database::get();
	$config = Config::get();

	$sql = "SELECT * FROM %%TOURNEY%% WHERE tourneyEvent = :tourneyId;";
	$tourneyInfo = $db->selectSingle($sql, [
		':tourneyId'	=> $tourneyEvent,
	]);

	$sql = "SELECT * FROM %%TOURNEYPARTICI%% WHERE tourneyJoin = :tourneyId AND playerId = :playerId;";
	$tourneyCheck = $db->selectSingle($sql, [
		':tourneyId'	=> $tourneyInfo['tourneyId'],
		':playerId'		=> $playerId,
	]);

	if(!empty($tourneyCheck) && $config->tourneyEnd >= TIMESTAMP) {
		$sql	= 'UPDATE %%TOURNEYPARTICI%% SET tourneyUnits = tourneyUnits + :tourneyUnits WHERE tourneyJoin = :tourneyJoin AND playerId = :playerId;';
		$db->update($sql, [
			':tourneyUnits'	=> $addUnits,
			':tourneyJoin'	=> $tourneyInfo['tourneyId'],
			':playerId'		=> $playerId,
		]);
	}
}

function getUsername(int $userId) {
	if($userId <= 0) {
		return false;
	};

	$sql = "SELECT username FROM %%USERS%% WHERE id = :userID ;";
	$params = [":userID" => $userId];
	$result = database::get()->selectSingle($sql, $params);

	return $result["username"];
}
