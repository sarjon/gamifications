CREATE TABLE IF NOT EXISTS `PREFIX_gamifications_activity_history` (
  `id_gamifications_activity_history` INT(11) NOT NULL AUTO_INCREMENT,
  `id_gamifications_player` INT(11) UNSIGNED NOT NULL,
  `id_reward` INT(11) UNSIGNED NOT NULL,
  `id_shop` INT(11) UNSIGNED NOT NULL,
  `activity_type` INT(11) UNSIGNED NOT NULL,
  `date_add` DATETIME NOT NULL,
  PRIMARY KEY (`id_gamifications_activity_history`, `id_gamifications_player`, `id_reward`, `id_shop`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;
