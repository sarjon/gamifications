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
 * Class GamificationsReward
 */
class GamificationsReward extends ObjectModel
{
    /**
     * Reward types
     */
    const REWARD_TYPE_POINTS = 1;
    const REWARD_TYPE_RANDOM_AMOUNT_OF_POINTS = 2;
    const REWARD_TYPE_DISCOUNT = 3;
    const REWARD_TYPE_FREE_SHIPPING = 4;
    const REWARD_TYPE_GIFT = 5;

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
     * @var string|array
     */
    public $description;

    /**
     * @var int
     */
    public $reward_type;

    /**
     * @var int
     */
    public $radius;

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
    public $id_product;

    /**
     * @var array
     */
    public static $definition = [
        'table' => 'gamifications_reward',
        'primary' => 'id_gamifications_reward',
        'fields' => [
            'reward_type' => [
                'type' => self::TYPE_INT,
                'size' => 50,
                'required' => true,
                'validate' => 'isUnsignedInt',

            ],
            'points' => [
                'type' => self::TYPE_INT,
                'required' => false,
                'validate' => 'isUnsignedInt',
            ],
            'radius' => [
                'type' => self::TYPE_INT,
                'required' => false,
                'validate' => 'isUnsignedInt',
            ],
            'id_product' => [
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
            'description' => [
                'type' => self::TYPE_STRING,
                'required' => false,
                'lang' => true,
                'size' => 255,
            ],
        ],
        'multishop' => true,
        'multilang' => true,
    ];

    /**
     * Get repository class name
     *
     * @return string
     */
    public static function getRepositoryClassName()
    {
        return 'GamificationsRewardRepository';
    }

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
        $module = Module::getInstanceByName('gamifications');

        $translations = [
            self::REWARD_TYPE_POINTS => $module->l('Fixed points', __CLASS__),
            self::REWARD_TYPE_DISCOUNT => $module->l('Discount', __CLASS__),
            self::REWARD_TYPE_FREE_SHIPPING => $module->l('Free shipping', __CLASS__),
            self::REWARD_TYPE_GIFT => $module->l('Gift', __CLASS__),
            self::REWARD_TYPE_RANDOM_AMOUNT_OF_POINTS => $module->l('Random amount of points', __CLASS__),
        ];

        return (null === $rewardType) ? $translations : $translations[$rewardType];
    }

    /**
     * Get all rewards types where type is points
     *
     * @return array
     */
    public static function getPointsRewardTypes()
    {
        return [
            self::REWARD_TYPE_POINTS,
            self::REWARD_TYPE_RANDOM_AMOUNT_OF_POINTS,
        ];
    }

    /**
     * Custom delete
     *
     * @return bool
     */
    public function delete()
    {
        if (!$this->canDelete()) {
            $module = Module::getInstanceByName('gamifications');

            $controller = Context::getContext()->controller;
            $controller->errors[] = $module->l('Reward is in use, it can not be deleted.', __CLASS__);
            $controller->warnings[] =
                $module->l('Reward cannot be delete if it is used in activity or points exchange.', __CLASS__);
            return false;
        }

        return parent::delete();
    }

    /**
     * Check if reward can be deleted
     *
     * @return bool
     */
    protected function canDelete()
    {
        if (!Validate::isLoadedObject($this)) {
            return true;
        }

        /** @var Gamifications $module */
        $module = Module::getInstanceByName('gamifications');
        $em = $module->getEntityManager();

        /** @var GamificationsRewardRepository $rewardRepository */
        $rewardRepository = $em->getRepository(__CLASS__);
        $isInUse = $rewardRepository->isRewardInUse($this->id);

        return !$isInUse;
    }
}
