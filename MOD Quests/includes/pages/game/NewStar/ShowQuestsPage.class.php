<?php

/**
 * @mods Quests
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 */

class ShowQuestsPage extends AbstractGamePage
{
	public static $requireModule = 0;

	function __construct()
	{
		parent::__construct();
	}

	function show()
	{
		global $USER, $LNG;

        $db = Database::get();

        $list_cat = [];
        $sql_cat_list = "SELECT questsCategories FROM %%QUESTS_CATEGORIES%% ORDER BY questsCategories ;";
        $result_cat_list = $db->select($sql_cat_list);
        
        foreach($result_cat_list as $categories) {
            $list_cat[] = [
                "id_cat" => $categories['questsCategories'],
                "name_cat" => $LNG["quest_categorie_".$categories['questsCategories']],
            ];
        }

        $this->tplObj->loadscript('quests.js');

		$this->assign([
            "result_cat_list" => $list_cat,
        ]);

		$this->display('page.quests.default.tpl');
	}
}