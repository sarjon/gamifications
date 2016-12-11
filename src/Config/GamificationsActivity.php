<?php
/**
 * This file is part of the Gamifications module.
 *
 * @author    Sarunas Jonusas, <jonusas.sarunas@gmail.com>
 * @copyright Copyright (c) permanent, Sarunas Jonusas
 * @license   Addons PrestaShop license limitation
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
        $translator = $module->getTranslator();

        $translations = [
            self::TYPE_DAILY_REWARD => $translator->trans('Daily Rewards', [], 'Modules.Gamifications.Admin'),
            self::TYPE_POINT_EXCHANGE => $translator->trans('Points Exchange', [], 'Modules.Gamifications.Admin'),
            self::TYPE_REFERRAL_PROGRAM => $translator->trans('Referral program', [], 'Modules.Gamifications.Admin'),
            self::TYPE_SHOPPING_POINT => $translator->trans('Shopping points', [], 'Modules.Gamifications.Admin'),
            self::TYPE_MANUALLY_ADDED_POINTS =>
                $translator->trans('Manually added points', [], 'Modules.Gamifications.Admin'),
        ];

        if (null !== $activityType) {
            return $translations[$activityType];
        }

        return $translations;
    }
}
