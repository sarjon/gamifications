<?php

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
     * @var array
     */
    public static $definition = [
        'table' => 'gamifications_point_exchange',
        'primary' => 'id_gamifications_point_exchange',
        'fields' => [
            'points' => ['type' => self::TYPE_INT, 'required' => true, 'validate' => 'isUnsignedInt'],
            'id_reward' => ['type' => self::TYPE_INT, 'required' => true, 'validate' => 'isUnsignedInt'],
        ],
        'multishop' => true,
    ];

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
