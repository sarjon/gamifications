CREATE TABLE IF NOT EXISTS `PREFIX_gamifications_referral` (
  `id_gamifications_referral` INT(11) UNSIGNED AUTO_INCREMENT,
  `id_invited_customer` INT(11) UNSIGNED NOT NULL,
  `id_referral_customer` INT(11) UNSIGNED NOT NULL,
  `id_shop` INT(11) UNSIGNED NOT NULL,
  `active` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
  `date_add` DATETIME NOT NULL,
  `date_upd` DATETIME NOT NULL,
  PRIMARY KEY (`id_gamifications_referral`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;
