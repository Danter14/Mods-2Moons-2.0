CREATE TABLE IF NOT EXISTS `uni1_voucher` (
  `id_voucher` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(60) NOT NULL DEFAULT '',
  `start_time` int(11) NOT NULL DEFAULT '0',
  `end_time` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id_voucher`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `uni1_voucher_rewards` (
  `id_reward` int(11) NOT NULL AUTO_INCREMENT,
  `id_voucher` int(11) NOT NULL DEFAULT '0',
  `reward` varchar(40) NOT NULL DEFAULT '',
  `price_reward` double(255,0) NOT NULL DEFAULT '0',
  FOREIGN KEY (`id_voucher`) REFERENCES `uni1_voucher`(`id_voucher`),
  PRIMARY KEY (`id_reward`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `uni1_voucher_user_use` (
  `id_voucher_use` int(11) NOT NULL AUTO_INCREMENT,
  `id_voucher` int(11) NOT NULL DEFAULT '0',
  `id_user` int(11) NOT NULL DEFAULT '0',
  `use_time` int(11) NOT NULL DEFAULT '0',
  FOREIGN KEY (`id_voucher`) REFERENCES `uni1_voucher`(`id_voucher`),
  FOREIGN KEY (`id_user`) REFERENCES `uni1_users`(`id`),
  PRIMARY KEY (`id_voucher_use`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;