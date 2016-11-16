CREATE TABLE IF NOT EXISTS `PREFIX_gamifications_player` (
  `id_gamifications_player` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_customer` INT(11) UNSIGNED NOT NULL,
  `total_points` INT(11) UNSIGNED NOT NULL DEFAULT 0,
  `spent_points` INT(11) UNSIGNED NOT NULL DEFAULT 0,
  `active` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
  `date_add` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_upd` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_gamifications_player`, `id_customer`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_gamifications_player_shop` (
  `id_gamifications_player` INT(11) UNSIGNED NOT NULL,
  `id_shop` INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id_gamifications_player`, `id_shop`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;
