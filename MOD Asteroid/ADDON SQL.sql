/**
 * @mods Asteroids
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 */

ALTER TABLE `uni1_config`
  ADD `asteroid_actif` int(11) UNSIGNED NOT NULL DEFAULT '0',
  ADD `asteroid_event` int(11) UNSIGNED NOT NULL DEFAULT '0',
  ADD `asteroid_metal` double(255,0) UNSIGNED NOT NULL DEFAULT '2000',
  ADD `asteroid_crystal` double(255,0) UNSIGNED NOT NULL DEFAULT '1500',
  ADD `asteroid_deuterium` double(255,0) UNSIGNED NOT NULL DEFAULT '750',
  ADD `asteroid_count` int(11) UNSIGNED NOT NULL DEFAULT '10',
  ADD `asteroid_round` int(11) UNSIGNED NOT NULL DEFAULT '0';

INSERT INTO `uni1_cronjobs` (`name`, `isActive`, `min`, `hours`, `dom`, `month`, `dow`, `class`, `nextTime`, `lock`) VALUES
('Asteroids', 1, '*', '*', '*', '*', '*', 'AsteroidsCronjob', 1532144700, NULL);