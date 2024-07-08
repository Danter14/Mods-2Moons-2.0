{block name="title" prepend}Voucher{/block}
{block content}
<link rel="stylesheet" type="text/css" href="{$dpath}css/voucher.css">

<div id="page">
    <div id="content">
        <div id="voucher-container" class="conteiner">
            <div class="gray_stripe">
                Voucher Code
            </div>
            <div class="voucher-form pt-2">
                <div>
                    <img src="https://cdn-icons-png.flaticon.com/256/6384/6384145.png" style="width: 50px; margin-right: 10px;" /> Enter your voucher code here:
                </div>
                <form id="form-voucher" method="POST">
                    <div><input type="text" name="voucher" value="" /></div>
                    <div>
                        <button type="submit">USE CODE</button>
                    </div>
                </form>
            </div>

            <div class="pt-2">
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
                            </div>
                            <div class="content-card-rewards">
                                {foreach from=$reward.rewards key=key item=reward}
                                    <div>
                                        {if $key >= 901 && $key <= 999}
                                            <img class="tooltip" src="{$dpath}img/resources/{$key}f.png" alt="{$LNG.tech.{$key}}" data-tooltip-content="{$LNG.tech.{$key}}">
                                        {else}
                                            <img class="tooltip" src="{$dpath}gebaeude/{$key}.gif" alt="{$LNG.tech.{$key}}" data-tooltip-content="{$LNG.tech.{$key}}" style="margin-right: 10px;">
                                        {/if}
                                        {$reward|number}
                                    </div>
                                {foreachelse}
                                    <div>
                                        Vous n'avaz jamais utilisé de code
                                    </div>
                                {/foreach}
                            </div>
                            <div class="content-card-time"><em>{$reward.use_time}</em></div>
                        </div>
                    </div>
                    {/foreach}
                </div>
            </div>
        </div>
    </div>
</div>
{/block}
