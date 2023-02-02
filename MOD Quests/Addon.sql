/**
 * @mods Quests
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 */

CREATE TABLE `uni1_quests_categories` (
  `questsCategoriesID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `questsCategories` int(11) unsigned NOT NULL DEFAULT "0",
  PRIMARY KEY (`questsCategoriesID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `uni1_quests_lists` (
  `questsID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `questsCategories` int(11) unsigned NOT NULL DEFAULT "0",
  `quest_title` varchar(255) NOT NULL DEFAULT "",
  `quest_description` text,
  `quest_objectif` int(11) NOT NULL DEFAULT "0",
  `quest_objectif_level` int(11) NOT NULL DEFAULT "0",
  `quest_points_reward` real unsigned DEFAULT "0",
  `quest_metal_reward` real unsigned DEFAULT "0",
  `quest_crystal_reward` real unsigned DEFAULT "0",
  `quest_deuterium_reward` real unsigned DEFAULT "0",
  `quest_darkmatter_reward` real unsigned DEFAULT "0",
  `quest_actif` int(11) unsigned DEFAULT "0",
  `quest_created` int(11) unsigned DEFAULT "0",
  `quest_event` int(11) unsigned DEFAULT "0",
  `quest_time_finish_event` int(11) unsigned DEFAULT "0",
  PRIMARY KEY (`questsID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `uni1_quests_users` (
  `userQuestsID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(11) unsigned NOT NULL DEFAULT "0",
  `questsID` varchar(255) NOT NULL DEFAULT "",
  `quest_objectif` text,
  `quest_objectif_level` int(11) NOT NULL DEFAULT "0",
  `quest_objectif_level_user` int(11) NOT NULL DEFAULT "0",
  `quest_users_accept` int(11) unsigned DEFAULT "0",
  `quest_users_finish` int(11) unsigned DEFAULT "0",
  PRIMARY KEY (`userQuestsID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE  `uni1_users` ADD `reputation_quests` real unsigned NOT NULL DEFAULT '0';