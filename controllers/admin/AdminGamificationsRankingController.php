<?php
/**
 * This file is part of the Gamifications module.
 *
 * @author    Sarunas Jonusas, <jonusas.sarunas@gmail.com>
 * @copyright Copyright (c) permanent, Sarunas Jonusas
 * @license   Addons PrestaShop license limitation
 */

/**
 * Class AdminGamificationsRankingController
 */
class AdminGamificationsRankingController extends GamificationsAdminController
{
    /**
     * AdminGamificationsRankingController constructor.
     */
    public function __construct()
    {
        $this->table = GamificationsRank::$definition['table'];
        $this->identifier = GamificationsRank::$definition['primary'];
        $this->className = 'GamificationsRank';

        parent::__construct();
    }

    /**
     * Init list
     */
    protected function initList()
    {
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $this->fields_list = [
            GamificationsRank::$definition['primary'] => [
                'title' => $this->trans('ID', [], 'Modules.Gamifications.Admin'),
                'type' => 'text',
                'width' => 20,
            ],
            'name' => [
                'title' => $this->trans('Group name', [], 'Modules.Gamifications.Admin'),
                'type' => 'text',
            ],
            'must_spend_money' => [
                'title' => $this->trans('Must spend money', [], 'Modules.Gamifications.Admin'),
                'type' => 'text',
            ],
            'must_spend_points' => [
                'title' => $this->trans('Must spend points', [], 'Modules.Gamifications.Admin'),
                'type' => 'text',
            ],
            'date_upd' => [
                'title' => $this->trans('Created at', [], 'Modules.Gamifications.Admin'),
                'align' => 'center',
                'type' => 'date',
                'filter_key' => 'a!date_add',
            ],
            'date_add' => [
                'title' => $this->trans('Updated at', [], 'Modules.Gamifications.Admin'),
                'align' => 'center',
                'type' => 'date',
                'filter_key' => 'a!date_add',
            ],
            //@todo: catalog discounts count
            //@todo: group reduction
            //@todo: customers count
        ];
    }
}
