<?php
/**
 * This file is part of the Gamifications module.
 *
 * @author    Sarunas Jonusas, <jonusas.sarunas@gmail.com>
 * @copyright Copyright (c) permanent, Sarunas Jonusas
 * @license   Addons PrestaShop license limitation
 */

/**
 * Class GamificationsRank
 */
class GamificationsRank extends ObjectModel
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var int
     */
    public $id_group;

    /**
     * @var int
     */
    public $id_shop;

    /**
     * @var float
     */
    public $must_spend_money;

    /**
     * @var int
     */
    public $must_spend_points;

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
        'table' => 'gamifications_rank',
        'primary' => 'id_gamifications_rank',
        'fields' => [
            'name' => ['type' => self::TYPE_STRING, 'required' => true, 'validate' => 'isGenericName', 'lang' => true],
            'id_group' => ['type' => self::TYPE_INT, 'required' => true, 'validate' => 'isUnsignedId'],
            'id_shop' => ['type' => self::TYPE_INT, 'required' => true, 'validate' => 'isUnsignedId'],
            'must_spend_money' => ['type' => self::TYPE_FLOAT, 'required' => true, 'validate' => 'isFloat'],
            'must_spend_points' => ['type' => self::TYPE_INT, 'required' => true, 'validate' => 'isUnsignedInt'],
            'date_add' => ['type' => self::TYPE_DATE, 'required' => false, 'validate' => 'isDate'],
            'date_upd' => ['type' => self::TYPE_DATE, 'required' => false, 'validate' => 'isDate'],
        ],
        'multilang' => true,
    ];

    /**
     * Get repository class name
     *
     * @return string
     */
    public static function getRepositoryClassName()
    {
        return 'GamificationsRankRepository';
    }

    /**
     * Create rank order to track which orders are processed
     *
     * @param Order $order
     */
    public static function createRankOrder(Order $order)
    {
        $insert = [
            'id_customer' => (int) $order->id_customer,
            'id_order'    => (int) $order->id,
            'active'      => 1,
        ];

        Db::getInstance()->insert('gamifications_rank_order', $insert);
    }
}
