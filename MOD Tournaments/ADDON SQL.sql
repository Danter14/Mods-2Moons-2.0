/**
 * @mods Tournoi
 * @version 1.0
 * @author yamilrh
 * @modification Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 */

ALTER TABLE `uni1_config` ADD `tourneyEnd` int(11) UNSIGNED NOT NULL DEFAULT '0';

INSERT INTO `uni1_cronjobs` (`name`, `isActive`, `min`, `hours`, `dom`, `month`, `dow`, `class`, `nextTime`, `lock`) VALUES
('Tournaments', 1, '*/5', '*', '*', '*', '*', 'TournamentCronjob', 1532144700, NULL);

CREATE TABLE `uni1_tourney` (
  `tourneyId` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tourneyName` varchar(50) NOT NULL,
  `priceOne` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `priceTwo` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `priceThree` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `tourneyEvent` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`tourneyId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `uni1_tourney` (`tourneyId`, `tourneyName`, `priceOne`, `priceTwo`, `priceThree`, `tourneyEvent`) VALUES
(1, 'Alpha', 5000, 3000, 2000, 5),
(2, 'Beta', 5000, 3000, 2000, 3),
(3, 'Gamma', 5000, 3000, 2000, 1),
(4, 'Delta', 5000, 3000, 2000, 2),
(5, 'Epsilon', 5000, 3000, 2000, 4);

CREATE TABLE `uni1_tourney_logs` (
  `logId` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tourneyUnits` double(50,0) UNSIGNED NOT NULL DEFAULT '0',
  `playerId` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `joinTime` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `tourneyJoin` int(11) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`logId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `uni1_tourney_participante` (
  `joinId` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tourneyUnits` double(50,0) UNSIGNED NOT NULL DEFAULT '0',
  `playerId` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `joinTime` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `tourneyJoin` int(11) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`joinId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;