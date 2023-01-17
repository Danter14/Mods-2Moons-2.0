<?php

/**
 * @mods Bank
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
            // ADDON Bank
            'commision_bank'                    => $config->commision_bank,
        );

        $expedition_limit_res_active 				= isset($_POST['expedition_limit_res_active']) && $_POST['expedition_limit_res_active'] == 'on' ? 1 : 0;

        $expedition_limit_res				= HTTP::_GP('expedition_limit_res', 0);
        
        // ADDON Bank
        $commision_bank				        = HTTP::_GP('commision_bank', 0);

        if($commision_bank > 100) {
            $commision_bank = 100;
        }

        $config_after = array(
            'expedition_limit_res'				=> $expedition_limit_res,
            'expedition_limit_res_active'		=> $expedition_limit_res_active,
            // ADDON Bank
            'commision_bank'                    => $commision_bank,
        );

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
        'expedition_limit_res'					=> $config->expedition_limit_res,
        'expedition_limit_res_active'			=> $config->expedition_limit_res_active,
        // ADDON Bank
        'commision_bank'				        => $config->commision_bank,
    ));

    $template->show('ConfigModsBody.tpl');
}