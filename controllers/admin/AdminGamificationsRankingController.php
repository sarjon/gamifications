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
     * Add custom links in page header
     */
    public function initPageHeaderToolbar()
    {
        if (empty($this->display)) {
            $this->page_header_toolbar_btn['new_rank'] = [
                'href' => self::$currentIndex.'&addgamifications_rank&token='.$this->token,
                'desc' => $this->trans('Add new Customer Rank'),
                'icon' => 'process-icon-new',
            ];
        }

        parent::initPageHeaderToolbar();
    }

    /**
     * Init list
     */
    protected function initList()
    {
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->lang = true;

        $this->fields_list = [
            GamificationsRank::$definition['primary'] => [
                'title' => $this->trans('ID', [], 'Modules.Gamifications.Admin'),
                'type' => 'text',
                'width' => 20,
            ],
            'name' => [
                'title' => $this->trans('Rank name', [], 'Modules.Gamifications.Admin'),
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
                'filter_key' => 'a!date_upd',
            ],
        ];
    }

    /**
     * Init customer rank form
     */
    protected function initForm()
    {
        $defaultCurrency = Currency::getDefaultCurrency();

        $this->fields_form = [
            'legend' => [
                'title' => 'Customer rank',
            ],
            'input' => [
                [
                    'name' => 'name',
                    'label' => $this->trans('Rank name', [], 'Modules.Gamifications.Admin'),
                    'hint' => $this->trans(
                        'E.g. Bronze, Silver, Gold or Level 1, Level 2 & etc.',
                        [],
                        'Modules.Gamifications.Admin'
                    ),
                    'type' => 'text',
                    'required' => true,
                    'lang' => true,
                ],
                [
                    'label' => $this->trans(
                        'Must spend money (%money%)',
                        ['%money%' => $defaultCurrency->getSign()],
                        'Modules.Gamifications.Admin'
                    ),
                    'hint' => $this->trans(
                        'How much money customer has to spend to get this rank. Calculated in default currency.',
                        [],
                        'Modules.Gamifications.Admin'
                    ),
                    'name' => 'must_spend_money',
                    'type' => 'text',
                    'class' => 'fixed-width-sm',
                    'suffix' => $defaultCurrency->getSign(),
                    'required' => true,
                ],
                [
                    'label' => $this->trans('Must spend points', [], 'Modules.Gamifications.Admin'),
                    'hint' => $this->trans(
                        'How many points customer has to spend to get this rank.',
                        [],
                        'Modules.Gamifications.Admin'
                    ),
                    'name' => 'must_spend_points',
                    'type' => 'text',
                    'class' => 'fixed-width-sm',
                    'suffix' => $this->trans('points', [], 'Modules.Gamifications.Admin'),
                    'required' => true,
                ],
                [
                    'label' => $this->trans('Group', [], 'Modules.Gamifications.Admin'),
                    'hint' =>
                        $this->trans('Assign unique customer group to each rank.', [], 'Modules.Gamifications.Admin')
                        .' '.
                        $this->trans(
                                'When customer reaches defined criterias he will be assigned to this group.',
                                [],
                                'Modules.Gamifications.Admin'
                            ),
                    'type' => 'select',
                    'name' => 'id_group',
                    'required' => true,
                    'options' => [
                        'id' => 'id_group',
                        'name' => 'name',
                        'query' => $this->getAvailableGroups(),
                    ],
                ],
                [
                    'type' => 'hidden',
                    'name' => 'id_shop',
                ],
            ],
            'submit' => [
                'title' => $this->trans('Save', [], 'Modules.Gamifications.Admin'),
            ],
        ];

        $this->fields_value['id_shop'] = (int) $this->context->shop->id;
    }

    /**
     * Get available customers groups
     *
     * @return array
     */
    public function getAvailableGroups()
    {
        $keys = ['PS_UNIDENTIFIED_GROUP', 'PS_GUEST_GROUP', 'PS_CUSTOMER_GROUP'];
        $excludeGroups = Configuration::getMultiple($keys);

        $groups = Group::getGroups($this->context->language->id, $this->context->shop->id);

        foreach ($groups as $key => $group) {
            if (in_array($group['id_group'], $excludeGroups)) {
                unset($groups[$key]);
            }
        }

        return $groups;
    }
}
