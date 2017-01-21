CREATE TABLE IF NOT EXISTS `PREFIX_gamifications_reward` (
  `id_gamifications_reward` INT(11) UNSIGNED AUTO_INCREMENT,
  `reward_type` VARCHAR(50) NOT NULL,
  `points` INT(11) UNSIGNED NOT NULL DEFAULT 0,
  `radius` INT(11) UNSIGNED NOT NULL DEFAULT 0,
  `id_product` INT(11) NOT NULL DEFAULT 0,
  `minimum_cart_amount` INT(11) UNSIGNED NOT NULL DEFAULT 0,
  `discount_reduction_type` VARCHAR(50) NOT NULL DEFAULT '',
  `discount_value` INT(11) UNSIGNED NOT NULL DEFAULT 0,
  `discount_valid_days` INT(11) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_gamifications_reward`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_gamifications_reward_lang` (
  `id_gamifications_reward` INT(11) UNSIGNED NOT NULL,
  `id_lang` INT(11) UNSIGNED NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `description` VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_gamifications_reward`, `id_lang`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_gamifications_reward_shop` (
  `id_gamifications_reward` INT(11) UNSIGNED NOT NULL,
  `id_shop` INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id_gamifications_reward`, `id_shop`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;
