<?php

/**
 * @mods Loterie
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 */

// Mods Lottery FR
$LNG['lottery_title_page'] = "Loterie";
$LNG['lottery_att_title'] = "Loterie";
$LNG['lottery_att_content'] = "Prochaine événement de la loterie";
$LNG['lottery_att_date_empty'] = "Aucune date";
$LNG['lottery_att_date'] = "Le prochain évènement de notre loterie n°<span class='color-orange'>%s</span> est prévu le : <span class='color-orange'>%s</span>";
$LNG['lotery_header'] = "Loterie n°<span class='color-orange'>%s</span>";
$LNG['lottery_title'] = "Prochain tirage";
$LNG['lottery_btn'] = "Acheté";
$LNG['lottery_ticket_vendu'] = "Tous les tickets on était vendu";
$LNG['lottery_prize_ticket_one'] = "Prix pour <strong>1</strong> seul ticket";
$LNG['lottery_prize'] = "Gain :";
$LNG['lottery_prize_content'] = "Le gagnant recevra <span class='color-mn'>%s %s x nombre de ticket acheté</span>";
$LNG['lottery_prize_content_suite'] = "Il est nécessaire d'avoir au moins <span class='color-min'>%s joueurs</span>, si le temps est écoulé et le système n'a pas un minimum de <span class='color-min'>%s joueurs</span>, alors le système va réinitialiser le compteur, le gain augmentera donc de <span class='color-min'>%s %s</span> à chaque report.";
$LNG['lottery_max_ticket_user'] = "Un joueur peut acheter au maximum";
$LNG['lottery_ticket'] = "Tickets";
$LNG['lottery_max_ticket'] = "Il reste actuellement pour cette loterie";
$LNG['lottery_list_participe'] = "Participants à la loterie";
$LNG['lottery_list_participant_empty'] = "Aucun joueur n'a actuellement acheter de ticket";
$LNG['lottery_list_win'] = "Dernier Gagnants à la loterie n°%s";
$LNG['lottery_list_win_empty'] = "Il n'y a pas encore eu de gagnant";
$LNG['lottery_ticket'] = "Tickets";
$LNG['lottery_error_ticket_solde_out'] = "Tous les tickets on était vendu merci d'attendre la prochaine loterie.";
$LNG['lottery_error_ticket_num'] = "Désolé mais vous demander trop de ticket !!!";
$LNG['lottery_error_ticket_buy'] = "Vous pouvez encore acheter %s ticket(s) maximum.";
$LNG['lottery_error_ticket_superieur'] = "Le nombre de ticket demandé depasse les tickets restant.";
$LNG['lottery_error_tickets_add'] = "Vous n'avez pas assez de ressources pour acheter les tickets.";
$LNG['lottery_success_ticket_buy'] = "Vous avez acheté %s ticket(s).";
$LNG['lottery_error_report'] = "Loterie reporté du à l'absence de joueur qui n'ont pas participé.";
$LNG['lottery_error_conf_adm'] = "Erreur de l'administrateur dans le configuration des gains loterie reporter.";
$LNG['lottery_system_msg'] = "Bravo, vous venez de gagner %s %s à la loterie n°%s";
$LNG['lottery_system_from'] = "Loterie";
$LNG['lottery_system_to'] = "Vous avez gagné";
$LNG['lottery_success_tirage'] = "La loterie est terminé bravo aux vainqueurs.";
$LNG['lottery_error_tirage'] = "Pas de tickets acheté pour la loterie, redémarrage de la loterie.";

