<?php

/**
 * @mods Quests
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 */

class ShowQuestsPage extends AbstractAdminPage
{
    public static $requireModule = 0;

    function __construct() 
    {
        if (!allowedTo(str_replace(array(dirname(__FILE__), '\\', '/', '.php'), '', __FILE__))) throw new Exception("Permission error!");
        parent::__construct();
    }
        
    function show()
    {
    
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

        // Affichage de nos différentes quêtes
        $result_list_quests = listQuest($db);

        // L'ajout d'une nouvelle quêtes
        if($action == "add_quest") {
            addQuest($db, $_POST);
            die();
        }

        // Récupération des données de la quête dans notre modal
        if($action == "modal_quest") {
            modalQuest($db, $_GET);
            die();
        }

        // L'édition de la quête
        if($action == "edit_quest") {
            editQuest($db, $_POST);
            die();
        }
        
        // La suppression de la quêtes
        if($action == "delete_quest") {
            deleteQuest($db, $_POST);
            die();
        }

        $template	= new template();

        $template->assign_vars([
            "list_categories" => $result_list_cat,
            "result_list_quests" => $result_list_quests,
            "objectifs_list" => objectifQuestLists(),
        ]);

        $template->show('QuestsPage.tpl');
    }
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

    /**
     * Fonction de la gestion d'ajout de quête
     */
    function listQuest($db) {
        global $LNG, $USER;

        $list_quests = [];

        $sql = "SELECT * FROM %%QUESTS_LISTS%% ORDER BY questsID ;";
        $result = $db->select($sql);

        foreach($result as $response) {

            if($response['quest_time_finish_event'] > TIMESTAMP) {
                $event_finish = _date($LNG['php_tdformat'], $response['quest_time_finish_event'], $USER['timezone']);
            } else if(!empty($response['quest_time_finish_event']) && $response['quest_time_finish_event'] <= TIMESTAMP) {
                $event_finish = -1;
            } else {
                $event_finish = 0;
            }

            $list_quests[] = [
                'questsID' => $response['questsID'],
                'questsCategories' => $response['questsCategories'],
                'quest_title' => $response['quest_title'],
                'quest_description' => $response['quest_description'],
                'quest_objectif' => $response['quest_objectif'],
                'quest_objectif_level' => $response['quest_objectif_level'],
                'quest_points_reward' => $response['quest_points_reward'],
                'quest_metal_reward' => $response['quest_metal_reward'],
                'quest_crystal_reward' => $response['quest_crystal_reward'],
                'quest_deuterium_reward' => $response['quest_deuterium_reward'],
                'quest_darkmatter_reward' => $response['quest_darkmatter_reward'],
                'quest_actif' => $response['quest_actif'],
                'quest_created' => _date($LNG['php_tdformat'], $response['quest_created'], $USER['timezone']),
                'quest_event' => $response['quest_event'],
                'quest_time_finish_event' => $event_finish,
            ];
        }

        return json_encode($list_quests, JSON_PRETTY_PRINT);
    }

    /**
     * Listes des objectifs des quêtes
     */
    function objectifQuestLists() {
        global $LNG, $reslist;

        /**
         * List pour la selection d'un objecif
         */
        $objectifs = [];
        foreach($reslist['build'] as $ID)
    	{
    		$objectifs[$ID]	= array(
                'id' => $ID,
                'name' => $LNG["tech"][$ID],
    		);
    	}

        foreach($reslist['tech'] as $ID)
    	{
    		$objectifs[$ID]	= array(
                'id' => $ID,
                'name' => $LNG["tech"][$ID],
    		);
    	}

        foreach($reslist['fleet'] as $ID)
    	{
    		$objectifs[$ID]	= array(
                'id' => $ID,
                'name' => $LNG["tech"][$ID],
    		);
    	}

        foreach($reslist['defense'] as $ID)
    	{
    		$objectifs[$ID]	= array(
                'id' => $ID,
                'name' => $LNG["tech"][$ID],
    		);
    	}

        foreach($reslist['officier'] as $ID)
    	{
    		$objectifs[$ID]	= array(
                'id' => $ID,
                'name' => $LNG["tech"][$ID],
    		);
    	}

        return json_encode($objectifs, JSON_PRETTY_PRINT);
    }

