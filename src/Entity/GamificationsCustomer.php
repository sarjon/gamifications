<?php

/**
 * Class GamificationsCustomer
 */
class GamificationsCustomer extends ObjectModel
{
    /**
     * @var int
     */
    public $id_customer;

    //@todo: Implement or get rid of
    /**
     * @var int
     */
    public $id_rank;

    /**
     * @var int
     */
    public $total_points;

    /**
     * @var int
     */
    public $spent_points;

    //@todo: implement active
    /**
     * @var bool
     */
    public $active;

    /**
     * @var string
     */
    public $date_add;

    /**
     * @var string
     */
    public $date_upd;

    /**
     * @var array
     */
    public static $definition = [
        'table' => 'gamifications_customer',
        'primary' => 'id_gamifications_customer',
        'fields' => [
            'id_customer' => ['type' => self::TYPE_INT, 'required' => true, 'validate' => 'isUnsignedInt'],
            'total_points' => ['type' => self::TYPE_INT, 'required' => false, 'validate' => 'isUnsignedInt'],
            'spent_points' => ['type' => self::TYPE_INT, 'required' => false, 'validate' => 'isUnsignedInt'],
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
        return 'GamificationsCustomerRepository';
    }

    /**
     * GamificationsCustomer constructor.
     *
     * @param int|null $id
     * @param int|null $idLang
     * @param int|null $idShop
     */
    public function __construct($id, $idLang = null, $idShop = null)
    {
        parent::__construct($id, $idLang, $idShop);
        Shop::addTableAssociation(self::$definition['table'], ['type' => 'shop']);
    }

    /**
     * Add points
     *
     * @param int $points
     * @param bool $commitChanges
     *
     * @return bool
     */
    public function addPoints($points, $commitChanges = true)
    {
        $this->total_points = (int) $this->total_points + (int) $points;

        if ($commitChanges) {
            return $this->save();
        }

        return true;
    }
}