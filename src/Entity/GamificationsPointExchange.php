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
 * Class GamificationsPointExchange
 */
class GamificationsPointExchange extends ObjectModel
{
    /**
     * @var array This is saved into _group table
     */
    public $groupBox;

    /**
     * @var int
     */
    public $id_reward;

    /**
     * @var int
     */
    public $points;

    /**
     * @var int
     */
    public $times_exchanged;

    /**
     * @var bool
     */
    public $active;

    /**
     * @var DateTime
     */
    public $date_add;

    /**
     * @var DateTime
     */
    public $date_upd;

    /**
     * @var array
     */
    public static $definition = [
        'table' => 'gamifications_point_exchange',
        'primary' => 'id_gamifications_point_exchange',
        'fields' => [
            'points' => ['type' => self::TYPE_INT, 'required' => true, 'validate' => 'isUnsignedInt'],
            'id_reward' => ['type' => self::TYPE_INT, 'required' => true, 'validate' => 'isUnsignedInt'],
            'times_exchanged' => ['type' => self::TYPE_INT, 'required' => false, 'validate' => 'isUnsignedInt'],
            'active' => ['type' => self::TYPE_BOOL, 'required' => false, 'validate' => 'isBool'],
            'date_add' => ['type' => self::TYPE_DATE, 'required' => false, 'validate' => 'isDate'],
            'date_upd' => ['type' => self::TYPE_DATE, 'required' => false, 'validate' => 'isDate'],
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
        return 'GamificationsPointExchangeRepository';
    }

    /**
     * GamificationsPointExchange constructor.
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
     * Custom update
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
        if (Tools::isSubmit('ajax')) {
            return true;
        }

        $db = Db::getInstance();
        $groupTableName = self::$definition['table'].'_group';

        $db->delete($groupTableName, self::$definition['primary'].' = '.(int) $this->id);

        if (!is_array($this->groupBox) || empty($this->groupBox)) {
            return true;
        }

        $data = [];
        foreach ($this->groupBox as $idGroup) {
            $data[] = [
                self::$definition['primary'] => (int) $this->id,
                'id_group' => (int) $idGroup,
            ];
        }

        return $db->insert($groupTableName, $data, false, true, Db::INSERT_IGNORE);
    }
}
