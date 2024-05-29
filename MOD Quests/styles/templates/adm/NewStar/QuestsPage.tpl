{include file="main.header.tpl"}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
<link rel="stylesheet" type="text/css" href="./styles/resource/css/admin/quest.css">
<script type="text/javascript" src="./scripts/admin/quest.js"></script>

<center>
<div id="panneau">
    <ul id="onglets" class="clearfix">
		<li class="actif_tab">Information</li>
		<li>Listes</li>
        <li>Ajouter</li>
	</ul>
    <div id="contenus">
        <div class="onglet_content">
            <h1>{$LNG.adm_quest_info_title}</h1>
            {$LNG.adm_quest_info_content}
        </div>
        <div class="onglet_content">
            <h1>Listes des catégorie</h1>
            <div>
                <table>
                    <tr class="categories_tab">
                        <th class="first">Catégorie Id</th>
                        <th>Nom</th>
                        <th class="end">Actions</th>
                    </tr>
                    {foreach from=$list_categories|@json_decode item=categorie}
                    <tr id="cat_{$categorie->id}" class="cat{$categorie->categorieID}">
                        <td>{$categorie->categorieID}</td>
                        <td>{$categorie->name}</td>
                        <td>
                            <a style="cursor: pointer;" onclick="javascript:modalCategorie({$categorie->id})">
                                <i class="fa-solid fa-pen" style="color: #237e23; padding-right: 20px;"></i>
                            </a>
                            <a onclick="javascript:deleteCategorie({$categorie->id})" style="cursor: pointer;">
                                <i class="fa-solid fa-trash" style="color: #c23934;"></i>
                            </a>
                        </td>
                    </tr>
                    {/foreach}
                </table>
            </div>
            <hr>
            <h1>Listes des quêtes</h1>
            <div>
                <table>
                    <tr>
                        <th class="first">Quête Id</th>
                        <th>Catégorie</th>
                        <th>Titre</th>
                        <th>Description</th>
                        <th>Objectif</th>
                        <th>Points Reward</th>
                        <th>{$LNG.tech.901} Reward</th>
                        <th>{$LNG.tech.902} Reward</th>
                        <th>{$LNG.tech.903} Reward</th>
                        <th>{$LNG.tech.921} Reward</th>
                        <th>Quête Actif</th>
                        <th>Créer le</th>
                        <th>Quête Event</th>
                        <th>Quête Event Expire</th>
                        <th class="end">Actions</th>
                    </tr>
                    {foreach from=$result_list_quests|@json_decode item=quete}
                    <tr id="quest_{$quete->questsID}" class="quest{$quete->questsID}">
                        <td>{$quete->questsID}</td>
                        <td>{$LNG["quest_categorie_{$quete->questsCategories}"]}</td>
                        <td>{$quete->quest_title}</td>
                        <td>{$quete->quest_description}</td>
                        <td>
                            +{$quete->quest_objectif_level|number} x {$LNG.tech.{$quete->quest_objectif}}
                        </td>
                        <td>{$quete->quest_points_reward|number}</td>
                        <td>{$quete->quest_metal_reward|number}</td>
                        <td>{$quete->quest_crystal_reward|number}</td>
                        <td>{$quete->quest_deuterium_reward|number}</td>
                        <td>{$quete->quest_darkmatter_reward|number}</td>
                        <td>
                            {if $quete->quest_actif}
                                <span data-before-text="Oui" data-before-type="green pill"></span>
                            {else}
                                <span data-before-text="Non" data-before-type="red pill"></span>
                            {/if}
                        </td>
                        <td><em>{$quete->quest_created}</em></td>
                        <td>
                            {if $quete->quest_event}
                                <span data-before-text="Oui" data-before-type="green pill"></span>
                            {else}
                                <span data-before-text="Non" data-before-type="red pill"></span>
                            {/if}
                        </td>
                        <td>
                            {if $quete->quest_time_finish_event == "0"}
                                <span data-before-text="Aucune" data-before-type="orange pill"></span>
                            {else if $quete->quest_time_finish_event == "-1"}
                                <span data-before-text="Terminer" data-before-type="red pill"></span>
                            {else}
                                {$quete->quest_time_finish_event}
                            {/if}
                        </td>
                        <td>
                            <a style="cursor: pointer;" onclick="javascript:modalQuest({$quete->questsID})">
                                <i class="fa-solid fa-pen" style="color: #237e23; padding-right: 20px;"></i>
                            </a>
                            <a onclick="javascript:deleteQuest({$quete->questsID})" style="cursor: pointer;">
                                <i class="fa-solid fa-trash" style="color: #c23934;"></i>
                            </a>
                        </td>
                    </tr>
                    {/foreach}
                </table>
            </div>
        </div>
        <div class="onglet_content">
            <h1>Créer une catégorie</h1>
            <form id="addCategorie" method="post">
                <input type="hidden" name="action" value="add_categorie">
                <input type="number" name="categorie_add">
                <button type="submit">Créer la catégorie</button>
            </form>
            <hr>
            <h1>Créer une quête</h1>
            <form id="addQuest" method="post">
                <input type="hidden" name="action" value="add_quest">
                <div class="form-control">
                    <label for="actif_quete">Quête actif ?</label>
                    <input id="actif_quete" name="quest_actif" type="checkbox" style="width: auto; margin-right: 25%;">
                </div>
                <div class="form-control" style="padding-bottom: 10px;">
                    <label>Choisir une catégorie*</label>
                    <select name="categories_id">
                        <option value="">Catégorie</option>
                    {foreach from=$list_categories|@json_decode item=categorie}
                        <option value="{$categorie->categorieID}">{$categorie->name}</option>
                    {/foreach}
                    </select>
                </div>
                <div class="form-control">
                    <label>Titre de la quête*</label>
                    <input class="input_t" type="text" name="quest_title">
                </div>
                <div class="form-control">
                    <label>Description de la quête</label>
                    <textarea name="quest_desc" style="height: 35px;"></textarea>
                </div>
                <div class="form-control">
                    <div class="form-control">
                        <label>Niveau objectif*</label>
                        <input type="text" name="quest_obj_level">
                    </div>
                    <label>x</label>
                    <select name="quest_obj">
                        <option value="">Objectif*</option>
                    {foreach from=$objectifs_list|@json_decode key=key item=objectif}
                        <option value="{$key}">{$objectif->name}</option>
                    {/foreach}
                    </select>
                </div>
                <div class="form-control">
                    <label>Points en récompense</label>
                    <input type="number" name="quest_redward_points">
                </div>
                <div class="form-control">
                    <label>Récompende de {$LNG.tech.901}</label>
                    <input type="number" name="quest_redward_metal">
                </div>
                <div class="form-control">
                    <label>Récompende de {$LNG.tech.902}</label>
                    <input type="number" name="quest_redward_crystal">
                </div>
                <div class="form-control">
                    <label>Récompende de {$LNG.tech.903}</label>
                    <input type="number" name="quest_redward_deuterium">
                </div>
                <div class="form-control">
                    <label>Récompende de {$LNG.tech.921}</label>
                    <input type="number" name="quest_redward_darkmatter">
                </div>
                <div class="form-control">
                    <label for="actif_quete_event">Quête d'évènement ?</label>
                    <input id="actif_quete_event" name="quest_event" type="checkbox" style="width: auto; margin-right: 25%;">
                </div>
                <div class="form-control">
                    <label>Date de fin de la quête évent</label>
                    <input type="datetime-local" name="quest_time_finish_event" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}">
                </div>
                <button type="submit">Créer la quête</button>
            </form>
        </div>
    </div>
