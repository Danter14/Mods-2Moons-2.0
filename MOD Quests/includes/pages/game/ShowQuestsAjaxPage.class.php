<?php

/**
 * @mods Quests
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 */

class ShowQuestsAjaxPage extends AbstractGamePage
{
	public static $requireModule = 0;

	function __construct()
	{
		parent::__construct();
        $this->setWindow('ajax');
	}

	function show()
	{
		global $LNG, $USER;

        extract($_GET);

        $db = Database::get();

        if(empty($categorie_id)) $categorie_id = 1;

        $sql_cat = "SELECT * FROM %%QUESTS_CATEGORIES%% ;";
        $result_cat = $db->select($sql_cat);

        $sql = "SELECT ql.*, qu.* FROM %%QUESTS_LISTS%% ql 
            LEFT JOIN %%QUESTS_USERS%% qu ON qu.user_questsID = ql.questsID 
            WHERE ql.quest_actif = 1 AND ql.questsCategories = :idCat 
            ORDER BY ql.questsID ;";
        $result = $db->select($sql, [":idCat" => $categorie_id]);

        $content_list = "";
        foreach($result as $response) {

            if($response['quest_time_finish_event'] > TIMESTAMP) {
                $event_finish = _date($LNG['php_tdformat'], $response['quest_time_finish_event'], $USER['timezone']);
            } else if(!empty($response['quest_time_finish_event']) && $response['quest_time_finish_event'] <= TIMESTAMP) {
                $event_finish = -1;
                $db->update("UPDATE %%QUESTS_LISTS%% SET quest_actif = 0 WHERE questsID = :questsID ;", [
                    "questsID" => $response['questsID'],
                ]);
            } else {
                $event_finish = 0;
            }

            $list_reward = [
                '901' => $response['quest_metal_reward'],
                '902' => $response['quest_crystal_reward'],
                '903' => $response['quest_deuterium_reward'],
                '921' => $response['quest_darkmatter_reward'],
            ];

            $content_list .= "<div class='quest_list' id='quest_".$response['questsID']."'>
                <div class='label_list'>
                    <div>";
                        if ($response['quest_event'] == 1) {
                            $content_list .= "<span class='badge info' style='margin-right: 5px;'>Event</span>";
                        }
                        if(is_null($response['user_quest_users_finish'])) {
                            $content_list .= "<span id='quest_badge_".$response['questsID']."' class='badge danger'>Non commencé</span>";
                        } else if ($response['user_quest_users_finish'] == 1) {
                            $content_list .= "<span id='quest_badge_".$response['questsID']."' class='badge warning'>En cours</span>";
                        } else {
                            $content_list .= "<span id='quest_badge_".$response['questsID']."' class='badge success'>Terminer</span>";
                        }
                        $content_list .= "</div>";
                    if ($event_finish > 0) {
                        $content_list .= "<span class='badge info'>Fini le ".$event_finish."</span>";
                    } else if ($event_finish < 0) {
                        $content_list .= "<span class='badge warning'>Event terminé</span>";
                    }
                    $content_list .= "<span class='badge info'><img src='https://cdn-icons-png.flaticon.com/512/2583/2583264.png' width='15' /> ".number_format($response['quest_points_reward'])."</span>
                </div>
                <div class='title_quest'>
                    <h2>".$response['quest_title']."</h2>
                </div>
                <p>".$response['quest_description']."</p>
                <div class='reward'>
                    <div>
                        <ul>";
                            foreach ($list_reward as $key => $reward) {
                                if ($reward > 0) {
                                    $content_list .= "<li>".number_format($reward)." ".$LNG['tech'][$key]."<li>";
                                }
                            }
                            $content_list .= "</ul>
                    </div>
                    <div>+".number_format($response['quest_objectif_level'])." ".$LNG['tech'][$response['quest_objectif']]."</div>";
                    if(is_null($response['user_quest_users_finish'])) {
                        $content_list .= "<div id='quest_button_".$response['questsID']."'><button id='btn_quest_".$response['questsID']."' onclick='javascript:questConfirm(".$response['questsID'].")'>Débuter</button></div>";
                    } else {
                        $content_list .= "<div class='progresse_bar_border' id='quest_button_".$response['questsID']."'></div>";
                    }
                    $content_list .= "</div>
            </div>";
        }

        if(!$result_cat && !$result) {
            $content_list .= "<div class='quest_list' style='text-align: center;'>
            <h2>Désolé mais actuellement il n'y aucun contenu disponible</h2>
            </div>";
        }

        if($result_cat && !$result) {
            $content_list .= "<div class='quest_list' style='text-align: center;'>
            <h2>Aucune quête disponible pour cette catégorie</h2>
            </div>";
        }

        echo json_encode($content_list, JSON_PRETTY_PRINT);
	}

