{include file="overall_header.tpl"}

<div class="content">
	<div style="font-size: 1.5rem; margin-bottom: 10px;">Edit Code - {$code}</div>
	<form action="" method="post">
		<input type="hidden" name="action" value="updateVoucher"/>
		<input type="hidden" name="id" value="{$id_voucher}"/>
		<input type="text" name="voucher" value="{$code}"/>
		<input type="datetime-local" name="start_time" value="{$start_time}"/>
		<input type="datetime-local" name="end_time" value="{if $end_time}{$end_time}{/if}"/>
		<input type="submit" value="Save"/>
	</form>
</div>

{include file="overall_footer.tpl"}
