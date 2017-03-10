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
 * Class GamificationsActivity
 */
class GamificationsActivity
{
    /**
     * Refferal reward time
     */
    const REFERRAL_REWARD_ON_NEW_CUSTOMER_REGISTRATION = 1;
    const REFERRAL_REWARD_ON_NEW_CUSTOMER_ORDER = 2;

    /**
     * Activities types
     */
    const TYPE_DAILY_REWARD = 1;
    const TYPE_POINT_EXCHANGE = 2;
    const TYPE_REFERRAL_PROGRAM = 3;
    const TYPE_SHOPPING_POINT = 4;
    // This is not an activity, you can add points to customer manually from BO
    const TYPE_MANUALLY_ADDED_POINTS = 5;

    /**
     * Get activity translations
     *
     * @param null $activityType
     *
     * @return string|array
     */
    public static function getActivityTypeTranslations($activityType = null)
    {
        $module = Module::getInstanceByName('gamifications');

        $translations = [
            self::TYPE_DAILY_REWARD =>          $module->l('Daily Rewards', __CLASS__),
            self::TYPE_POINT_EXCHANGE =>        $module->l('Points Exchange', __CLASS__),
            self::TYPE_REFERRAL_PROGRAM =>      $module->l('Referral program', __CLASS__),
            self::TYPE_SHOPPING_POINT =>        $module->l('Shopping points', __CLASS__),
            self::TYPE_MANUALLY_ADDED_POINTS => $module->l('Manually added points', __CLASS__),
        ];

        if (null !== $activityType) {
            return $translations[$activityType];
        }

        return $translations;
    }
}
