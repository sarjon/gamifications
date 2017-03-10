<?php
/**
 * This file is part of the Gamifications module.
 *
 * @author    Sarunas Jonusas, <jonusas.sarunas@gmail.com>
 * @copyright Copyright (c) permanent, Sarunas Jonusas
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Class GamificationsConfig
 */
class GamificationsConfig
{
    /**
     * Available module configurations
     */
    const REFERRAL_PROGRAM_STATUS = 'GAMIFICATIONS_REFERRAL_STATUS';
    const REFERRAL_REWARD_TIME = 'GAMIFICATIONS_REFERRAL_REWARD_TIME';
    const REFERRAL_REWARD = 'GAMIFICATIONS_REFERRAL_REWARD';
    const REFERRAL_NEW_CUSTOMER_REWARD_ENABLED = 'GAMIFICATIONS_REFERRAL_NEW_CUSTOMER_REWARD_ENABLED';
    const REFERRAL_NEW_CUSTOMER_ORDER_STATES = 'GAMIFICATIONS_REFERRAL_ORDER_STATES';
    const REFERRAL_NEW_CUSTOMER_REWARD = 'GAMIFICATIONS_REFERRAL_NEW_CUSTOMER_REWARD';
    const DAILY_REWARDS_STATUS = 'GAMIFICATIONS_DAILY_REWARDS_STATUS';
    const SHOPPING_POINTS_STATUS = 'GAMIFICATIONS_SHOPPING_POINTS_STATUS';
    const SHOPPING_POINTS_RATIO = 'GAMIFICATIONS_SHOPPING_POINTS_RATIO';
    const SHOPPING_POINTS_ORDER_STATES = 'GAMIFICATIONS_SHOPPING_POINTS_ORDER_STATES';
    const SHOPPING_POINTS_INCLUDE_SHIPPNG_PRICE = 'GAMIFICATIONS_SHOPPING_POINTS_INCLUDE_SHIPPNG_PRICE';

    const DISPLAY_HELP = 'GAMIFICATIONS_DISPLAY_HELP';
    const FRONT_OFFICE_TITLE = 'GAMIFICATIONS_FRONT_OFFICE_TITLE';

    /**
     * Get default configuration
     *
     * @return array
     */
    public static function getDefaultConfiguration()
    {
        $frontOfficeTitle = [];

        foreach (Language::getIDs() as $idLang) {
            $frontOfficeTitle[$idLang] = 'Loyalty program';
        }

        return [
            self::DAILY_REWARDS_STATUS => 0,
            self::DISPLAY_HELP => 1,
            self::FRONT_OFFICE_TITLE => $frontOfficeTitle,
            self::REFERRAL_PROGRAM_STATUS => 0,
            self::REFERRAL_REWARD_TIME => GamificationsActivity::REFERRAL_REWARD_ON_NEW_CUSTOMER_REGISTRATION,
            self::REFERRAL_NEW_CUSTOMER_REWARD_ENABLED => 0,
            self::REFERRAL_NEW_CUSTOMER_ORDER_STATES => json_encode([]),
            self::SHOPPING_POINTS_STATUS => 0,
            self::SHOPPING_POINTS_ORDER_STATES => json_encode([]),
            self::SHOPPING_POINTS_RATIO => 1,
            self::SHOPPING_POINTS_INCLUDE_SHIPPNG_PRICE => 1,
            self::REFERRAL_NEW_CUSTOMER_REWARD => 0,
            self::REFERRAL_REWARD => 0,
        ];
    }
}
