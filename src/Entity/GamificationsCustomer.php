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
    public $referral_code;

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
            'referral_code' => ['type' => self::TYPE_STRING, 'required' => false, 'validate' => 'isString'],
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
     * Create gamifications customer
     *
     * @param Customer $customer
     * @param bool $returnObject
     *
     * @return bool|GamificationsCustomer
     */
    public static function create(Customer $customer, $returnObject = false)
    {
        $gamificationsCustomer = new GamificationsCustomer();
        $gamificationsCustomer->total_points = 0;
        $gamificationsCustomer->spent_points = 0;
        $gamificationsCustomer->id_customer = (int) $customer->id;
        $gamificationsCustomer->active = true;
        $gamificationsCustomer->referral_code = strtolower(Tools::passwdGen(16));

        $created = $gamificationsCustomer->save();

        if ($returnObject) {
            return $gamificationsCustomer;
        }

        return $created;
    }

    /**
     * GamificationsCustomer constructor.
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

    /**
     * Remove points
     *
     * @param int $points
     *
     * @return GamificationsCustomer
     */
    public function removePoints($points)
    {
        $this->total_points = (int) $this->total_points - $points;

        return $this;
    }

    /**
     * @param int $points
     *
     * @return GamificationsCustomer
     */
    public function addSpentPoints($points)
    {
        $this->spent_points = (int) $this->spent_points + $points;

        return $this;
    }

    /**
     * Check if customer can exchange points into
     *
     * @param GamificationsPointExchange $pointsExchangeReward
     *
     * @return bool
     */
    public function checkExchangePoints(GamificationsPointExchange $pointsExchangeReward)
    {
        return $pointsExchangeReward->points <= $this->total_points;
    }
}