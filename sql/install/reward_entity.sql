CREATE TABLE IF NOT EXISTS `PREFIX_gamification_reward` (
  `id_gamification_reward` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `reward_type` VARCHAR(50) NOT NULL,
  `points` INT(11) UNSIGNED NOT NULL DEFAULT 0,
  `prize` VARCHAR(20) NOT NULL DEFAULT 0,
  `minimum_cart_amount` INT(11) UNSIGNED NOT NULL DEFAULT 0,
  `discount_reduction_type` VARCHAR(50) NOT NULL DEFAULT '',
  `discount_apply_type` VARCHAR(50) NOT NULL DEFAULT '',
  `discount_value` INT(11) UNSIGNED NOT NULL DEFAULT 0,
  `discount_valid_days` INT(11) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_gamification_reward`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_gamification_reward_lang` (
  `id_gamification_reward` INT(11) UNSIGNED NOT NULL,
  `id_lang` INT(11) UNSIGNED NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id_gamification_reward`, `id_lang`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_gamification_reward_shop` (
  `id_gamification_reward` INT(11) UNSIGNED NOT NULL,
  `id_shop` INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id_gamification_reward`, `id_shop`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;