// Mods Loterry FR ADMIN
$LNG['lottery_adm_title'] = "Loterie";
$LNG['lottery_adm_avertissement'] = "Attention: pour les configurations des ticket Max disponibles et le nombre de joueurs requis pour le tirage faite en sorte d'avoir asser de ticket disponible pour avec le tirage.";
$LNG['lottery_adm_actif'] = "Activer la loterie";
$LNG['lottery_adm_actif_desc'] = "Si vous activer la loterie directement sans passer par la date d'attente il pouront avec accès aussitot, si vous la désactiver alors qu'il y a pas eu de gagnant les ticket ne seront pas perdu et sera actif lors de la prochaine loterie.";
$LNG['lottery_adm_time_start'] = "Date d'activation automatique de la loterie";
$LNG['lottery_adm_time_start_desc'] = "Vous pouvez mettre une date qui activera automatique la loterie un fois la date atteinte.";
$LNG['lottery_adm_metal'] = "Coût du ticket en ";
$LNG['lottery_adm_metal_desc'] = "Prix pour l'achat d'un seul tickets";
$LNG['lottery_adm_crystal'] = "Coût du ticket en ";
$LNG['lottery_adm_crystal_desc'] = "Prix pour l'achat d'un seul tickets";
$LNG['lottery_adm_deuterium'] = "Coût du ticket en ";
$LNG['lottery_adm_deuterium_desc'] = "Prix pour l'achat d'un seul tickets";
$LNG['lottery_adm_max_user_tickets'] = "Ticket maximum par joueur";
$LNG['lottery_adm_max_user_tickets_desc'] = "Maximum de Ticket possible lors de l'achat de la loterie par joueur.";
$LNG['lottery_adm_max_tickets'] = "Ticket maximum disponible pour la loterie";
$LNG['lottery_adm_max_tickets_desc'] = "Ticket maximum qui sont disponible pour les joueurs";
$LNG['lottery_adm_max_user_win'] = "Nombre de personne gagnant";
$LNG['lottery_adm_max_user_win_desc'] = "Nombre de personne qui peuvent gagner à la loterie lors du tirage. Attention inférieur au nombre miminum de participant";
$LNG['lottery_adm_min_user_tirage'] = "Nombre minimum de joueur pour un tirage";
$LNG['lottery_adm_min_user_tirage_desc'] = "Nombre de joueur minimum pour permettre à la loterie de faire le tirage des gagnants";
$LNG['lottery_adm_prize'] = "Gain pour les joueurs";
$LNG['lottery_adm_prize_desc'] = "gain pour le premier joueur puis diminue pour les autre joueurs si plusieur vainqueurs.";
$LNG['lottery_adm_prize_addon_reset'] = "Augmentation des gains par non tirage";
$LNG['lottery_adm_prize_addon_reset_desc'] = "Augmenter les gain de la loterie si aucun tirage n'a pu se faire.";



// Mods Lottery EN
$LNG['lottery_title_page'] = "Lottery";
$LNG['lottery_att_title'] = "Lottery";
$LNG['lottery_att_content'] = "Next lottery event";
$LNG['lottery_att_date_empty'] = "No date";
$LNG['lottery_att_date'] = "The next event of our lottery no.<span class='color-orange'>%s</span> is planned on : <span class='color-orange'>%s</span>";
$LNG['lotery_header'] = "Lottery n°<span class='color-orange'>%s</span>";
$LNG['lottery_title'] = "Next draw";
$LNG['lottery_btn'] = "Buy";
$LNG['lottery_ticket_vendu'] = "All the tickets we were sold";
$LNG['lottery_prize_ticket_one'] = "Price for <strong>1</strong> single ticket";
$LNG['lottery_prize'] = "Gain :";
$LNG['lottery_prize_content'] = "The winner will receive <span class='color-mn'>%s %s x number of tickets purchased</span>";
$LNG['lottery_prize_content_suite'] = "It is necessary to have at least <span class='color-min'>%s players</span>, if the time is up and the system does not have a minimum of <span class='color-min'>%s players</span>, then the system will reset the counter, so the gain will increase by <span class= 'color-min'>%s %s</span> on each report.";
$LNG['lottery_max_ticket_user'] = "A player can buy a maximum";
$LNG['lottery_ticket'] = "Tickets";
$LNG['lottery_max_ticket'] = "He currently remains for this lottery";
$LNG['lottery_list_participe'] = "Lottery entrants";
$LNG['lottery_list_participant_empty'] = "No player has currently purchased a ticket";
$LNG['lottery_list_win'] = "Latest Lottery Winners No.%s";
$LNG['lottery_list_win_empty'] = "There hasn't been a winner yet";
$LNG['lottery_ticket'] = "Tickets";
$LNG['lottery_error_ticket_solde_out'] = "All the tickets we were sold please wait for the next lottery.";
$LNG['lottery_error_ticket_num'] = "Sorry but asking you too many tickets !!!";
$LNG['lottery_error_ticket_buy'] = "You can still buy %s ticket(s) maximum.";
$LNG['lottery_error_ticket_superieur'] = "The number of tickets requested exceeds the remaining tickets.";
$LNG['lottery_error_tickets_add'] = "You don't have enough resources to buy the tickets.";
$LNG['lottery_success_ticket_buy'] = "You bought %s ticket(s).";
$LNG['lottery_error_report'] = "Lottery postponed due to the absence of players who did not participate.";
$LNG['lottery_error_conf_adm'] = "Admin error in configuring lottery winnings reporter.";
$LNG['lottery_system_msg'] = "Congratulations, you have just won %s %s in lottery n°%s";
$LNG['lottery_system_from'] = "Lottery";
$LNG['lottery_system_to'] = "You have won";
$LNG['lottery_success_tirage'] = "The lottery is over congratulations to the winners.";
$LNG['lottery_error_tirage'] = "No tickets purchased for the lottery, restarting the lottery.";

