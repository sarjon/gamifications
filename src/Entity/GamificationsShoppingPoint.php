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
 * Class GamificationsShoppingPoint
 */
class GamificationsShoppingPoint extends ObjectModel
{
    /**
     * @var int
     */
    public $id_customer;

    /**
     * @var int
     */
    public $id_order;

    /**
     * @var bool
     */
    public $active;

    /**
     * @var int
     */
    public $id_shop;

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
        'table' => 'gamifications_shopping_point',
        'primary' => 'id_gamifications_shopping_point',
        'fields' => [
            'id_customer' => ['type' => self::TYPE_INT, 'required' => true, 'validate' => 'isUnsignedInt'],
            'id_order' => ['type' => self::TYPE_INT, 'required' => true, 'validate' => 'isUnsignedInt'],
            'id_shop' => ['type' => self::TYPE_INT, 'required' => true, 'validate' => 'isUnsignedInt'],
            'active' => ['type' => self::TYPE_BOOL, 'required' => true, 'validate' => 'isBool'],
            'date_add' => ['type' => self::TYPE_DATE, 'required' => false, 'validate' => 'isDate'],
            'date_upd' => ['type' => self::TYPE_DATE, 'required' => false, 'validate' => 'isDate'],
        ],
    ];

    /**
     * Create ShoppingPoint object
     *
     * @param Order $order
     * @param $returnObject
     *
     * @return bool|GamificationsShoppingPoint
     */
    public static function create(Order $order, $returnObject = false)
    {
        $shoppingPoint = new GamificationsShoppingPoint();
        $shoppingPoint->id_customer = (int) $order->id_customer;
        $shoppingPoint->id_order = (int) $order->id;
        $shoppingPoint->id_shop = (int) Context::getContext()->shop->id;
        $shoppingPoint->active = true;

        $created = $shoppingPoint->save();

        if ($returnObject) {
            return $shoppingPoint;
        }

        return $created;
    }

    /**
     * Get repository name
     *
     * @return string
     */
    public static function getRepositoryClassName()
    {
        return 'GamificationsShoppingPointRepository';
    }
}
