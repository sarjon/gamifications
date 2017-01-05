CREATE TABLE IF NOT EXISTS `PREFIX_gamifications_activity_history` (
  `id_gamifications_activity_history` INT(11) AUTO_INCREMENT,
  `id_customer` INT(11) UNSIGNED NOT NULL,
  `id_reward` INT(11) UNSIGNED NOT NULL,
  `id_shop` INT(11) UNSIGNED NOT NULL,
  `activity_type` INT(11) UNSIGNED NOT NULL,
  `reward_type` INT(11) UNSIGNED NOT NULL,
  `points` INT(11) NOT NULL DEFAULT 0,
  `date_add` DATETIME NOT NULL,
  `date_upd` DATETIME NOT NULL,
  PRIMARY KEY (`id_gamifications_activity_history`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;
