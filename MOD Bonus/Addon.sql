ALTER TABLE  `uni1_users` ADD `bonus_attente_time` int(11) NOT NULL DEFAULT '0';

CREATE TABLE `uni1_bonus` (
  `bonusID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `time_att_bonus` int(11) NOT NULL,
  `metal_min` real unsigned DEFAULT "0",
  `metal_max` real unsigned DEFAULT "0",
  `crystal_min` real unsigned DEFAULT "0",
  `crystal_max` real unsigned DEFAULT "0",
  `deuterium_min` real unsigned DEFAULT "0",
  `deuterium_max` real unsigned DEFAULT "0",
  `darkmatter_min` real unsigned DEFAULT "0",
  `darkmatter_max` real unsigned DEFAULT "0",
  PRIMARY KEY (`bonusID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `uni1_bonus` (`bonusID`, `time_att_bonus`, `metal_min`, `metal_max`, `crystal_min`, `crystal_max`, `deuterium_min`, `deuterium_max`, `darkmatter_min`, `darkmatter_max`) VALUES 
(NULL, '1800', '1000', '5000', '500', '2500', '250', '1500', '10', '50'),
(NULL, '7200', '10000', '50000', '5000', '25000', '2500', '15000', '50', '100'),
(NULL, '10800', '100000', '500000', '50000', '250000', '25000', '150000', '100', '300'),
(NULL, '21600', '1000000', '5000000', '500000', '2500000', '250000', '1500000', '300', '500'),
(NULL, '86400', '10000000', '50000000', '5000000', '25000000', '2500000', '15000000', '500', '1000');