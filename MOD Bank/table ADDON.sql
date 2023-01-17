/**
 * @mods Bank
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 */

ALTER TABLE  `uni1_config` ADD `commision_bank` int(11) NOT NULL DEFAULT '10';

CREATE TABLE `uni1_bank` (
  `bankId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `bank_metal` real unsigned NOT NULL DEFAULT '0',
  `bank_cristal` real unsigned NOT NULL DEFAULT '0',
  `bank_deuterium` real unsigned NOT NULL DEFAULT '0',
  `bank_darkmatter` real unsigned NOT NULL DEFAULT '0',
  `bank_time_update` int(11) unsigned NOT NULL DEFAULT '0',
  `bank_time_retrait` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`bankId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;