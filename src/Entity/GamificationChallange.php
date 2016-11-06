<?php

/**
 * Class GamificationChallange
 */
class GamificationChallange extends ObjectModel
{
    const CHALLANGE_TYPE_PRODUCTS_VIEWS = 'products_views';
    const CHALLANGE_TYPE_ORDERS_MADE = 'orders_made';

    //@todo: finish definition
    public static $definition = [
        'table' => 'gamification_challange',
        'primary' => 'id_gamification_challange',
        'fields' => [
            'name' => [
                'type' => self::TYPE_STRING,
                'required' => true,
                'lang' => true,
                'size' => '100',
                'validate' => 'isGenericName',
            ],
        ],
        'multishop' => true,
        'multilang' => true,
    ];

    /**
     * GamificationChallange constructor.
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
