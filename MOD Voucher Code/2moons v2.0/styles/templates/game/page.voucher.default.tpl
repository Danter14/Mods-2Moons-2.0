{block name="title" prepend}Voucher{/block}
{block content}
<style>
.content_page {
    width: 60%;
}

.content-card-reward {
    background-color: rgba(36, 42, 53, 0.6);
    border: 1px solid rgba(0, 0, 0, 0.5);
    width: 45%;
    border-radius: 5px;
    padding: 10px;
}

.content-card-reward .content-card-title {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.content-card-reward .content-card-rewards {
    display: flex;
    align-items: center;
    justify-content: space-around;
    flex-wrap: wrap;
    margin-top: 10px;
    gap: 10px;
}

.content-card-reward .content-card-rewards div {
    background-color: rgba(0, 0, 0, 0.3);
    color: #fff;
    padding: 10px;
    border-radius: 5px;
}

.content-card-reward .content-card-rewards div img {
    width: 30px;
    height: 30px;
    margin-right: 10px;
    border-radius: 50%;
}
</style>

<div class="content_page">
	<div class="title">
		Voucher Code
	</div>

	<div style="text-align: center;">
        <p>Enter your voucher code here:</p>
        <form id="form-voucher" method="POST">
            <div><input type="text" name="voucher" value="" /></div>
            <div><input type="submit" value="USE CODE" style="width: 20%;
  padding: 5px;
  margin-top: 5px;
  border-radius: 5px;" /></div>
        </form>
    </div>

    <div>
        <p style="text-align: center;">Vos dernières récompense:</p>
        <div style="display: flex;
                justify-content: space-between;
                margin: 0 10px;
                gap: 10px;
                flex-wrap: wrap;">
            {foreach from=$rewardsUse item=reward}
            <div class="content-card-reward">
                <div>
                    <div class="content-card-title">
                        <div style="font-size: 13px; font-weight: bold;">{$reward.code}</div>
                        <div><em>{$reward.use_time}</em></div>
                    </div>
                    <div class="content-card-rewards">
                        {foreach from=$reward.rewards key=key item=reward}
                            <div>
                                {if $key >= 901 && $key <= 999}
                                    <img class="tooltip" src="{$dpath}images/{$key}.gif" alt="{$LNG.tech.{$key}}" data-tooltip-content="{$LNG.tech.{$key}}">
                                {else}
                                    <img class="tooltip" src="{$dpath}gebaeude/{$key}.gif" alt="{$LNG.tech.{$key}}" data-tooltip-content="{$LNG.tech.{$key}}">
                                {/if}
                                {$reward|number}
                            </div>
                        {foreachelse}
                            <div>
                                Vous n'avaz jamais utilisé de code
                            </div>
                        {/foreach}
                    </div>
                </div>
            </div>
            {/foreach}
        </div>
    </div>
</div>
{/block}
