<?php

/**
 * Class GamificationsDailyReward
 */
class GamificationsDailyReward extends ObjectModel
{
    /**
     * @var int
     */
    public $id_reward;

    /**
     * @var float
     */
    public $boost = 1;

    /**
     * @var bool
     */
    public $active;

    /**
     * @var int
     */
    public $times_won;

    /**
     * @var array
     */
    public static $definition = [
        'table' => 'gamifications_daily_reward',
        'primary' => 'id_gamifications_daily_reward',
        'fields' => [
            'id_reward' => ['type' => self::TYPE_INT, 'required' => true, 'validate' => 'isUnsignedInt'],
            'boost' => ['type' => self::TYPE_INT, 'required' => true, 'validate' => 'isUnsignedInt'],
            'active' => ['type' => self::TYPE_BOOL, 'required' => false, 'validate' => 'isBool'],
            'times_won' => ['type' => self::TYPE_INT, 'required' => false, 'validate' => 'isUnsignedInt'],
        ],
        'multishop' => true,
    ];

    /**
     * Get repository class name
     *
     * @return string
     */
    public static function getRepositoryClassName()
    {
        return 'GamificationsDailyRewardRepository';
    }

    /**
     * GamificationsDailyReward constructor.
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
