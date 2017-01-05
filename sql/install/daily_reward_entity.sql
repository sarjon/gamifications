CREATE TABLE IF NOT EXISTS `PREFIX_gamifications_daily_reward` (
  `id_gamifications_daily_reward` INT(11) UNSIGNED AUTO_INCREMENT,
  `id_reward` INT(11) UNSIGNED NOT NULL,
  `boost` INT(11) UNSIGNED NOT NULL,
  `active` TINYINT(1) UNSIGNED NOT NULL,
  `times_won` INT(11) UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_gamifications_daily_reward`, `id_reward`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_gamifications_daily_reward_shop` (
  `id_gamifications_daily_reward` INT(11) UNSIGNED NOT NULL,
  `id_shop` INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id_gamifications_daily_reward`, `id_shop`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_gamifications_daily_reward_group` (
  `id_gamifications_daily_reward` INT(11) UNSIGNED NOT NULL,
  `id_group` INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id_gamifications_daily_reward`, `id_group`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;
