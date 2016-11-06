<?php

/**
 * Class GamificationReward
 */
class GamificationReward extends ObjectModel
{
    /**
     * Reward types
     */
    const REWARD_TYPE_POINTS = 'points';
    const REWARD_TYPE_DISCOUNT = 'discount';
    const REWARD_TYPE_FREE_SHIPPING = 'shipping';

    /**
     * Discount reduction types
     */
    const DISCOUNT_REDUCTION_PERCENT = 'percent';
    const DISCOUNT_REDUCTION_AMOUNT = 'amount';

    /**
     * Discount apply types
     */
    const DISCOUNT_TYPE_AUTOMATICALLY_APPLIED = 'automatically_applied';
    const DISCOUNT_TYPE_CODE = 'code';

    /**
     * @var string|array
     */
    public $name;

    /**
     * @var string
     */
    public $reward_type;

    /**
     * @var int
     */
    public $points;

    /**
     * @var int
     */
    public $minimum_cart_amount;

    /**
     * @var string
     */
    public $discount_reduction_type;

    /**
     * @var string
     */
    public $discount_apply_type;

    /**
     * @var int
     */
    public $discount_value;

    /**
     * @var int
     */
    public $discount_valid_days;

    /**
     * @var array
     */
    public static $definition = [
        'table' => 'gamification_reward',
        'primary' => 'id_gamification_reward',
        'fields' => [
            'reward_type' => [
                'type' => self::TYPE_STRING,
                'size' => 50,
                'required' => true,
                'validate' => 'isString',

            ],
            'points' => [
                'type' => self::TYPE_INT,
                'required' => false,
                'validate' => 'isUnsignedInt',
            ],
            'minimum_cart_amount' => [
                'type' => self::TYPE_INT,
                'required' => false,
                'validate' => 'isUnsignedFloat',
            ],
            'discount_reduction_type' => [
                'type' => self::TYPE_STRING,
                'required' => false,
                'size' => 50,
                'validate' => 'isString',
            ],
            'discount_apply_type' => [
                'type' => self::TYPE_STRING,
                'required' => false,
                'size' => 50,
                'validate' => 'isString',
            ],
            'discount_value' => [
                'type' => self::TYPE_INT,
                'required' => false,
                'validate' => 'isUnsignedInt',
            ],
            'discount_valid_days' => [
                'type' => self::TYPE_INT,
                'required' => false,
                'validate' => 'isUnsignedInt',
            ],
            'name' => [
                'type' => self::TYPE_STRING,
                'required' => true,
                'lang' => true,
                'size' => 100,
                'validate' => 'isGenericName',
            ],
        ],
        'multishop' => true,
        'multilang' => true,
    ];

    /**
     * GamificationReward constructor.
     *
     * @param int|null $id
     * @param int|null $idLang
     * @param int|null $idShop
     */
    public function __construct($id = null, $idLang = null, $idShop = null)
    {
        parent::__construct($id, $idLang, $idShop);
        Shop::addTableAssociation(self::$definition['table'], ['type' => 'shop']);
    }
}
