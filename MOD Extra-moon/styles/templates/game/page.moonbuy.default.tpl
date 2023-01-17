{block name="title" prepend}Buy Moon{/block}
{block name="content"}

<!--
 * @mods Bank
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 -->

<div class="content_page">
	<div class="title">
		Moon Request Create
	</div>

	<div>
		<center>
			<img src="{$dpath}planeten/small/s_mond.jpg" width="120" height="120">
			<br /><br />
			A moon will be placed to your planet for a price of {$prize} Dark matter. 
			<br /><br />
			<form method="POST" action="?page=moonBuy">
				<button type="submit" name="action" value="createMoon" class="button" style="height:25px;">Purchase!</button>
			</form>
			<br /><br />
		</center>
	</div>
</div>
{/block}