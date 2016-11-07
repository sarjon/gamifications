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
    const REWARD_TYPE_FREE_SHIPPING = 'free_shipping';
    const REWARD_TYPE_PRIZE = 'prize';

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
     * @var string id_product
     */
    public $prize;

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
            'prize' => [
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

    /**
     * Get translation(s) for reward type(s)
     *
     * @param string|null $rewardType
     *
     * @return array|string
     */
    public static function getRewardsTranslations($rewardType = null)
    {
        $translator = Context::getContext()->getTranslator();

        $translations = [
            self::REWARD_TYPE_POINTS => $translator->trans('Points', [], 'Modules.Gamification'),
            self::REWARD_TYPE_DISCOUNT => $translator->trans('Discount', [], 'Modules.Gamification'),
            self::REWARD_TYPE_FREE_SHIPPING => $translator->trans('Free shipping', [], 'Modules.Gamification'),
            self::REWARD_TYPE_PRIZE => $translator->trans('Prize', [], 'Modules.Gamification'),
        ];

        return (null === $rewardType) ? $translations : $translations[$rewardType];
    }
}
