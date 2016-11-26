<?php

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
        ];

        if (null !== $activityType) {
            return $translations[$activityType];
        }

        return $translations;
    }
}
