/**
 * @mods Loterie
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 */

ALTER TABLE `uni1_config` 
ADD `lottery_actif` int(11) NOT NULL DEFAULT '0',
ADD `lottery_actif_att_time` int(11) NOT NULL DEFAULT '0',
ADD `lottery_ticket_prize_metal` double(255, 0) NOT NULL DEFAULT '15000',
ADD `lottery_ticket_prize_crystal` double(255, 0) NOT NULL DEFAULT '12000',
ADD `lottery_ticket_prize_deuterium` double(255, 0) NOT NULL DEFAULT '7500',
ADD `lottery_max_users_tickets` int(11) NOT NULL DEFAULT '1',
ADD `lottery_max_tickets` int(11) NOT NULL DEFAULT '10',
ADD `lottery_max_users_winner` int(11) NOT NULL DEFAULT '1',
ADD `lottery_time` int(11) NOT NULL DEFAULT '0',
ADD `lottery_min` int(11) NOT NULL DEFAULT '10',
ADD `lottery_prize` int(11) NOT NULL DEFAULT '100',
ADD `lottery_prize_add` int(11) NOT NULL DEFAULT '50',
ADD `lottery_count` int(11) NOT NULL DEFAULT '1';

CREATE TABLE IF NOT EXISTS `uni1_lottery`(
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `userId` int(11) NOT NULL,
    `tickets` int(5) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `uni1_lottery_log`(
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `userId` int(11) NOT NULL,
    `time` int(11) NOT NULL,
    `prize` int(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
