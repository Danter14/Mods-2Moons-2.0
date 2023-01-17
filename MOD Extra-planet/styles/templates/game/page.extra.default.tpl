{block name="title" prepend}Extra Planet{/block}
{block name="content"}

<!--
 * @mods Extra Planet
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 -->

<div class="content_page">
	<div class="title">
		{$LNG.extra_planet_title}
	</div>

	<div>
		<center>
			{if $requiredDarkMatter}
                <p>
                    {$LNG.fcm_info}
                    <br />
                    <span style="color:red; font-weight: bold;">{$requiredDarkMatter}</span>
                </p>
            {/if}
			{$content_text}
			<br />
			<form method="POST" action="?page=extraPlanet">
				<button type="submit" name="action" value="extraPlanetColo" class="button" style="height:25px;">{$extra_planet_btn_buy}!</button>
			</form>
		</center>
	</div>

	<div class="title" style="margin-top: 10px;">
		{$LNG.extra_planet_title_create}
	</div>

	<div>
		<center>
			<p>{$extra_planet_content_create}</p>
			<form method="POST" action="?page=extraPlanet">
				G : <input name="galaxy" type="number" max="{$max_galaxy}" min="1" value="1" />
				S : <input name="system" type="number" max="{$max_system}" min="1" value="1" />
				P : <input name="position" type="number" max="{$max_planets}" min="1" value="1" />
				<br />
				<button type="submit" name="action" value="extraPlanetFull" class="button" style="height:25px;">{$extra_planet_btn_buy_creted}</button>
			</form>
			<br /><br />
		</center>
	</div>
</div>
{/block}