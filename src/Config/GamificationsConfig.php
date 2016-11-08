<?php

/**
 * Class GamificationsConfig
 */
class GamificationsConfig
{
    /**
     * Available module configurations
     */
    const CHALLANGES_STATUS = 'GAMIFICATIONS_CHALLANGES_STATUS';
    const CHALLANGES_DISPLAY_REWARDS = 'GAMIFICATIONS_CHALLANGES_DISPLAY_REWARDS';
    const DAILY_DAILY_REWARDS_STATUS = 'GAMIFICATIONS_DAILY_REWARDS_STATUS';

    const DISPLAY_EXPLANATIONS = 'GAMIFICATIONS_DISPLAY_EXPLANATIONS';

    /**
     * Get default configuration
     *
     * @return array
     */
    public static function getDefaultConfiguration()
    {
        return [
            self::CHALLANGES_STATUS => 1,
            self::CHALLANGES_DISPLAY_REWARDS => 0,
            self::DAILY_DAILY_REWARDS_STATUS => 1,
            self::DISPLAY_EXPLANATIONS => 1,
        ];
    }
}
