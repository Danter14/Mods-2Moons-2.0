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

        $sql = "SELECT * FROM %%QUESTS_LISTS%% WHERE quest_actif = 1 AND questsCategories = :idCat ORDER BY questsID ;";
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
                        $content_list .= "<span class='badge danger'>Non commencé</span>
                    </div>";
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
                    <div>+".number_format($response['quest_objectif_level'])." ".$LNG['tech'][$response['quest_objectif']]."</div>
                    <div><button onclick='javascript:questConfirm(".$response['questsID'].")'>Débuter</button></div>
                </div>
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
}