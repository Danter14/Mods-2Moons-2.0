{include file="overall_header.tpl"}
<style>
td{
font-size:1.2em;
}
.bgco{
background-color:#003851;
}
.pagination{
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    gap: 5px;
    margin-top: 30px;
}
.pagination a {
    font-size:1.2em;
    color:#fff;
    background-color:#003851;
    padding:5px 10px;
    margin-right:5px;
}
.pagination .current-page, .pagination .att-page {
    font-size:1.2em;
    color:#fff;
    padding:5px 10px;
    margin-right:5px;
}

.pagination .current-page {
    background-color:#00510B;
}

.pagination .att-page {
    background-color:#4A4C4B;
}
.search-bar {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    gap: 5px;
    margin-top: 30px;
    margin-bottom: 30px;
}
</style>
<div class="content_page">
	<div class="title" style="font-size:1.2em">
		Rapid fire editor
	</div>

	<table border='1' width="100%">
		<tr>
		<th colspan="2">
            <font color="red" style="font-size:1.3em;color:#9BC800;">
                <pre>
                    caution!
                    1. You can delete the current row or modify the amount of Rapid fire.
                    2. When saving a new rapid fire, it is displayed in order.
                    3. Automatically stop on further saves with the same row incorrectly.
                </pre>
            </font>
		</th>
		</tr>
		<tr>
			<td><form action="?page=edit-fire&mode=clear" method="post">	
				After all operations are complete, please clear the cache.
			</td>
			<td><input type="submit" value="Clear Cache" style="display:block;float:right;background-color: #003F5B;width:120px;color:#fff;height:30px"></td>
			</form>
		</tr>
	</table>
    <div class="search-bar">
        <form action="?page=edit-fire" method="POST">
            <input type="number" name="search" placeholder="Recherche id...">
            <input type="submit" value="Rechercher">
        </form>
    </div>
	<table border='1' width="100%">
	<tr>
		<td class="bgco" height="30px">Fleet/Defense</td>
		<td class="bgco">Rapid fire item</td>
		<td class="bgco">Rapid fire</td>
		<td class="bgco">Save</td>
	</tr>
		<form action="?page=edit-fire&mode=add" method="post">
	<tr>	
		<td>
			{html_options name=elemID options=$short style='font-size:1.0em;color:#58FA58'}
		</td>	
		<td>
			{html_options name=rapID options=$short style='font-size:1.0em;color:#EE00E6'}
		</td>	
		<td>
			<input style="width:60px; color:#FC6;font-size:1.3em" name="shoots" type="number" min="1" onchange="KeyUpBuy('');" onkeyup="KeyUpBuy('');" value="1">
		</td>
		<td>
			<input style="background-color: #48A34B;width:120px;color:#fff;font-size:1.1em;display:block; margin:0 auto; padding:3px 15px;" type="submit" value="Add Save">
		</td>
	</tr>
	</form>	
	</table> 
	<table border='1' width="100%">
	<tr>
		<td colspan="6" height="30px">Rapid Fire in the database</td>
	</tr>
	<tr>
		<td class="bgco">No.</td>
		<td class="bgco">Delete</td>
		<td class="bgco" height="30px">Fleet/Defense</td>
		<td class="bgco">Rapid fire item</td>
		<td class="bgco">Rapid fire</td>
		<td class="bgco">Edit Rapid fire</td>
	</tr>
	{foreach $fleet_i as $row}
	<tr>
		<td width="30px">#{$row@iteration + $offset}</td>
		<td>
		<form action="?page=edit-fire&mode=del&elemID={$row.elementID}&rapID={$row.rapidfireID}" method="post">
			<input onclick="return confirm('Delete the rows of items {$row.elementID} and {$row.rapidfireID}?');" type="submit" value="Delete" style="font-size:0.9em;color:red">
		</form>
		</td>	
		<td>({$row.elementID})
			<a href="#" style="color:#58FA58;padding-left: 3px;" onclick="return Dialog.info({$row.elementID})">{$LNG.tech.{$row.elementID}}</a></td>
		<td>({$row.rapidfireID})
			<a href="#" style="color:#EE00E6;padding-left: 3px;" onclick="return Dialog.info({$row.rapidfireID})">{$LNG.tech.{$row.rapidfireID}}</a></td>
		<td><span style="color:#FC6;">{$row.shoots}</span></td>
		<td>
		<form action="?page=edit-fire&mode=edit&elemID={$row.elementID}&rapID={$row.rapidfireID}" method="post">
			<input style="font-size:1.3em;width:50px;height:20px" name="newshoots" type="number" min="1" onchange="KeyUpBuy('');" onkeyup="KeyUpBuy('');" value="{$row.shoots}">
			<input onclick="return confirm('Modify the rows of items {$row.elementID} and {$row.rapidfireID}?');" style="font-size:1.0em;background-color: #003F5B;width:120px;color:#fff;" type="submit" value="Modify">
		</form>
		</td>
	</tr>
    {foreachelse}
        <tr>
            <td colspan="6" height="30px">No results</td>
        </tr>
	{/foreach}
	</table>
    <div class="pagination">
            {if $currentPage > 1}
                <a href="?page=edit-fire&pagination=1">1</a>
                {if $currentPage > 4}
                    <span class="att-page">...</span>
                {/if}
            {else}
                <span class="current-page">{$currentPage}</span>
            {/if}

            {for $page=max(2, $currentPage-3) to min($currentPage+3, $totalPages-1)}
                {if $page == $currentPage}
                    <span class="current-page">{$page}</span>
                {else}
                    <a href="?page=edit-fire&pagination={$page}">{$page}</a>
                {/if}
            {/for}

            {if $currentPage < $totalPages}
                {if $currentPage < $totalPages - 3}
                    <span class="att-page">...</span>
                {/if}
                <a style="font-size:1.2em;color:#fff;background-color:#003851;padding:5px 10px;margin-right:5px;" href="?page=edit-fire&pagination={$totalPages}">{$totalPages}</a>
            {else}
                <span class="current-page">{$currentPage}</span>
            {/if}
    </div>
</div>
{include file="overall_footer.tpl"}