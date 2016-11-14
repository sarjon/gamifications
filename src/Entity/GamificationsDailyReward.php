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
    public $groupBox;

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

    /**
     * Custom add
     *
     * @param bool $autoDate
     * @param bool $nullValues
     * @return bool
     */
    public function add($autoDate = true, $nullValues = false)
    {
        $parentReturn = parent::add($autoDate, $nullValues);

        $this->updateGroups();

        return $parentReturn;
    }

    /**
     * Custom add
     *
     * @param bool $nullValues
     * @return bool
     */
    public function update($nullValues = false)
    {
        $parentReturn = parent::update($nullValues);

        $this->updateGroups();

        return $parentReturn;
    }

    /**
     * Update cutomers group relation
     *
     * @return bool
     */
    protected function updateGroups()
    {
        if (!is_array($this->groupBox) || empty($this->groupBox)) {
            return true;
        }

        $db = Db::getInstance();
        $groupTableName = self::$definition['table'].'_group';

        $db->delete($groupTableName, 'id_gamifications_daily_reward = '.(int) $this->id);

        $data = [];
        foreach ($this->groupBox as $idGroup) {
            $data[] = [
                'id_gamifications_daily_reward' => (int) $this->id,
                'id_group' => (int) $idGroup,
            ];
        }

        return $db->insert($groupTableName, $data, false, true, Db::INSERT_IGNORE);
    }
}
