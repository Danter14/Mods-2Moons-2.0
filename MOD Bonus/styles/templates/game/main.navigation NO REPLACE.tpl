{if $bonus_att == 0}
	<a href="game.php?page=bonus">
		<div class="menu_content_full">
			Bonus
		</div>
	</a>
{else}
	<div class="menu_content_full">
		After Bonus : <br>{$bonus_att}
	</div>
{/if}