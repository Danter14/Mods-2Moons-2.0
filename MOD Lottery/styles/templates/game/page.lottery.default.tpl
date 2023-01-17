{block name="title" prepend}{$LNG.lottery_title_page}{/block}
{block name="content"}

<!--
 * @mods Loterie
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 -->

<link rel="stylesheet" type="text/css" href="{$dpath}lottery.css" />
{if !$lottery_actif}
<div class="container card">
	<div class="card-header">{$LNG.lottery_att_title}</div>
	<div class="card-body">
		<div class="text-ct">
			<img src="{$dpath}img/tickets.png" style="width: 150px; padding-right: 20px; margin: 0 auto;" />
		</div>
		<div>
			<p class="title_loterie">{$LNG.lottery_att_content}</p>
		</div>
		<div class="text-ct">
			<p>{$lng_lottery_att_date}</p>
		</div>
	</div>
</div>
{else}
<div class="container card">
	<div class="card-header">{$lng_lotery_header}</div>
	<div class="card-body">
		<div>
			<p class="title_loterie">{$LNG.lottery_title}</p>
		</div>
		<div class="countdown2" secs="{$secs}">
			<span>{$secs|time}</span>
		</div>
		<div class="text-ct mt-2">
			{if !$max_vente_tikets}
			<form method="POST">
				<img src="{$dpath}img/tickets.png" style="width: 80px; padding-right: 20px;" />
				<select size="1" name="tickets">
				{for $tikets=1 to 10}
					<option value="{$tikets}">{$tikets}</option>
				{/for}
				</select>
				<input type="submit" value="{$LNG.lottery_btn}" name="Buy" style="margin-left: 20px;">
			</form>
			{else}
				<img src="{$dpath}img/tickets.png" style="width: 80px;" />
				<br>
				{$LNG.lottery_ticket_vendu}
			{/if}
		</div>
		<center>
		<div class="emulated-flex-gap">
			<div>
				{$LNG.lottery_prize_ticket_one} <br><br>
				<span class="color-metal">{$metal_p} {$LNG.tech.901}</span><br>
				<span class="color-crystal">{$crystal_p} {$LNG.tech.902}</span><br>
				<span class="color-deut">{$deuterium_p} {$LNG.tech.903}</span>
			</div>
			<div>
				{$LNG.lottery_prize} 
				<br>
				<p>{$lng_lottery_prize_content}</p>
				<p>{$lng_lottery_prize_content_suite}</p>
			</div>
			<div>
				<div>{$LNG.lottery_max_ticket_user}</div>
				<div class="max-ticket">{$max_tickets_per_player}</div>
				<div class="max-ticket-text">{$LNG.lottery_ticket}</div>
			</div>
			<div>
				<div>{$LNG.lottery_max_ticket_user}</div>
				<div class="max-ticket">{$max_vente_tikets_nb}</div>
				<div class="max-ticket-text">{$LNG.lottery_ticket}</div>
				<div></div>
			</div>
		</div>
		<div class="emulated-flex-gap-2">
			<div>
				{$LNG.lottery_list_participe}
				<hr>
				{foreach $user_lists as $UserId => $userListPart}
					<p>{$userListPart.username} à acheter {$userListPart.tickets} billets</p>
				{foreachelse}
					<p>{$LNG.lottery_list_participant_empty}</p>
				{/foreach}
			</div>
			<div>
				{$lng_lottery_list_win}
				<hr>
				{foreach $winners_lists as $UserId => $userListWin}
					<p>{$userListWin.username} - <span class="color-mn">{$userListWin.prize} {$LNG.tech.921}</span> - <em>{$userListWin.time_win}</em></p>
				{foreachelse}
					<p>{$LNG.lottery_list_win_empty}</p>
				{/foreach}
			</div>
		</div>
	</center>
	</div>
</div>
{/if}
{/block}