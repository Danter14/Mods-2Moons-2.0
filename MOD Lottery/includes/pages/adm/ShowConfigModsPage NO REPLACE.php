<?php

/**
 * @mods Loterie
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 */

if (!allowedTo(str_replace(array(dirname(__FILE__), '\\', '/', '.php'), '', __FILE__))) throw new Exception("Permission error!");

function ShowConfigModsPage()
{
    global $LNG;
    $config = Config::get(Universe::getEmulated());

    if (!empty($_POST))
    {
        $config_before = array(
            'expedition_limit_res'				=> $config->expedition_limit_res,
            'expedition_limit_res_active'		=> $config->expedition_limit_res_active,
            // ADDON MOD Loterie
            'lottery_actif'                     => $config->lottery_actif,
            'lottery_actif_att_time'            => $config->lottery_actif_att_time,
            'lottery_ticket_prize_metal'        => $config->lottery_ticket_prize_metal,
            'lottery_ticket_prize_crystal'      => $config->lottery_ticket_prize_crystal,
            'lottery_ticket_prize_deuterium'    => $config->lottery_ticket_prize_deuterium,
            'lottery_max_users_tickets'         => $config->lottery_max_users_tickets,
            'lottery_max_tickets'               => $config->lottery_max_tickets,
            'lottery_max_users_winner'          => $config->lottery_max_users_winner,
            'lottery_min'                       => $config->lottery_min,
            'lottery_prize'                     => $config->lottery_prize,
            'lottery_prize_add'                 => $config->lottery_prize_add,
        );

        $expedition_limit_res_active 		= isset($_POST['expedition_limit_res_active']) && $_POST['expedition_limit_res_active'] == 'on' ? 1 : 0;

        $expedition_limit_res				= HTTP::_GP('expedition_limit_res', 0);

        /**
         * ADDON MOD Loterie
         */
        $lottery_actif 		                    = isset($_POST['lottery_actif']) && $_POST['lottery_actif'] == 'on' ? 1 : 0;
        $lottery_actif_att_time			        = HTTP::_GP('lottery_actif_att_time', "");
        $lottery_ticket_prize_metal			    = HTTP::_GP('lottery_ticket_prize_metal', "");
        $lottery_ticket_prize_crystal			= HTTP::_GP('lottery_ticket_prize_crystal', "");
        $lottery_ticket_prize_deuterium			= HTTP::_GP('lottery_ticket_prize_deuterium', "");
        $lottery_max_users_tickets			    = HTTP::_GP('lottery_max_users_tickets', 0);
        $lottery_max_tickets			        = HTTP::_GP('lottery_max_tickets', 0);
        $lottery_max_users_winner			    = HTTP::_GP('lottery_max_users_winner', 0);
        $lottery_min			                = HTTP::_GP('lottery_min', 0);
        $lottery_prize			                = HTTP::_GP('lottery_prize', 0);
        $lottery_prize_add			            = HTTP::_GP('lottery_prize_add', 0);

        if(!strtotime($lottery_actif_att_time)) {
            $lottery_actif_att_time = 0;
        } else {
            $lottery_actif_att_time = strtotime($lottery_actif_att_time);
        }

        $config_after = array(
            'expedition_limit_res'				=> $expedition_limit_res,
            'expedition_limit_res_active'		=> $expedition_limit_res_active,
            // ADDON MOD Loterie
            'lottery_actif'                     => $lottery_actif,
            'lottery_actif_att_time'            => $lottery_actif_att_time,
            'lottery_ticket_prize_metal'        => $lottery_ticket_prize_metal,
            'lottery_ticket_prize_crystal'      => $lottery_ticket_prize_crystal,
            'lottery_ticket_prize_deuterium'    => $lottery_ticket_prize_deuterium,
            'lottery_max_users_tickets'         => $lottery_max_users_tickets,
            'lottery_max_tickets'               => $lottery_max_tickets,
            'lottery_max_users_winner'          => $lottery_max_users_winner,
            'lottery_min'                       => $lottery_min,
            'lottery_prize'                     => $lottery_prize,
            'lottery_prize_add'                 => $lottery_prize_add,
        );

       // ADDON MOD Loterie
       if(!$lottery_actif) {
            $config_after["lottery_time"] = 0;
        }

        foreach($config_after as $key => $value)
        {
            $config->$key	= $value;
        }
        $config->save();

        $LOG = new Log(3);
        $LOG->target = 0;
        $LOG->old = $config_before;
        $LOG->new = $config_after;
        $LOG->save();
    }

    $template	= new template();

    $template->assign_vars(array(
        'expedition_limit_res'				=> $config->expedition_limit_res,
        'expedition_limit_res_active'		=> $config->expedition_limit_res_active,
        // ADDON MOD Loterie
        'lottery_actif'                     => $config->lottery_actif,
        'lottery_actif_att_time'            => $config->lottery_actif_att_time,
        'lottery_ticket_prize_metal'        => $config->lottery_ticket_prize_metal,
        'lottery_ticket_prize_crystal'      => $config->lottery_ticket_prize_crystal,
        'lottery_ticket_prize_deuterium'    => $config->lottery_ticket_prize_deuterium,
        'lottery_max_users_tickets'         => $config->lottery_max_users_tickets,
        'lottery_max_tickets'               => $config->lottery_max_tickets,
        'lottery_max_users_winner'          => $config->lottery_max_users_winner,
        'lottery_min'                       => $config->lottery_min,
        'lottery_prize'                     => $config->lottery_prize,
        'lottery_prize_add'                 => $config->lottery_prize_add,
    ));

    $template->show('ConfigModsBody.tpl');
}