    /** Confimation de la l'acceptation de la quête PART 5 */
    function fleetVol($fleetId): int {
        global $LNG, $USER, $resource;

        $db = Database::get();

        $result = $db->select("SELECT fleet_owner, fleet_array FROM %%FLEETS%% WHERE fleet_owner = :userID", [":userID" => $USER['id']]);

        $shipList		= [];
        $fleet_count = 0;
        if($result) {
            foreach($result as $fleet) {
                $shipArray		= array_filter(explode(';', $fleet['fleet_array']));
                foreach($shipArray as $ship) {
                    $shipDetail		= explode(',', $ship);
                    $shipList[]	= [
                        "id" => $shipDetail[0],
                        "count" => $shipDetail[1]
                    ];
                }
            }

            foreach($shipList as $fleetCount) {
                if($fleetCount["id"] == $fleetId) {
                    $fleet_count += $fleetCount["count"];
                }
            }
        }

        return $fleet_count;
    }

    function runQuest() {
        global $LNG, $USER, $resource;

        $db = Database::get();

        extract($_POST);

        $sql = "SELECT * FROM %%QUESTS_LISTS%% WHERE questsID = :idQuest ;";
        $result = $db->selectSingle($sql, [":idQuest" => $quest_id]);

        $response = [];

        if($result < 1) {
            $response["error"] = "danger";
            $response["message"] = "Cette quête n'existe plus.";
        } else {
            if($result["quest_actif"] == 1) {
                if($result['quest_event'] == 1 && $result['quest_time_finish_event'] < TIMESTAMP) {
                    $response["error"] = "warning";
                    $response["message"] = "La quête d'événement est terminé.";
                } else {

                    $sql_controle = "SELECT * FROM %%QUESTS_USERS%% WHERE user_questsID = :questID ;";
                    $result_controle = $db->selectSingle($sql_controle, [":questID" => $quest_id]);

                    if($result_controle > 1) {
                        $response["error"] = "danger";
                        $response["message"] = "Vous avez déjà accepter cette quête.";
                    } else {
                        $table = "%%PLANETS%% WHERE id_owner = :where";

                        if ($result['quest_objectif'] > 100 && $result['quest_objectif'] < 200 || 
                            $result['quest_objectif'] > 600 && $result['quest_objectif'] < 700) 
                        {
                            $table = "%%USERS%% WHERE id = :where ;";
                        }
                        
                        $field = $resource[$result['quest_objectif']];
                        $tab = [":where" => $USER["id"]];

                        $sql_result_quest = "SELECT SUM($field) as total FROM $table";
                        $result_quest_user = $db->selectSingle($sql_result_quest, $tab);

                        if ($result['quest_objectif'] > 200 && $result['quest_objectif'] < 300) 
                        {
                            $result_quest_user['total'] += self::fleetVol($result['quest_objectif']);
                        }

                        $sql_user_quest = "INSERT INTO %%QUESTS_USERS%% SET 
                            userId = :userID,
                            user_questsID = :questID,
                            user_quest_objectif = :objectifID,
                            user_quest_objectif_level = :objectifLEVEL,
                            user_quest_objectif_level_user = :objectifLevelUser,
                            user_quest_users_accept = :created_at,
                            user_quest_users_finish = :quest_finish
                        ;";
                        $db->insert($sql_user_quest, [
                            ":userID" => $USER['id'],
                            ":questID" => $result['questsID'],
                            ":objectifID" => $result['quest_objectif'],
                            ":objectifLEVEL" => $result['quest_objectif_level'],
                            ":objectifLevelUser" => $result_quest_user['total'],
                            ":quest_finish" => 1,
                            ":created_at" => TIMESTAMP,
                        ]);

                        $response["error"] = "success";
                        $response["message"] = "La quête ".$result['quest_title']." à bien commencer.";

                        $response["questID"] = $result['questsID'];
                        $response["badgeStyle"] = "warning";
                        $response["badgeContent"] = "En cours";
                    }
                }
            } else {
                $response["error"] = "warning";
                $response["message"] = "La quête n'est pas active.";
            }
        }

        echo json_encode($response, JSON_PRETTY_PRINT);
    }

