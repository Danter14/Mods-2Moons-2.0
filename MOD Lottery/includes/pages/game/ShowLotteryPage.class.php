<?php

/**
 * @mods Loterie
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 */

class ShowLotteryPage extends AbstractGamePage
{	
	public static $requireModule = 0;
	
	function __construct() {
		parent::__construct();
	}

	/**
	* On vérifie si l'événement loterie est actif ou non
	* We check if the lottery event is active or not
	* @return bool
	*/
	function verifActifLoterie(): bool {
		$db = Database::get();
		$config = Config::get();

		if(!$config->lottery_actif && $config->lottery_actif_att_time < TIMESTAMP && !empty($config->lottery_actif_att_time)) {
			$db->update("UPDATE %%CONFIG%% SET lottery_actif = 1, lottery_actif_att_time = 0 ;");
		}

		if(
			!$config->lottery_actif || 
			(!$config->lottery_actif && !$config->lottery_actif_att_time) ||
			$config->lottery_actif_att_time > TIMESTAMP
		) { return false; }

		return true;
	}
	
	function show()
	{
		global  $USER, $PLANET, $LNG, $resource;

		$db = Database::get();
		$config = Config::get();
	
		/**
		 * On récupère toute nos configuration de notre loterie
		 * We recover all our configuration of our lottery
		 */
		$max_lottery_tickets = $config->lottery_max_tickets;
		$max_users_tickets = $config->lottery_max_users_tickets;
		$ticket_price_metal = $config->lottery_ticket_prize_metal * $config->resource_multiplier;
		$ticket_price_crystal = $config->lottery_ticket_prize_crystal * $config->resource_multiplier;
		$ticket_price_deuterium = $config->lottery_ticket_prize_deuterium * $config->resource_multiplier;
		$ticket_prize_dm = $config->lottery_prize;
		$lottery_prize_add = $config->lottery_prize_add;
		/** 
		 * Joueur max qui peut gagner au tirage (de 0 à max aléatoirement)
		 * Max player who can win the draw (from 0 to max randomly)
		 */
		$count_max_winner = $config->lottery_max_users_winner;

		/** 
		 * Temps d'attente entre chaque tirage, actuellement toute les 12h
		 * Waiting time between each draw, currently every 12 hours
		 */
		$time_att_tirage = TIMESTAMP + 12*60*60;

		$this->tplObj->loadscript('jquery.countdown.js');

		if(!$this->verifActifLoterie())
		{
			$lottery_actif_att_time = ($config->lottery_actif_att_time == 0) ? $LNG['lottery_att_date_empty'] : _date($LNG['php_tdformat'], $config->lottery_actif_att_time, $USER['timezone']);
			$this->tplObj->assign_vars(array(
				'lottery_actif' => $this->verifActifLoterie(),
				'lng_lottery_att_date' => sprintf($LNG['lottery_att_date'], $config->lottery_count, $lottery_actif_att_time)
			));

			$this->display('page.lottery.default.tpl');
			die();
		}

		$sql = "SELECT SUM(tickets) as totals_vente FROM %%LOTTERY%% ;";
		$result_controle = $db->selectSingle($sql);
		$count_tickets_restant = $max_lottery_tickets - $result_controle['totals_vente'];

		if($_POST) {

			$tickets = HTTP::_GP('tickets', 0);

			if($result_controle["totals_vente"] >= $max_lottery_tickets) {
				$this->printMessage($LNG['lottery_error_ticket_solde_out'], true, array('game.php?page=lottery', 2));
				die();
			}

			if($tickets <= 0 || $tickets > $max_users_tickets) {
				$this->printMessage($LNG['lottery_error_ticket_num'], true, array('game.php?page=lottery', 2));
				die();
			}

			$sql_exists = $db->selectSingle("SELECT * FROM %%LOTTERY%% WHERE `userId` = :userId ;", [':userId' => $USER['id']]);
		
			if($db->rowCount($sql_exists) > 0) {
				$sql_tickets = $db->selectSingle("SELECT SUM(tickets) as total_tickets FROM %%LOTTERY%% WHERE `userId` = :userId ;", [':userId' => $USER['id']]);
				if((($sql_tickets['total_tickets'] + $tickets) > $max_users_tickets)){
					$ticket_user_reste =  $max_users_tickets - $sql_tickets['total_tickets'];
					$this->printMessage(sprintf($LNG['lottery_error_ticket_buy'], $ticket_user_reste), true, array('game.php?page=lottery', 2));
					die();
				}
			}

			if($tickets > $count_tickets_restant){
				$this->printMessage($LNG['lottery_error_ticket_superieur'], true, array('game.php?page=lottery', 2));
				die();
			}

			$cost['metal'] = $tickets * $ticket_price_metal;
			$cost['crystal'] = $tickets * $ticket_price_crystal;
			$cost['deuterium'] = $tickets * $ticket_price_deuterium;

			if($PLANET['metal'] < $cost['metal'] || $PLANET['crystal'] < $cost['crystal'] || $PLANET['deuterium'] < $cost['deuterium']){
				$this->printMessage($LNG['lottery_error_tickets_add'], true, array('game.php?page=lottery', 2));
				die();
			} else {
	            $PLANET[$resource[901]]	-= $cost['metal'];
	            $PLANET[$resource[902]]	-= $cost['crystal'];
	            $PLANET[$resource[903]]	-= $cost['deuterium'];

				$db->insert("INSERT INTO %%LOTTERY%% SET 
					userId = :userId, 
					tickets = :nbtickets",
					[
						":userId" => $USER['id'],
						":nbtickets" => $tickets,
				]);
				
				$this->printMessage(sprintf($LNG['lottery_success_ticket_buy'], $tickets), true, array('game.php?page=lottery', 2));
				die();
			}
		}
		
		$total_users = $db->select("SELECT DISTINCT `userId` FROM %%LOTTERY%% ;");
		$total_users = $db->rowCount($total_users);

		if($config->lottery_time < TIMESTAMP) {
			if($total_users < $config->lottery_min) {

				$db->update("UPDATE %%CONFIG%% SET `lottery_prize` = `lottery_prize` + :lottery_add_prise ;", [":lottery_add_prise" => $lottery_prize_add]);
				$time = $time_att_tirage;
				$db->update("UPDATE %%CONFIG%% SET `lottery_time` = :timeOut ;", [":timeOut" => $time]);
				$this->printMessage($LNG['lottery_error_report'], true, array('game.php?page=lottery', 2));
				die();
			}

			if(($config->lottery_min < $count_max_winner) || !$count_max_winner) {

				$db->update("UPDATE %%CONFIG%% SET `lottery_prize` = `lottery_prize` + :lottery_add_prise ;", [":lottery_add_prise" => $lottery_prize_add]);
				$time = $time_att_tirage;
				$db->update("UPDATE %%CONFIG%% SET `lottery_time` = :timeOut ;", [":timeOut" => $time]);
				$this->printMessage($LNG['lottery_error_conf_adm'], true, array('game.php?page=lottery', 2));
				die();
			}

			/**
			 * Algorithme pour les récompenses et gestion du tirage des joueurs
			 * Algorithm for rewards and player draw management
			 */
			$get_tickets = $db->select("SELECT * FROM %%LOTTERY%% ");
			if($db->rowCount($get_tickets) > 0) {

				$user_array = [];
				$diferent_users = $db->select("SELECT DISTINCT `userId` FROM %%LOTTERY%% ;");
				$user_count = $db->rowCount($diferent_users);

				foreach($diferent_users as $user_diff) {
					$user_array[] = $user_diff['userId'];
				}

				$user_win = [];
				$winner_count = 0;
				for ($i = 0; $i < $count_max_winner; $i++) {
					$randomIndex = array_rand($user_array);
					$randomPlayer = $user_array[$randomIndex];
					if(!in_array($randomPlayer, $user_win)){
						$user_win[$i+1] = $randomPlayer;
					}else{
						$i--;
					}
				}

				$winner_list = [];
				foreach($user_win as $key => $winner) {
					$total_user  = $db->selectSingle("SELECT SUM(tickets) as sum_tickets FROM %%LOTTERY%% WHERE `userId` = :userID ;", [":userID" => $winner]);
					$total_user_prize = round(($ticket_prize_dm / $key) * $total_user['sum_tickets']);

					$sql_win = "SELECT id, username FROM %%USERS%% WHERE id = :winnerID ;";
					$result_win = $db->selectSingle($sql_win, [":winnerID" => $winner]);

					$winner_list[] = [
						"username" => $result_win["username"],
						"prize" => $total_user_prize
					];

					if($USER['id'] == $result_win['id']) {
						$USER[$resource[921]] += $total_user_prize;
					} else {
						$db->update("UPDATE %%USERS%% SET `darkmatter` = `darkmatter` + :totalUserPrize WHERE `id` = :userID", [
							":totalUserPrize" => $total_user_prize,
							":userID" => $result_win['id'],
						]);
					}

					$db->insert("INSERT INTO %%LOTTERYLOGS%% SET 
						userId = :userID,
						time = :time_win,
						prize = :total_user_prize 
					;", [
						":userID" => $result_win['id'],
						":time_win" => TIMESTAMP,
						":total_user_prize" => $total_user_prize
					]);

					$message = sprintf($LNG['lottery_system_msg'], pretty_number($total_user_prize), $LNG['tech'][921], $config->lottery_count);
					PlayerUtil::sendMessage($winner, 0, $LNG['lottery_system_from'], 50, $LNG['lottery_system_to'], $message, TIMESTAMP);
				}

				$db->nativeQuery("TRUNCATE TABLE %%LOTTERY%% ;");

				$time = $time_att_tirage;
				$db->update("UPDATE %%CONFIG%% SET 
					`lottery_time` = :time_up, lottery_prize = :reset_gain, lottery_prize_add = :reset_gain_add, lottery_count = lottery_count + :lottery_count ;", [
						":time_up" => $time,
						":reset_gain" => mt_rand(1, 1500),
						":reset_gain_add" => mt_rand(0, 750),
						":lottery_count" => 1,
				]);

				$this->printMessage($LNG['lottery_success_tirage'], true, array('game.php?page=lottery', 2));
				die();
			} else {
				$time = $time_att_tirage;
				$db->update("UPDATE %%CONFIG%% SET `lottery_time` = :time_up ;", [
					":time_up" => $time,
				]);
		
				$this->printMessage($LNG['lottery_error_tirage'], true, array('game.php?page=lottery', 2));
				die();
			}
		} else {
			$secs = $config->lottery_time - TIMESTAMP;
		}

		/**
		* Liste pour les joueurs qui participent à la loterie
		* List for players who participate in the lottery
		*/
		$userListParticipate = [];
		$diferent_users = $db->select("SELECT DISTINCT `userId` FROM %%LOTTERY%% ;");
		if($db->rowCount($diferent_users) > 0){
			foreach($diferent_users as $userList) {
				$total_user = $db->selectSingle("SELECT SUM(tickets) as sum_tickets FROM %%LOTTERY%% WHERE `userId` = :userID ;", [":userID" => $userList['userId']]);
				
				$sql_list_part = "SELECT id, username FROM %%USERS%% WHERE id = :userID ;";
				$result_list_part = $db->selectSingle($sql_list_part, [":userID" => $userList['userId']]);

				$userListParticipate[$userList['userId']] = [
					"username" => $result_list_part['username'],
					"tickets" => $total_user['sum_tickets'],
				];
			}
		}

		/**
		* Liste pour les joueurs qui on gagner à la dernière loterie
		* List for players who won the last lottery
		*/
		$userListWinner = [];
		$result_winner = $db->select("SELECT * FROM %%LOTTERYLOGS%% ORDER BY `time` DESC LIMIT 5");
		if($db->rowCount($result_winner) > 0){
			foreach($result_winner as $userWinner) {

				$sql_list_win = "SELECT id, username FROM %%USERS%% WHERE id = :userID ;";
				$result_list_win = $db->selectSingle($sql_list_win, [":userID" => $userWinner['userId']]);

				$userListWinner[] = [
					"username" => $result_list_win['username'],
					"prize" => pretty_number($userWinner['prize']),
					"time_win" => _date($LNG['php_tdformat'], $userWinner['time'], $USER['timezone']),
				];
			}
		}

		$this->tplObj->assign_vars(array(
			'metal_p' => pretty_number(floattostring($ticket_price_metal)),
			'crystal_p' => pretty_number(floattostring($ticket_price_crystal)),
			'deuterium_p' => pretty_number(floattostring($ticket_price_deuterium)),
			'secs'		=> $secs,
			'user_lists' => $userListParticipate,
			'max_tickets_per_player' => $max_users_tickets,
			'winners_lists'	=> $userListWinner,
			'lottery_actif' => $this->verifActifLoterie(),
			'max_vente_tikets' => ($result_controle['totals_vente'] >= $max_lottery_tickets) ? true : false,
			'max_vente_tikets_nb' => $count_tickets_restant,
			'lng_lotery_header' => sprintf($LNG["lotery_header"], pretty_number($config->lottery_count)),
			'lng_lottery_prize_content' => sprintf($LNG['lottery_prize_content'], pretty_number($ticket_prize_dm), $LNG['tech'][921]),
			'lng_lottery_prize_content_suite' => sprintf($LNG['lottery_prize_content_suite'], $config->lottery_min, $config->lottery_min, $lottery_prize_add, $LNG['tech'][921]),
			'lng_lottery_list_win' => sprintf($LNG['lottery_list_win'], pretty_number($config->lottery_count - 1)),
		));

		$this->display('page.lottery.default.tpl');
	}
}