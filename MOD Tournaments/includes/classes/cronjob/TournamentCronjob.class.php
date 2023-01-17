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

require_once 'includes/classes/cronjob/CronjobTask.interface.php';

class TournamentCronJob implements CronjobTask
{
	function run()
	{
		if(config::get()->tourneyEnd < TIMESTAMP){
			$tourneyIds = array(1,2,3,4,5);
			foreach($tourneyIds as $Ids){
				$sql 	= "SELECT * FROM %%TOURNEYPARTICI%% WHERE tourneyJoin = :tourneyJoin AND tourneyUnits > 0 ORDER BY tourneyUnits DESC LIMIT 3;";
				$totalPlayers = database::get()->select($sql, array(
					':tourneyJoin'    => $Ids
				));
				$sql = "SELECT * FROM %%TOURNEY%% WHERE tourneyId = :tourneyId;";
				$tourneyDetail = database::get()->selectSingle($sql, array(
					':tourneyId'	=> $Ids
				));
				$sql	= 'DELETE FROM %%TOURNEYLOGS%%;';
				Database::get()->delete($sql, array());
				
				$countPrice = 0;
				$priceArray = array("0" => $tourneyDetail['priceOne'], "1" => $tourneyDetail['priceTwo'], "2" => $tourneyDetail['priceThree']);
				foreach($totalPlayers as $Winner){
					$sql 	= "UPDATE %%USERS%% SET antimatter = antimatter + :antimatter WHERE id = :userId;";
					database::get()->update($sql, array(
						':antimatter'   => $priceArray[$countPrice],
						':userId'    	=> $Winner['playerId']
					));
					$sql 	= "INSERT INTO %%TOURNEYLOGS%% SET tourneyUnits = :tourneyUnits, playerId = :playerId, joinTime = :joinTime, tourneyJoin = :tourneyJoin;";
					database::get()->insert($sql, array(
						':tourneyUnits' => $Winner['tourneyUnits'],
						':playerId'   	=> $Winner['playerId'],
						':joinTime'   	=> $Winner['joinTime'],
						':tourneyJoin'  => $Winner['tourneyJoin'],
					));
					PlayerUtil::sendMessage($Winner['playerId'], $Winner['playerId'], getUsername($Winner['playerId']), 4, "You won the tournament", "You have won a tournament and received. ".pretty_number($priceArray[$countPrice])." antimatter.", TIMESTAMP);
					$countPrice++;
				}
			}

			$sql	= 'DELETE FROM %%TOURNEYPARTICI%%;';
			database::get()->delete($sql, array());
			$sql	= 'DELETE FROM %%TOURNEY%%;';
			database::get()->delete($sql, array());
			//START NEW COMPETITIONS HERE
			$AlowedEvents = array(1,2,3,4,5,6,7,8,9,10,11);
			$totalTourney = array(1,2,3,4,5);
			$tourneyNames =	array("Alpha", "Beta", "Gamma", "Delta", "Epsilon");
			$countArray   = 0;
			$priceArray	  = array("0" => array(5000,3000,2000), "1" => array(4500,2700,1800), "2" => array(4000,2400,1600), "3" => array(3500,2100,1400), "4" => array(3000,1800,1200), "5" => array(0,0,0), "6" => array(0,0,0), "7" => array(0,0,0));
			$CountIdTour  = 0;
			foreach($totalTourney as $Tourney){
				$randTourney  = array_rand($AlowedEvents, 1) + 1;
				$RealCat	  = $randTourney - 1;
				unset($AlowedEvents[$RealCat]);
				$CountIdTour++;
				$sql 	= "INSERT INTO %%TOURNEY%% SET tourneyId = :tourneyId, tourneyName = :tourneyName, priceOne = :priceOne, priceTwo = :priceTwo, priceThree = :priceThree, tourneyEvent = :tourneyEvent;";
				database::get()->insert($sql, array(
					':tourneyId' 	=> $CountIdTour,
					':tourneyName' 	=> $tourneyNames[$countArray],
					//':priceOne'   	=> $priceArray[$randTourney][0],
					//':priceTwo'   	=> $priceArray[$randTourney][1],
					//':priceThree'  	=> $priceArray[$randTourney][2],
					':priceOne'   	=> 5000,
					':priceTwo'   	=> 3000,
					':priceThree'  	=> 2000,
					':tourneyEvent' => $randTourney,
				));
				$countArray++;
			}

			//SEND MESSAGE TO ALL PLAYERS FOR NEW START TOURNAMENTS
			$timeToUpdate	= TIMESTAMP;
			$sql	= "SELECT DISTINCT id, lang FROM %%USERS%% WHERE onlinetime > :onlinetime;";
			$totalUsers = database::get()->select($sql, array(
				':onlinetime'	=> $timeToUpdate - (7 * 24 * 3600),
			));

			//SET A NEW TIMER
			config::get(ROOT_UNI)->tourneyEnd	= TIMESTAMP + 48 * 3600;
			$sql	= 'UPDATE %%CONFIG%% SET `tourneyEnd` = :tourneyEnd WHERE `uni` = 1;';
			database::get()->update($sql, array(
				':tourneyEnd'	=> TIMESTAMP + 48 * 3600, 
			));	
		}
	}
}