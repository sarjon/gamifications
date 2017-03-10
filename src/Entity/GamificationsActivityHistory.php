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
 * Class GamificationsActivityHistory
 */
class GamificationsActivityHistory extends ObjectModel
{
    /**
     * @var int
     */
    public $id_customer;

    /**
     * @var int
     */
    public $id_reward;

    /**
     * @var int
     */
    public $id_shop;

    /**
     * @var int
     */
    public $activity_type;

    /**
     * @var string
     */
    public $date_add;

    /**
     * @var string
     */
    public $date_upd;

    /**
     * @var int
     */
    public $points;

    /**
     * @var int
     */
    public $reward_type;

    /**
     * @var array
     */
    public static $definition = [
        'table' => 'gamifications_activity_history',
        'primary' => 'id_gamifications_activity_history',
        'fields' => [
            'id_customer' => ['type' => self::TYPE_INT, 'reqired' => true, 'validate' => 'isUnsignedInt'],
            'id_reward' => ['type' => self::TYPE_INT, 'reqired' => true, 'validate' => 'isUnsignedInt'],
            'id_shop' => ['type' => self::TYPE_INT, 'reqired' => true, 'validate' => 'isUnsignedInt'],
            'activity_type' => ['type' => self::TYPE_INT, 'reqired' => true, 'validate' => 'isUnsignedInt'],
            'reward_type' => ['type' => self::TYPE_INT, 'reqired' => true, 'validate' => 'isUnsignedInt'],
            'points' => ['type' => self::TYPE_INT, 'reqired' => false, 'validate' => 'isInt'],
            'date_add' => ['type' => self::TYPE_DATE, 'required' => false, 'validate' => 'isDate'],
            'date_upd' => ['type' => self::TYPE_DATE, 'required' => false, 'validate' => 'isDate'],
        ],
    ];

    /**
     * Log activity history
     *
     * @param GamificationsReward $reward
     * @param int $idCustomer
     * @param int $activityType
     * @param int|null $points
     *
     * @return bool
     */
    public static function log(GamificationsReward $reward, $idCustomer, $activityType, $points = null)
    {
        $context = Context::getContext();

        $activityHistory = new GamificationsActivityHistory();
        $activityHistory->id_customer = (int) $idCustomer;
        $activityHistory->id_reward = (int) $reward->id;
        $activityHistory->id_shop = (int) $context->shop->id;
        $activityHistory->reward_type = (int) $reward->reward_type;
        $activityHistory->activity_type = (int) $activityType;
        $activityHistory->points = (int) $points;

        return $activityHistory->save();
    }
}
