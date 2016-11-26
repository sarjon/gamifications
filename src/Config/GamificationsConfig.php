<?php

/**
 * Class GamificationsConfig
 */
class GamificationsConfig
{
    /**
     * Available module configurations
     */
    const REFERRAL_STATUS = 'GAMIFICATIONS_REFERRAL_STATUS';
    const REFERRAL_REWARD_TIME = 'GAMIFICATIONS_REFERRAL_REWARD_TIME';
    const REFERRAL_ORDER_STATE = 'GAMIFICATIONS_REFERRAL_ORDER_STATE';
    const REFERRAL_REWARD = 'GAMIFICATIONS_REFERRAL_REWARD';
    const REFERRAL_NEW_CUSTOMER_REWARD = 'GAMIFICATIONS_REFERRAL_NEW_CUSTOMER_REWARD';
    const DAILY_REWARDS_STATUS = 'GAMIFICATIONS_DAILY_REWARDS_STATUS';

    const DISPLAY_EXPLANATIONS = 'GAMIFICATIONS_DISPLAY_EXPLANATIONS';
    const FRONT_OFFICE_TITLE = 'GAMIFICATIONS_FRONT_OFFICE_TITLE';

    /**
     * Get default configuration
     *
     * @return array
     */
    public static function getDefaultConfiguration()
    {
        return [
            self::REFERRAL_STATUS => 1,
            self::DAILY_REWARDS_STATUS => 1,
            self::DISPLAY_EXPLANATIONS => 1,
            self::FRONT_OFFICE_TITLE => 'Loyality program',
        ];
    }
}