// Mods Loterry EN ADMIN
$LNG['lottery_adm_title'] = "Lottery";
$LNG['lottery_adm_avertissement'] = "Attention: for the Max ticket configurations available and the number of players required for the draw, make sure to have enough tickets available for the draw.";
$LNG['lottery_adm_actif'] = "Activate the lottery";
$LNG['lottery_adm_actif_desc'] = "If you activate the lottery directly without going through the waiting date, it will be able to access it immediately, if you deactivate it when there has been no winner, the tickets will not be lost and will be active during the next lottery.";
$LNG['lottery_adm_time_start'] = "Automatic lottery activation date";
$LNG['lottery_adm_time_start_desc'] = "You can put a date that will automatically activate the lottery once the date is reached.";
$LNG['lottery_adm_metal'] = "Ticket cost in ";
$LNG['lottery_adm_metal_desc'] = "Price for the purchase of a single ticket";
$LNG['lottery_adm_crystal'] = "Ticket cost in ";
$LNG['lottery_adm_crystal_desc'] = "Price for the purchase of a single ticket";
$LNG['lottery_adm_deuterium'] = "Ticket cost in ";
$LNG['lottery_adm_deuterium_desc'] = "Price for the purchase of a single ticket";
$LNG['lottery_adm_max_user_tickets'] = "Maximum ticket per player";
$LNG['lottery_adm_max_user_tickets_desc'] = "Maximum Ticket possible when purchasing the lottery per player.";
$LNG['lottery_adm_max_tickets'] = "Maximum ticket available for the lottery";
$LNG['lottery_adm_max_tickets_desc'] = "Maximum ticket that are available to players";
$LNG['lottery_adm_max_user_win'] = "Number of winners";
$LNG['lottery_adm_max_user_win_desc'] = "Number of people who can win the lottery during the draw. Attention less than the minimum number of participants";
$LNG['lottery_adm_min_user_tirage'] = "Minimum number of players for a draw";
$LNG['lottery_adm_min_user_tirage_desc'] = "Minimum number of players to allow the lottery to draw winners";
$LNG['lottery_adm_prize'] = "Gain for players";
$LNG['lottery_adm_prize_desc'] = "gain for the first player then decreases for the other players if several winners.";
$LNG['lottery_adm_prize_addon_reset'] = "Increase in winnings by non-draw";
$LNG['lottery_adm_prize_addon_reset_desc'] = "Increase lottery winnings if no draw could be made.";