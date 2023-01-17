{block name="title" prepend}{$LNG.boutique_title}{/block}
{block name="content"}

<!--
 * @mods Shop
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 -->

<div class="content_page">
	<div class="title">
		{$LNG.boutique_title}
	</div>

	<div>
		<form action="?page=boutique" method="POST">
			<table style="width: 100%;">
				<tr>
					<td>{$LNG.boutique_title_reduc_fleet}</td>
					<td><span id="prize_reduc">{$prize_base|number}</span> {$LNG.tech.921}</td>
					<td style="text-align: right;">
						<input name="time_reduc" min="0" type="number" value="0" onchange="myFunction({$prize_base})" onkeyup="myFunction({$prize_base})" />
						<button name="action" value="bonus_fleet_reduc_time">{$LNG.boutique_btn_reduc_fleet}</button>
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>

<script>
	function myFunction(prize_base) {
		var valueReduc = $('input[name="time_reduc"]').val();
		var prizeReduc = Math.floor(prize_base * (1 + valueReduc / 100));
		document.getElementById("prize_reduc").innerHTML = number_format(prizeReduc);
	}
</script>

{/block}