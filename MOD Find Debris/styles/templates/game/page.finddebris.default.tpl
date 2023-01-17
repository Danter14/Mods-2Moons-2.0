{block name="title" prepend}Find Debris{/block}
{block name="content"}

<!--
 * @mods Finds debris
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 -->

<style>
	.center {
	margin-left: auto;
	margin-right: auto;
	}
</style>

<div class="content_page">
	<div class="title">
		Find Debris
	</div>

	<div>
		<table>
			<tbody>
				<tr>
					<td><a href="?page=findDebris&y=1">Show Debris(range : {$range})</a></td>
				</tr>
				{$debris}
			</tbody>
			<table width="100%" cellpadding="0" cellspacing="1">
				<tr style="display: none;" id="fleetstatusrow">
					<th colspan="6">Fleet...</th>
				</tr>
			</table>
		</table>
	</div>
</div>

<script type="text/javascript">
	MaxFleetSetting = {$user_maxfleetsettings};
</script>

<script>
	function doit(missionID, planetID) {
		$.getJSON("game.php?page=fleetAjax&ajax=1&mission="+missionID+"&planetID="+planetID, function(data)
		{
			$('#slots').text(data.slots);
			if(typeof data.ships !== "undefined")
			{
				$.each(data.ships, function(elementID, value) {
					$('#elementID'+elementID).text(number_format(value));
				});
			}
			
			var statustable	= $('#fleetstatusrow');
			var messages	= statustable.find("~tr");
			if(messages.length == MaxFleetSetting) {
				messages.filter(':last').remove();
			}
			var element		= $('<td />').attr('colspan', 8).attr('class', data.code == 600 ? "success" : "error").text(data.mess).wrap('<tr />').parent();
			statustable.removeAttr('style').after(element);
		});
	}
</script>
{/block}