</div>
</center>

<!-- Catégories Modal -->
<div class="modal_popup">
    <div class="bg_shadow"></div>
    <div class="popup">
        <div class="popup_head">
            <span id="test_cat"></span>
            <button class="close_btn" onclick="javascript:modalCategorieClose()">
                <img src="https://cdn-icons-png.flaticon.com/512/871/871552.png" width="35" />
            </button>
        </div>
        <form class="popup_form" id="editCategorie" method="post">
            <div class="popup_body">
                <input type="hidden" name="action" value="edit_categorie">
                <input type="hidden" name="id" id="hidden_cat">
                <label>Choisir la nouvelle catégorie</label>
                <br>
                <input type="number" name="catId" id="form_cat_id">
            </div>
            <div class="popup_foot">
                <button class="popup_confirm_btn" type="submit">Sauvegarder</button>
            </div>
        </form>
    </div>
</div>

<!-- Quête Modal -->
<div class="modal_popup_quest">
    <div class="bg_shadow"></div>
    <div class="popup">
        <div class="popup_head">
            <span id="title_quest"></span>
            <button class="close_btn" onclick="javascript:modalQuestClose()">
                <img src="https://cdn-icons-png.flaticon.com/512/871/871552.png" width="35" />
            </button>
        </div>
        <form class="popup_form" id="editQuest" method="post">
            <input type="hidden" name="id" id="hidden_quest">
            <div class="popup_body">
                <input type="hidden" name="action" value="edit_quest">
                <div class="form-control">
                    <label for="actif_quete_modal">Quête actif ?</label>
                    <input id="actif_quete_modal" name="quest_actif" type="checkbox" style="width: auto; margin-right: 25%;">
                </div>
                <div class="form-control" style="padding-bottom: 10px;">
                    <label>Choisir une catégorie*</label>
                    <select id="quest_categorie_modal" name="categories_id">
                        <option value="">Catégorie</option>
                    {foreach from=$list_categories|@json_decode item=categorie}
                        <option value="{$categorie->categorieID}">{$categorie->name}</option>
                    {/foreach}
                    </select>
                </div>
                <div class="form-control">
                    <label>Titre de la quête*</label>
                    <input id="quest_title" class="input_t" type="text" name="quest_title">
                </div>
                <div class="form-control">
                    <label>Description de la quête</label>
                    <textarea id="quest_description" name="quest_desc" style="height: 35px;"></textarea>
                </div>
                <div class="form-control">
                    <div class="form-control">
                        <label>Niveau objectif*</label>
                        <input id="quest_obj_level" type="text" name="quest_obj_level">
                    </div>
                    <label>x</label>
                    <select id="quest_obj_modal" name="quest_obj">
                        <option value="">Objectif*</option>
                    {foreach from=$objectifs_list|@json_decode key=key item=objectif}
                        <option value="{$key}">{$objectif->name}</option>
                    {/foreach}
                    </select>
                </div>
                <div class="form-control">
                    <label>Points en récompense</label>
                    <input id="quest_reward_point" type="number" name="quest_redward_points">
                </div>
                <div class="form-control">
                    <label>Récompende de {$LNG.tech.901}</label>
                    <input id="quest_reward_metal" type="number" name="quest_redward_metal">
                </div>
                <div class="form-control">
                    <label>Récompende de {$LNG.tech.902}</label>
                    <input id="quest_reward_crystal" type="number" name="quest_redward_crystal">
                </div>
                <div class="form-control">
                    <label>Récompende de {$LNG.tech.903}</label>
                    <input id="quest_reward_deuterium" type="number" name="quest_redward_deuterium">
                </div>
                <div class="form-control">
                    <label>Récompende de {$LNG.tech.921}</label>
                    <input id="quest_reward_darkmatter" type="number" name="quest_redward_darkmatter">
                </div>
                <div class="form-control">
                    <label for="actif_quete_event_modal">Quête d'évènement ?</label>
                    <input id="actif_quete_event_modal" name="quest_event" type="checkbox" style="width: auto; margin-right: 25%;">
                </div>
                <div class="form-control">
                    <label>Date de fin de la quête évent</label>
                    <input id="quest_event_time" type="datetime-local" name="quest_time_finish_event" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}">
                </div>
            </div>
            <div class="popup_foot">
                <button class="popup_confirm_btn" type="submit">Sauvegarder</button>
            </div>
        </form>
    </div>
</div>
{include file="main.footer.tpl"}