    function verifQuest() {
        global $LNG, $USER, $resource;

        $db = Database::get();

        $response = [];

        $sql = "SELECT * FROM %%QUESTS_USERS%% WHERE userId = :userId ;";
        $result = $db->select($sql, [":userId" => $USER['id']]);

        $responseQuest = [];
        foreach($result as $verif) {
            $table = "%%PLANETS%% WHERE id_owner = :where";

            if ($verif['user_quest_objectif'] > 100 && $verif['user_quest_objectif'] < 200 || 
                $verif['user_quest_objectif'] > 600 && $verif['user_quest_objectif'] < 700) 
            {
                $table = "%%USERS%% WHERE id = :where ;";
            }
                            
            $field = $resource[$verif['user_quest_objectif']];
            $tab = [":where" => $USER["id"]];

            $sql_verif_quest = "SELECT SUM($field) as total FROM $table";
            $verif_quest_user = $db->selectSingle($sql_verif_quest, $tab);

            $fleet_vol = 0;
            if ($verif['user_quest_objectif'] > 200 && $verif['user_quest_objectif'] < 300) 
            {
                $fleet_vol = self::fleetVol($verif['user_quest_objectif']);
            }

            $total_user_objectif = $verif['user_quest_objectif_level_user'] + $verif['user_quest_objectif_level'];
            $count_current = $total_user_objectif - $verif_quest_user["total"];
            $countrest = ($verif["user_quest_objectif_level"] + $fleet_vol) - $count_current;

            if($verif_quest_user["total"] >= $total_user_objectif) {
                $responseQuest[$verif['user_questsID']] = [
                    "user_quest_finish" => true,
                    "count_current_pourcent" => ($countrest / $verif['user_quest_objectif_level']) * 100,
                    "user_quest_users_finish" => $verif['user_quest_users_finish'],
                ];
            } else {
                $responseQuest[$verif['user_questsID']] = [
                    "user_quest_finish" => false,
                    "count_current_pourcent" => round(($countrest / $verif['user_quest_objectif_level']) * 100, 2),
                    "user_quest_users_finish" => $verif['user_quest_users_finish'],
                ];
            }
        }

        echo json_encode($responseQuest, JSON_PRETTY_PRINT);
    }

