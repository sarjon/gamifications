CREATE TABLE IF NOT EXISTS `PREFIX_gamifications_rank` (
  `id_gamifications_rank` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_group` INT(11) UNSIGNED NOT NULL,
  `id_shop` INT(11) UNSIGNED NOT NULL,
  `id_parent` INT(11) UNSIGNED NOT NULL UNIQUE,
  `must_spend_points` INT(11) UNSIGNED NOT NULL,
  `must_spend_money` DECIMAL(17, 2) UNSIGNED NOT NULL,
  `date_add` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_upd` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_gamifications_rank`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_gamifications_rank_lang` (
  `id_gamifications_rank` INT(11) UNSIGNED NOT NULL,
  `id_lang` INT(11) UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id_gamifications_rank`, `id_lang`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_gamifications_rank_order` (
  `id_customer` INT(11) UNSIGNED NOT NULL,
  `id_order` INT(11) UNSIGNED NOT NULL,
  `active` TINYINT(1) UNSIGNED NOT NULL,
  PRIMARY KEY (`id_customer`, `id_order`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;