    /**
     * Fonction qui permet de récupérer les donnée d'une quête
     */
    function modalQuest($db, $data) {
        global $LNG;

        extract($data);

        $response = [];

        if(empty($questID)) {
            $response["alert"] = "danger";
            $response["message"] = "Cette id n'existe pas !!!!";
        } else {
            $sql = "SELECT * FROM %%QUESTS_LISTS%% WHERE questsID = :questsID ;";
            $result = $db->selectSingle($sql, [":questsID" => $questID]);
            $result_count = $db->rowCount($result);

            if($result_count > 0) {
                $timestamp = $result['quest_time_finish_event'];
                $date = new DateTime();
                $date->setTimestamp($timestamp);
                $event_finish = $date->format('Y-m-d\TH:i');

                $response[] = [
                    'questsID' => $result['questsID'],
                    'questsCategories' => $result['questsCategories'],
                    'quest_title' => $result['quest_title'],
                    'quest_description' => $result['quest_description'],
                    'quest_objectif' => $result['quest_objectif'],
                    'quest_objectif_level' => $result['quest_objectif_level'],
                    'quest_points_reward' => $result['quest_points_reward'],
                    'quest_metal_reward' => $result['quest_metal_reward'],
                    'quest_crystal_reward' => $result['quest_crystal_reward'],
                    'quest_deuterium_reward' => $result['quest_deuterium_reward'],
                    'quest_darkmatter_reward' => $result['quest_darkmatter_reward'],
                    'quest_actif' => $result['quest_actif'],
                    'quest_event' => $result['quest_event'],
                    'quest_time_finish_event' => $event_finish,
                ];
            } else {
                $response["alert"] = "danger";
                $response["message"] = "Cette id n'existe pas !!!!";
            }
        }

        echo json_encode($response, JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Fonction de la gestion d'ajout de quête
     */
    function addQuest($db, $data) {
        extract($data);

        $response = [];

        if(empty($categories_id) || empty($quest_title) || empty($quest_obj_level) || empty($quest_obj)) {
            $response["alert"] = "danger";
            $response["message"] = "Des champs sont obligatoire";
        } else {
            $controle_count = "SELECT questsCategories FROM %%QUESTS_CATEGORIES%% WHERE questsCategories = :idCat ;";
            $result_count = $db->selectSingle($controle_count, [":idCat" => $categories_id]);
            $result_count = $db->rowCount($controle_count);

            if(empty($result_count)) {
                $response["alert"] = "danger";
                $response["message"] = "Votre catégorie choisie n'existe pas";
            } else {

                $quest_actif = isset($quest_actif) && $quest_actif == 'on' ? 1 : 0;
                $quest_event = isset($quest_event) && $quest_event == 'on' ? 1 : 0;

                /** Changement du format reçu de la date en Timestamp */
                if(!strtotime($quest_time_finish_event)) {
                    $quest_time_finish_event = 0;
                } else {
                    $quest_time_finish_event = strtotime($quest_time_finish_event);
                }

                $sql_insert = "INSERT INTO %%QUESTS_LISTS%% SET 
                    questsCategories = :questsCategories,
                    quest_title = :quest_title,
                    quest_description = :quest_description,
                    quest_objectif = :quest_objectif,
                    quest_objectif_level = :quest_objectif_level,
                    quest_points_reward = :quest_points_reward,
                    quest_metal_reward = :quest_metal_reward,
                    quest_crystal_reward = :quest_crystal_reward,
                    quest_deuterium_reward = :quest_deuterium_reward,
                    quest_darkmatter_reward = :quest_darkmatter_reward,
                    quest_actif = :quest_actif,
                    quest_created = :quest_created,
                    quest_event = :quest_event,
                    quest_time_finish_event = :quest_time_finish_event ;
                ";

                $result_isert = $db->insert($sql_insert, [
                    ":questsCategories" => $categories_id,
                    ":quest_title" => $quest_title,
                    ":quest_description" => nl2br($quest_desc),
                    ":quest_objectif" => (int) $quest_obj,
                    ":quest_objectif_level" => (int) $quest_obj_level,
                    ":quest_points_reward" => (double) $quest_redward_points,
                    ":quest_metal_reward" => (double) $quest_redward_metal,
                    ":quest_crystal_reward" => (double) $quest_redward_crystal,
                    ":quest_deuterium_reward" => (double) $quest_redward_deuterium,
                    ":quest_darkmatter_reward" => (double) $quest_redward_darkmatter,
                    ":quest_actif" => (int) $quest_actif,
                    ":quest_created" => TIMESTAMP,
                    ":quest_event" => (int) $quest_event,
                    ":quest_time_finish_event" => (int) $quest_time_finish_event,
                ]);

                $response["alert"] = "success";
                $response["message"] = "Notre quête à bien été créer";
            }
        }

        echo json_encode($response, JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Fonction de la suppression d'une catégorie
     */
    function editQuest($db, $data) {
        extract($data);

        $response = [];

        if(empty($categories_id) || empty($quest_title) || empty($quest_obj_level) || empty($quest_obj)) {
            $response["alert"] = "danger";
            $response["message"] = "Des champs sont obligatoire";
        } else {
            $controle_count = "SELECT questsCategories FROM %%QUESTS_CATEGORIES%% WHERE questsCategories = :idCat ;";
            $result_count = $db->selectSingle($controle_count, [":idCat" => $categories_id]);
            $result_count = $db->rowCount($controle_count);

            if(empty($result_count)) {
                $response["alert"] = "danger";
                $response["message"] = "Votre catégorie choisie n'existe pas";
            } else {

                $quest_actif = isset($quest_actif) && $quest_actif == 'on' ? 1 : 0;
                $quest_event = isset($quest_event) && $quest_event == 'on' ? 1 : 0;

                /** Changement du format reçu de la date en Timestamp */
                if(!strtotime($quest_time_finish_event)) {
                    $quest_time_finish_event = 0;
                } else {
                    $quest_time_finish_event = strtotime($quest_time_finish_event);
                }

                $sql_update = "UPDATE %%QUESTS_LISTS%% SET 
                    questsCategories = :questsCategories,
                    quest_title = :quest_title,
                    quest_description = :quest_description,
                    quest_objectif = :quest_objectif,
                    quest_objectif_level = :quest_objectif_level,
                    quest_points_reward = :quest_points_reward,
                    quest_metal_reward = :quest_metal_reward,
                    quest_crystal_reward = :quest_crystal_reward,
                    quest_deuterium_reward = :quest_deuterium_reward,
                    quest_darkmatter_reward = :quest_darkmatter_reward,
                    quest_actif = :quest_actif,
                    quest_created = :quest_created,
                    quest_event = :quest_event,
                    quest_time_finish_event = :quest_time_finish_event 
                    WHERE questsID = :questsID;
                ";

                $result_isert = $db->update($sql_update, [
                    ":questsID" => $id,
                    ":questsCategories" => $categories_id,
                    ":quest_title" => $quest_title,
                    ":quest_description" => nl2br($quest_desc),
                    ":quest_objectif" => (int) $quest_obj,
                    ":quest_objectif_level" => (int) $quest_obj_level,
                    ":quest_points_reward" => (double) $quest_redward_points,
                    ":quest_metal_reward" => (double) $quest_redward_metal,
                    ":quest_crystal_reward" => (double) $quest_redward_crystal,
                    ":quest_deuterium_reward" => (double) $quest_redward_deuterium,
                    ":quest_darkmatter_reward" => (double) $quest_redward_darkmatter,
                    ":quest_actif" => (int) $quest_actif,
                    ":quest_created" => TIMESTAMP,
                    ":quest_event" => (int) $quest_event,
                    ":quest_time_finish_event" => (int) $quest_time_finish_event,
                ]);

                $response["alert"] = "success";
                $response["message"] = "Vos donnée on bien était mise à jour";
            }
        }

        echo json_encode($response, JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Fonction de la suppression d'une catégorie
     */
    function deleteQuest($db, $data) {
        extract($data);

        $response = [];

        if(!empty($data)) {

            $controle_count = "SELECT questsID FROM %%QUESTS_LISTS%% WHERE questsID = :idQuest ;";
            $result_count = $db->selectSingle($controle_count, [":idQuest" => $id]);
            $result_count = $db->rowCount($controle_count);

            if ($result_count < 0 || empty($result_count)) {
                $response["alert"] = "danger";
                $response["message"] = "Cette Id de quête existe pas !!!!";
            } else {
                $sql = "DELETE FROM %%QUESTS_LISTS%% WHERE questsID = :idQuest ;";
                $db->delete($sql, [":idQuest" => $id]);

                $response["alert"] = "success";
                $response["message"] = "Votre quête à bien était supprimer";
            }
        } else {
            $response["alert"] = "danger";
            $response["message"] = "Merci de mettre un id";
        }

        echo json_encode($response, JSON_PRETTY_PRINT);
        exit;
    }
