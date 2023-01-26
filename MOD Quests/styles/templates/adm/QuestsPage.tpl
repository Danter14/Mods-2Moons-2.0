{include file="overall_header.tpl"}
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
        </div>
        <div class="onglet_content">
            <h1>Créer une catégorie</h1>
            <form id="addCategorie" method="post">
                <input type="hidden" name="action" value="add_categorie">
                <input type="number" name="categorie_add">
                <button type="submit">Créer la catégorie</button>
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
{include file="overall_footer.tpl"}