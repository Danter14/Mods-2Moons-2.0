{block name="title" prepend}{$LNG.header_bank}{/block}
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
		{$LNG.bank_dispo}
	</div>

	<div>
		<table class="table519">
			<tr>
				<td>{$LNG.tech.901}</td>
				<td>{$bank_metal|number}</td>
			</tr>
			<tr>
				<td>{$LNG.tech.902}</td>
				<td>{$bank_cristal|number}</td>
			</tr>
			<tr>
				<td>{$LNG.tech.903}</td>
				<td>{$bank_deuterium|number}</td>
			</tr>
			<tr>
				<td>{$LNG.tech.921}</td>
				<td>{$bank_darkmatter|number}</td>
			</tr>
		</table>
	</div>
</div>

<div class="content_page" style="margin-top: 10px;">
	<div class="title">
		{$LNG.bank_depot}
	</div>

	<div>
		<form action="game.php?page=bank&mode=updateBank" method="POST">
			<table class="table519">
				<tr>
					<td>{$LNG.tech.901}</td>
					<td>{$LNG.tech.902}</th>
					<td>{$LNG.tech.903}</th>
					<td>{$LNG.tech.921}</th>
				</tr>
				<tr>
					<td><input type="number" name="depot_metal" value="0"></td>
					<td><input type="number" name="depot_cristal" value="0"></td>
					<td><input type="number" name="depot_deuterium" value="0"></td>
					<td><input type="number" name="depot_darkmatter" value="0"></td>
				</tr>
				<tr>
					<td colspan="4"><input type="submit" value="{$LNG.bank_btn_depot}"></td>
				</tr>
				<tr>
					<td colspan="4">{$commision_bank}%</td>
				</tr>
				<tr>
					<td colspan="4" style="color: #C70039; font-weight: bold;">{$bank_commision_exoneration}</td>
				</tr>
				<tr>
					<td colspan="4">{$dernier_depot}</td>
				</tr>
			</table>
		</form>
	</div>
</div>

<div class="content_page" style="margin-top: 10px;">
	<div class="title">
		{$LNG.bank_debit}
	</div>

	<div>
		<form action="game.php?page=bank&mode=debitBank" method="POST">
			<table class="table519">
				<tr>
					<td>{$LNG.tech.901}</td>
					<td>{$LNG.tech.902}</th>
					<td>{$LNG.tech.903}</th>
					<td>{$LNG.tech.921}</th>
				</tr>
				<tr>
					
					<td><input type="number" name="debit_metal" value="0"></td>
					<td><input type="number" name="debit_cristal" value="0"></td>
					<td><input type="number" name="debit_deuterium" value="0"></td>
					<td><input type="number" name="debit_darkmatter" value="0"></td>
				</tr>
				<tr>
					<td colspan="4"><input type="submit" value="{$LNG.bank_btn_debit}"></td>
				</tr>
				<tr>
					<td colspan="4">{$LNG.bank_desc_retrait}</td>
				</tr>
				<tr>
					<td colspan="4">{$dernier_retrait}</td>
				</tr>
			</table>
		</form>
	</div>
</div>
{/block}