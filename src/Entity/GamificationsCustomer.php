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
 * Class GamificationsCustomer
 */
class GamificationsCustomer extends ObjectModel
{
    /**
     * @var int
     */
    public $id_customer;

    /**
     * @var int
     */
    public $id_shop;

    /**
     * @var int
     */
    public $total_points = 0;

    /**
     * @var int
     */
    public $spent_points = 0;

    /**
     * @var float
     */
    public $spent_money = 0.0;

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
            'id_shop' => ['type' => self::TYPE_INT, 'required' => true, 'validate' => 'isUnsignedInt'],
            'total_points' => ['type' => self::TYPE_INT, 'required' => false, 'validate' => 'isUnsignedInt'],
            'spent_points' => ['type' => self::TYPE_INT, 'required' => false, 'validate' => 'isUnsignedInt'],
            'spent_money' => ['type' => self::TYPE_FLOAT, 'required' => false, 'validate' => 'isFloat'],
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
        $context = Context::getContext();

        $gamificationsCustomer                = new GamificationsCustomer();
        $gamificationsCustomer->total_points  = 0;
        $gamificationsCustomer->spent_points  = 0;
        $gamificationsCustomer->spent_money   = 0.0;
        $gamificationsCustomer->id_customer   = (int) $customer->id;
        $gamificationsCustomer->active        = true;
        $gamificationsCustomer->referral_code = strtolower(Tools::passwdGen(16));
        $gamificationsCustomer->id_shop       = (int) $context->shop->id;

        $created = $gamificationsCustomer->save();

        if ($returnObject) {
            return $gamificationsCustomer;
        }

        return $created;
    }

    /**
     * Remove gamifications customer
     *
     * @param Customer $customer
     *
     * @return bool
     */
    public static function remove(Customer $customer)
    {
        /** @var Gamifications $gamifications */
        $gamifications = Module::getInstanceByName('gamifications');

        $context = Context::getContext();
        $em      = $gamifications->getEntityManager();

        /** @var GamificationsCustomerRepository $customerRepository */
        $customerRepository      = $em->getRepository('GamificationsCustomer');
        $idGamificationsCustomer = $customerRepository->findIdByCustomerId($customer->id, $context->shop->id);

        if (null === $idGamificationsCustomer) {
            return true;
        }

        $gamificationsCustomer = new GamificationsCustomer($idGamificationsCustomer);

        return $gamificationsCustomer->delete();
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

    /**
     * Add spent money
     *
     * @param float $money
     * @param bool $commit
     *
     * @return $this
     */
    public function addSpentMoney($money, $commit = true)
    {
        $this->spent_money = (float) $this->spent_money + (float) $money;

        if ($commit) {
            $this->save();
        }

        return $this;
    }
}
