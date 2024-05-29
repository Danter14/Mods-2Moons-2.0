{block name="title" prepend}Quêtes{/block}
{block name="content"}
<div id="page">
    <div id="content">
        <div class="content_page">
            <div class="quest-title">
                Listes des quêtes
            </div>
            <div class="quests">
                <div class="categories">
                    <ul id="onglets">
                        {foreach from=$result_cat_list item=categories}
                            <li value="{$categories.id_cat}">{$categories.name_cat}</li>
                        {/foreach}
                    </ul>
                </div>

                <div class="quest_content" id="contents"></div>
            </div>
        </div>
    </div>
</div>
{/block}