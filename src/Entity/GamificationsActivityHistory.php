<?php

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
    public $reward_typpe;

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
            'points' => ['type' => self::TYPE_INT, 'reqired' => false, 'validate' => 'isUnsignedInt'],
            'date_add' => ['type' => self::TYPE_DATE, 'required' => false, 'validate' => 'isDate'],
            'date_upd' => ['type' => self::TYPE_DATE, 'required' => false, 'validate' => 'isDate'],
        ],
    ];
}
