{include file="overall_header.tpl"}

<!--
 * @mods Loterie
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 -->
 
         <!-- ADDON Loterie -->
        <table width="70%" cellpadding="2" cellspacing="2">
            <tr>
                <th colspan="2">{$LNG.lottery_adm_title}</th><th>&nbsp;</th>
            </tr>
            <tr>
                <td colspan="3" style="color: red; font-weight: bold;">{$LNG.lottery_adm_avertissement}</td>
            </tr>
            <tr>
                <td>{$LNG.lottery_adm_actif}<br></td>
                <td><input name="lottery_actif"{if $lottery_actif} checked="checked"{/if}  type="checkbox"></td>
                <td><img src="./styles/resource/images/admin/i.gif" width="16" height="16" alt="" class="tooltip" data-tooltip-content="{$LNG.lottery_adm_actif_desc}"></td>
            </tr>
            <tr>
                <td>{$LNG.lottery_adm_time_start}</td>
                <td>
                    <input type="datetime-local" name="lottery_actif_att_time"
                        pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}">
                </td>
                <td><img src="./styles/resource/images/admin/i.gif" width="16" height="16" alt="" class="tooltip" data-tooltip-content="{$LNG.lottery_adm_time_start_desc}"></td>
            </tr>
            <tr>
                <td>{$LNG.lottery_adm_metal} {$LNG.tech.901}</td>
                <td><input name="lottery_ticket_prize_metal" size="60" value="{$lottery_ticket_prize_metal}" type="text"></td>
                <td><img src="./styles/resource/images/admin/i.gif" width="16" height="16" alt="" class="tooltip" data-tooltip-content="{$LNG.lottery_adm_metal_desc}"></td>
            </tr>
            <tr>
                <td>{$LNG.lottery_adm_crystal} {$LNG.tech.902}</td>
                <td><input name="lottery_ticket_prize_crystal" size="60" value="{$lottery_ticket_prize_crystal}" type="text"></td>
                <td><img src="./styles/resource/images/admin/i.gif" width="16" height="16" alt="" class="tooltip" data-tooltip-content="{$LNG.lottery_adm_crystal_desc}"></td>
            </tr>
            <tr>
                <td>{$LNG.lottery_adm_deuterium} {$LNG.tech.903}</td>
                <td><input name="lottery_ticket_prize_deuterium" size="60" value="{$lottery_ticket_prize_deuterium}" type="text"></td>
                <td><img src="./styles/resource/images/admin/i.gif" width="16" height="16" alt="" class="tooltip" data-tooltip-content="{$LNG.lottery_adm_deuterium_desc}"></td>
            </tr>
            <tr>
                <td>{$LNG.lottery_adm_max_user_tickets}</td>
                <td><input name="lottery_max_users_tickets" maxlength="3" size="60" value="{$lottery_max_users_tickets}" type="text"></td>
                <td><img src="./styles/resource/images/admin/i.gif" width="16" height="16" alt="" class="tooltip" data-tooltip-content="{$LNG.lottery_adm_max_user_tickets_desc}"></td>
            </tr>
            <tr>
                <td>{$LNG.lottery_adm_max_tickets}</td>
                <td><input name="lottery_max_tickets" maxlength="3" size="60" value="{$lottery_max_tickets}" type="text"></td>
                <td><img src="./styles/resource/images/admin/i.gif" width="16" height="16" alt="" class="tooltip" data-tooltip-content="{$LNG.lottery_adm_max_tickets_desc}"></td>
            </tr>
            <tr>
                <td>{$LNG.lottery_adm_max_user_win}</td>
                <td><input name="lottery_max_users_winner" maxlength="3" size="60" value="{$lottery_max_users_winner}" type="text"></td>
                <td><img src="./styles/resource/images/admin/i.gif" width="16" height="16" alt="" class="tooltip" data-tooltip-content="{$LNG.lottery_adm_max_user_win_desc}"></td>
            </tr>
            <tr>
                <td>{$LNG.lottery_adm_min_user_tirage}</td>
                <td><input name="lottery_min" maxlength="3" size="60" value="{$lottery_min}" type="text"></td>
                <td><img src="./styles/resource/images/admin/i.gif" width="16" height="16" alt="" class="tooltip" data-tooltip-content="{$LNG.lottery_adm_min_user_tirage_desc}"></td>
            </tr>
            <tr>
                <td>{$LNG.lottery_adm_prize}</td>
                <td><input name="lottery_prize" maxlength="3" size="60" value="{$lottery_prize}" type="text"></td>
                <td><img src="./styles/resource/images/admin/i.gif" width="16" height="16" alt="" class="tooltip" data-tooltip-content="{$LNG.lottery_adm_prize_desc}"></td>
            </tr>
            <tr>
                <td>{$LNG.lottery_adm_prize_addon_reset}</td>
                <td><input name="lottery_prize_add" maxlength="3" size="60" value="{$lottery_prize_add}" type="text"></td>
                <td><img src="./styles/resource/images/admin/i.gif" width="16" height="16" alt="" class="tooltip" data-tooltip-content="{$LNG.lottery_adm_prize_addon_reset_desc}"></td>
            </tr>
            <tr>
                <td colspan="3"><input value="{$LNG.se_save_parameters}" type="submit"></td>
            </tr>
        </table>
{include file="overall_footer.tpl"}