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
     * @param bool $updateGroups
     *
     * @return bool
     */
    public function add($autoDate = true, $nullValues = false, $updateGroups = true)
    {
        $parentReturn = parent::add($autoDate, $nullValues);

        if ($updateGroups) {
            $this->updateGroups();
        }

        return $parentReturn;
    }

    /**
     * Custom update
     *
     * @param bool $nullValues
     * @param bool $updateGroups
     *
     * @return bool
     */
    public function update($nullValues = false, $updateGroups = true)
    {
        $parentReturn = parent::update($nullValues);

        if ($updateGroups) {
            $this->updateGroups();
        }

        return $parentReturn;
    }

    /**
     * Save entity
     *
     * @param bool $nullValues
     * @param bool $autoDate
     * @param bool $updateGroups
     *
     * @return bool
     */
    public function save($nullValues = false, $autoDate = true, $updateGroups = true)
    {
        return (int) $this->id > 0 ?
            $this->update($nullValues, $updateGroups) :
            $this->add($autoDate, $nullValues, $updateGroups);
    }

    /**
     * Update cutomers group relation
     *
     * @return bool
     */
    protected function updateGroups()
    {
        $db = Db::getInstance();
        $groupTableName = self::$definition['table'].'_group';

        $db->delete($groupTableName, 'id_gamifications_daily_reward = '.(int) $this->id);

        if (!is_array($this->groupBox) || empty($this->groupBox)) {
            return true;
        }

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
