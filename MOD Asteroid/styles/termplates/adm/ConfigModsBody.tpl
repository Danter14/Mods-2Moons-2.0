{include file="overall_header.tpl"}

<!--
 * @mods Asteroids
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 -->
 <!-- ADDON Asteroids -->
        <table width="70%" cellpadding="2" cellspacing="2">
            <tr>
                <th colspan="2">{$LNG.msg_asteroid_title}</th><th>&nbsp;</th>
            </tr>
            <tr>
                <td>{$LNG.msg_asteroid_actif}<br></td>
                <td><input name="asteroid_actif"{if $asteroid_actif} checked="checked"{/if}  type="checkbox"></td>
                <td><img src="./styles/resource/images/admin/i.gif" width="16" height="16" alt="" class="tooltip" data-tooltip-content="{$LNG.msg_asteroid_actif_desc}"></td>
            </tr>
            <tr>
                <td>{$LNG.msg_asteroid_metal} {$LNG.tech.901}</td>
                <td><input name="asteroid_metal" size="60" value="{$asteroid_metal}" type="text"></td>
                <td><img src="./styles/resource/images/admin/i.gif" width="16" height="16" alt="" class="tooltip" data-tooltip-content="{$LNG.msg_asteroid_metal_desc}"></td>
            </tr>
            <tr>
                <td>{$LNG.msg_asteroid_crystal} {$LNG.tech.902}</td>
                <td><input name="asteroid_crystal" size="60" value="{$asteroid_crystal}" type="text"></td>
                <td><img src="./styles/resource/images/admin/i.gif" width="16" height="16" alt="" class="tooltip" data-tooltip-content="{$LNG.msg_asteroid_crystal_desc}"></td>
            </tr>
            <tr>
                <td>{$LNG.msg_asteroid_deuterium} {$LNG.tech.903}</td>
                <td><input name="asteroid_deuterium" size="60" value="{$asteroid_deuterium}" type="text"></td>
                <td><img src="./styles/resource/images/admin/i.gif" width="16" height="16" alt="" class="tooltip" data-tooltip-content="{$LNG.msg_asteroid_deuterium_desc}"></td>
            </tr>
            <tr>
                <td>{$LNG.msg_asteroid_max}</td>
                <td><input name="asteroid_count" maxlength="3" size="60" value="{$asteroid_count}" type="text"></td>
                <td><img src="./styles/resource/images/admin/i.gif" width="16" height="16" alt="" class="tooltip" data-tooltip-content="{$LNG.msg_asteroid_max_desc}"></td>
            </tr>
            <tr>
                <td colspan="3"><input value="{$LNG.se_save_parameters}" type="submit"></td>
            </tr>
        </table>
{include file="overall_footer.tpl"}