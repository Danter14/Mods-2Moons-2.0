{block name="title" prepend}Voucher{/block}
{block content}
<style>
input {
	margin-block-end: 25px;
}
</style>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
    <div class="card mb-3">
        <h5 class="card-header">Edit Code - {$rtesult.code}</h5>
        <div class="card-body">
			<form action="" method="post">
				<input type="hidden" name="action" value="updateVoucher"/>
				<input type="hidden" name="id" value="{$result.id_voucher}"/>

				<label for="voucher">Voucher code</label>
				<input class="form-control" type="text" name="voucher" value="{$result.code}"/>

				<label for="start_time">Date de départ du code</label>
				<input class="form-control" type="datetime-local" name="start_time" value="{$result.start_time}"/>

				<label for="end_time">Date de fin du code</label>
				<input class="form-control" type="datetime-local" name="end_time" value="{if $result.end_time}{$result.end_time}{/if}"/>

				<input class="btn btn-primary" type="submit" value="Save" />
			</form>
		</div>
	</div>
</main>
{/block}
