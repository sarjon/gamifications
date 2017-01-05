CREATE TABLE IF NOT EXISTS `PREFIX_gamifications_shopping_point` (
  `id_gamifications_shopping_point` INT(11) AUTO_INCREMENT,
  `id_customer` INT(11) UNSIGNED NOT NULL,
  `id_order` INT(11) UNSIGNED NOT NULL,
  `id_shop` INT(11) UNSIGNED NOT NULL,
  `active` TINYINT(1) UNSIGNED NOT NULL,
  `date_add` DATETIME NOT NULL,
  `date_upd` DATETIME NOT NULL,
  PRIMARY KEY (`id_gamifications_shopping_point`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;
