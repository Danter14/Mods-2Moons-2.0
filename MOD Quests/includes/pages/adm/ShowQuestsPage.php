<?php

/**
 * @mods Quests
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 */

if (!allowedTo(str_replace(array(dirname(__FILE__), '\\', '/', '.php'), '', __FILE__))) throw new Exception("Permission error!");

function ShowQuestsPage() {
    
    global $LNG, $reslist, $resource;

    // Connexion à notre base de donnée
    $db = Database::get();

    // Liste de nos action à effectuer
    $action = HTTP::_GP("action", "");

    // Affichage de nos différentes catégories
    $result_list_cat = listCategorie($db);

    // Un formulaire pour l'ajout des catégories
    if($action == "add_categorie") {
        addCategories($db, $_POST);
        die();
    }
    // Récupération des données de la catégories dans notre modal
    if($action == "modal_categorie") {
        modalCategorie($db, $_GET);
        die();
    }
    // Un formulaire pour l'édition de la catégorie
    if($action == "edit_categorie") {
        editCategories($db, $_POST);
        die();
    }
    // Suppression de la catégorie
    if($action == "delete_categorie") {
        deleteCategories($db, $_POST);
        die();
    }

    $template	= new template();

    $template->assign_vars([
        "list_categories" => $result_list_cat,
    ]);

    $template->show('QuestsPage.tpl');
}

/**
 * Fonction de la gestion d'ajout de quête
 */
function listCategorie($db) {
    global $LNG;

    $list_categories = [];

    $sql = "SELECT * FROM %%QUESTS_CATEGORIES%% ORDER BY questsCategories ;";
    $result = $db->select($sql);

    foreach($result as $response) {
        $list_categories[] = [
            'id' => $response["questsCategoriesID"],
            'categorieID' => $response["questsCategories"],
            'name' => $LNG["quest_categorie_".$response["questsCategories"]],
        ];
    }

    return json_encode($list_categories, JSON_PRETTY_PRINT);
}

/**
 * Fonction de la gestion d'ajout de catégorie
 */
function addCategories($db, $data) {
    global $LNG;
    extract($data);

    $response = [];

    if(!empty($categorie_add)) {

        $sql = "SELECT questsCategories FROM %%QUESTS_CATEGORIES%% WHERE questsCategories = :questsCategories ;";
        $result = $db->selectSingle($sql, [":questsCategories" => $categorie_add]);
        $result = $db->rowCount($result);

        if ($result > 0) {
            $response["alert"] = "danger";
            $response["message"] = "Ce n° de catégorie existe déjà, merci de vous diriger dans la liste.";
        } else {
            $sql_insert = "INSERT INTO %%QUESTS_CATEGORIES%% SET questsCategories = :questsCategories ;";
            $result_isert = $db->insert($sql_insert, [":questsCategories" => $categorie_add]);

            $sql = "SELECT * FROM %%QUESTS_CATEGORIES%% WHERE questsCategories = :questsCategories ;";
            $result = $db->selectSingle($sql, [":questsCategories" => $categorie_add]);

            $response["alert"] = "success";
            $response["message"] = "Votre catégorie avec l'id : $categorie_add à bien était ajouter, attention ajouter le dans le fichier lang.";
            $response["content"] = "<tr id='cat_".$result['questsCategoriesID']."' class='cat".$result['questsCategories']."'>
                <td>".$result['questsCategories']."</td>
                <td>".$LNG["quest_categorie_".$result["questsCategories"]]."</td>
                <td>
                    <a style='cursor: pointer;' onclick='javascript:modalQuest({$result['questsCategoriesID']})'>
                        <i class='fa-solid fa-pen' style='color: #237e23; padding-right: 20px;'></i>
                    </a>
                    <a onclick='javascript:deleteCategorie({$result['questsCategoriesID']})' style='cursor: pointer;'>
                        <i class='fa-solid fa-trash' style='color: #c23934;'></i>
                    </a>
                </td>
            </tr>";
        }
    } else {
        $response["alert"] = "danger";
        $response["message"] = "Merci de mettre un id";
    }

    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
}

function modalCategorie($db, $data) {
    global $LNG;

    extract($data);

    $response = [];

    if(empty($idCat)) {
        $response["alert"] = "danger";
        $response["message"] = "Cette id n'existe pas !!!!";
    } else {
        $sql = "SELECT * FROM %%QUESTS_CATEGORIES%% WHERE questsCategoriesID = :questsCategoriesID ;";
        $result = $db->selectSingle($sql, [":questsCategoriesID" => $idCat]);
        $result_count = $db->rowCount($result);

        if($result_count > 0) {
            $response["id"] = $result['questsCategoriesID'];
            $response["catId"] = $result['questsCategories'];
            $response["name"] = $LNG["quest_categorie_".$result["questsCategories"]];
        } else {
            $response["alert"] = "danger";
            $response["message"] = "Cette id n'existe pas !!!!";
        }
    }

    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
}

/**
 * Fonction de l'édition d'une catégorie
 */
function editCategories($db, $data) {
    extract($data);

    $response = [];

    if(empty($catId)) {
        $response["alert"] = "danger";
        $response["message"] = "Merci de renseiger un id !!!!";
    } else {
        $sql = "SELECT questsCategories FROM %%QUESTS_CATEGORIES%% WHERE questsCategories = :questsCategories ;";
        $result = $db->selectSingle($sql, [":questsCategories" => $catId]);
        $result = $db->rowCount($result);

        if($result > 0) {
            $response["alert"] = "danger";
            $response["message"] = "Ce n° de catégorie existe déjà, merci de vous diriger dans la liste.";
        } else {
            $sql_update = "UPDATE %%QUESTS_CATEGORIES%% 
                SET questsCategories = :questsCategories 
                WHERE questsCategoriesID = :questsCategoriesID 
            ;";
            $result_update = $db->update($sql_update, [
                ":questsCategories" => $catId,
                ":questsCategoriesID" => $id,
            ]);

            $response["alert"] = "success";
            $response["message"] = "Votre catégorie avec l'id : $catId à bien était éditer, attention ajouter ou modifier le dans le fichier lang.";
        }
    }

    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
}

/**
 * Fonction de la suppression d'une catégorie
 */
function deleteCategories($db, $data) {
    extract($data);

    $response = [];

    if(!empty($id)) {

        $controle_count = "SELECT questsCategoriesID FROM %%QUESTS_CATEGORIES%% WHERE questsCategoriesID = :idCat ;";
        $result_count = $db->selectSingle($controle_count, [":idCat" => $id]);
        $result_count = $db->rowCount($controle_count);

        if ($result_count < 0 || empty($result_count)) {
            $response["alert"] = "danger";
            $response["message"] = "Cette Id de catégorie existe pas !!!!";
        } else {
            $sql = "DELETE FROM %%QUESTS_CATEGORIES%% WHERE questsCategoriesID = :idCat ;";
            $db->delete($sql, [":idCat" => $id]);

            $sql_quests = "UPDATE %%QUESTS_LISTS%% SET questsCategories = 0 WHERE questsCategories = :idCat ;";
            $db->delete($sql, [":idCat" => $id]);

            $response["alert"] = "success";
            $response["message"] = "Votre catégorie à bien était supprimer";
        }
    } else {
        $response["alert"] = "danger";
        $response["message"] = "Cette Catégories n'existe pas !!";
    }

    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
}