    function finishQuest() {
        global $LNG, $USER, $resource, $PLANET;

        $db = Database::get();

        extract($_POST);

        $response = [];

        $sql = "SELECT * FROM %%QUESTS_USERS%% WHERE user_questsID = :questsID AND userId = :userId ;";
        $result = $db->selectSingle($sql, [":questsID" => $quest_id, ":userId" => $USER['id']]);

        if($result['user_quest_users_finish'] == 2) {
            $response["error"] = "danger";
            $response["message"] = "Vous avez déjà terminer cette quête.";
        } else {
            $table = "%%PLANETS%% WHERE id_owner = :where";

            if ($result['user_quest_objectif'] > 100 && $result['user_quest_objectif'] < 200 || 
                $result['user_quest_objectif'] > 600 && $result['user_quest_objectif'] < 700) 
            {
                $table = "%%USERS%% WHERE id = :where ;";
            }
                                
            $field = $resource[$result['user_quest_objectif']];
            $tab = [":where" => $USER["id"]];

            $sql_result_quest = "SELECT SUM($field) as total FROM $table";
            $result_quest_user = $db->selectSingle($sql_result_quest, $tab);

            $total_user_objectif = $result['user_quest_objectif_level_user'] + $result['user_quest_objectif_level'];
            $count_current = $total_user_objectif - $result_quest_user["total"];
            $countrest = $result["user_quest_objectif_level"] - $count_current;

            if($result_quest_user["total"] >= $total_user_objectif) {
                self::rewardQuest($result["user_questsID"]);

                $response["questID"] = $result["user_questsID"];
                $response["badgeStyle"] = "success";
                $response["badgeContent"] = "Terminé";
                $response["error"] = "success";
                $response["message"] = "Bravo c'est gagné, vous avez fini la quête. 
                Les récompense on étais ajouter à votre compte";
            } else {
                $response["error"] = "danger";
                $response["message"] = "Pas de triche merci, tentative enregistrer.";
            }
        }
        
        echo json_encode($response, JSON_PRETTY_PRINT);
    }

    function rewardQuest($questID) {
        global $LNG, $USER, $resource, $PLANET;

        $db = Database::get();

        $sql = "SELECT quest_points_reward, 
        quest_metal_reward, 
        quest_crystal_reward, 
        quest_deuterium_reward, 
        quest_darkmatter_reward 
        FROM %%QUESTS_LISTS%% WHERE questsID = :questID ;";
        $result = $db->selectSingle($sql, [":questID" => $questID]);

        $field_planet = [];
        $field_users = "";
        if(!empty($result['quest_metal_reward'])) {
            $response["metal"] = $result['quest_metal_reward'];
            $field_planet[] = $resource[901]."=".$resource[901]."+".$result['quest_metal_reward'];
        }

        if(!empty($result['quest_crystal_reward'])) {
            $response["crystal"] = $result['quest_crystal_reward'];
            $field_planet[] = $resource[902]."=".$resource[902]."+".$result['quest_crystal_reward'];
        }

        if(!empty($result['quest_deuterium_reward'])) {
            $response["deuterium"] = $result['quest_deuterium_reward'];
            $field_planet[] = $resource[903]."=".$resource[903]."+".$result['quest_deuterium_reward'];
        }

        if(!empty($result['quest_darkmatter_reward'])) {
            $field_users = $resource[921]."=".$resource[921]."+".$result['quest_darkmatter_reward'].",";
            $response["darkmatter"] = $result['quest_darkmatter_reward'];
        }

        $sql = "UPDATE %%USERS%% SET $field_users reputation_quests = reputation_quests + :reputation_quests WHERE id = :userID ;";
        $db->update($sql, [":reputation_quests" => $result['quest_points_reward'], ":userID" => $USER['id']]);

        if(!empty($field_planet)) {
            $set = implode(", ", $field_planet);

            $sql = "UPDATE %%PLANETS%% SET $set WHERE id = :idPlanet ;";
            $db->update($sql, [":idPlanet" => $USER['id_planet']]);
        }

        $sql = "UPDATE %%QUESTS_USERS%% SET user_quest_users_finish = :user_quest_users_finish WHERE user_questsID = :questsID AND userId = :userId ;";
        $result = $db->update($sql, [":user_quest_users_finish" => 2, ":questsID" => $questID, ":userId" => $USER['id']]);

        return $response;
    }
}