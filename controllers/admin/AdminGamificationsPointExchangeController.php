<?php
/**
 * This file is part of the Gamifications module.
 *
 * @author    Sarunas Jonusas, <jonusas.sarunas@gmail.com>
 * @copyright Copyright (c) permanent, Sarunas Jonusas
 * @license   Addons PrestaShop license limitation
 */

/**
 * Class AdminGamificationsPointController
 */
class AdminGamificationsPointExchangeController extends GamificationsAdminController
{
    /**
     * AdminGamificationsPointExchangeController constructor.
     */
    public function __construct()
    {
        $this->className = 'GamificationsPointExchange';
        $this->table = GamificationsPointExchange::$definition['table'];
        $this->identifier = GamificationsPointExchange::$definition['primary'];
        Shop::addTableAssociation(GamificationsPointExchange::$definition['table'], ['type' => 'shop']);

        parent::__construct();
    }

    /**
     * Custom list rendering
     *
     * @return false|string
     */
    public function renderList()
    {
        $this->_select = 'grl.`name`';

        $this->_join = '
            LEFT JOIN `'._DB_PREFIX_.'gamifications_reward_lang` grl
                ON grl.`id_gamifications_reward` = a.`id_reward`
            LEFT JOIN `'._DB_PREFIX_.'gamifications_reward_shop` grs
                ON grs.`id_gamifications_reward` = grl.`id_gamifications_reward`
        ';

        $this->_where = '
            AND grl.`id_lang` = '.(int) $this->context->language->id.' 
            AND grs.`id_shop` = '.(int) $this->context->shop->id.'
        ';

        return parent::renderList();
    }

    /**
     * Add custom links in page header
     */
    public function initPageHeaderToolbar()
    {
        if (empty($this->display)) {
            $this->page_header_toolbar_btn['new_reward'] = [
                'href' => self::$currentIndex.'&addgamifications_point_exchange&token='.$this->token,
                'desc' => $this->trans('Add new Points Exchange'),
                'icon' => 'process-icon-new',
            ];
        }

        parent::initPageHeaderToolbar();
    }

    /**
     * Update status via AJAX
     */
    public function ajaxProcessStatusGamificationsPointExchange()
    {
        $idPointExchange = (int) Tools::getValue(GamificationsPointExchange::$definition['primary']);

        $pointExchange = new GamificationsPointExchange($idPointExchange, null, $this->context->shop->id);
        $pointExchange->active =  !((bool) $pointExchange->active);

        $response = [];
        $response['success'] = false;

        if (!Validate::isLoadedObject($pointExchange) || !$pointExchange->save()) {
            $response['error'] = true;
            $response['text'] = $this->trans('Failed to update', [], 'Modules.Gamifications.Admin');

            die(json_encode($response));
        }

        $response['success'] = true;
        $response['text'] = $this->trans('Successful update', [], 'Modules.Gamifications.Admin');

        die(json_encode($response));
    }

    /**
     * Display
     *
     * @return string
     */
    protected function displayHelp()
    {
        return $this->context->smarty->fetch(
            $this->module->getLocalPath().'views/templates/admin/points_exchange_info.tpl'
        );
    }

    /**
     * Initialize list fields
     */
    protected function initList()
    {
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $this->fields_list = [
            GamificationsPointExchange::$definition['primary'] => [
                'title' => $this->trans('ID', [], 'Modules.Gamifications.Admin'),
                'width' => 20,
                'type' => 'text',
            ],
            'points' => [
                'title' => $this->trans('Points', [], 'Modules.Gamifications.Admin'),
                'type' => 'text',
            ],
            'name' => [
                'title' => $this->trans('Reward name', [], 'Modules.Gamifications.Admin'),
                'type' => 'text',
                'filter_key' => 'grl!name',
            ],
            'active' => [
                'title' => $this->trans('Enabled', [], 'Modules.Gamifications.Admin'),
                'active' => 'status',
                'type' => 'bool',
                'ajax' => true,
                'orderby' => false,
                'align' => 'center',
            ],
            'times_exchanged' => [
                'title' => $this->trans('Times exchanged', [], 'Modules.Gamifications.Admin'),
                'type' => 'text',
                'align' => 'center',
            ],
        ];
    }

    protected function initForm()
    {
        $excludePointRewards = [
            GamificationsReward::REWARD_TYPE_POINTS,
            GamificationsReward::REWARD_TYPE_RANDOM_AMOUNT_OF_POINTS,
        ];

        /** @var GamificationsRewardRepository $rewardRepository */
        $rewardRepository = $this->module->getEntityManager()->getRepository('GamificationsReward');
        $availableRewards = $rewardRepository->findAllNamesAndIds(
            $this->context->language->id,
            $this->context->shop->id,
            $excludePointRewards
        );

        $this->fields_form = [
            'legend' => [
                'title' => $this->trans('Points exchange', [], 'Modules.Gamifications.Admin'),
            ],
            'input' => [
                [
                    'label' => $this->trans('Points needed', [], 'Modules.Gamifications.Admin'),
                    'type' => 'text',
                    'name' => 'points',
                    'hint' =>
                        $this->trans('Points needed to exchange it into reward', [], 'Modules.Gamifications.Admin'),
                    'class' => 'fixed-width-xl',
                    'suffix' => $this->trans('points', [], 'Modules.Gamifications.Admin'),
                    'required' => true,
                ],
                [
                    'label' => $this->trans('Choose reward', [], 'Modules.Gamifications.Admin'),
                    'type' => 'select',
                    'name' => 'id_reward',
                    'hint' => $this->trans(
                        'List of availabhe rewards. NOTE: You cannot exchange points into points.',
                        [],
                        'Modules.Gamifications.Admin'
                    ),
                    'required' => true,
                    'options' => [
                        'id' => 'id_gamifications_reward',
                        'name' => 'name',
                        'query' => $availableRewards,
                    ],
                ],
                [
                    'label' => $this->trans('Enabled', [], 'Modules.Gamifications.Admin'),
                    'type' => 'switch',
                    'values' => [
                        [
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->trans('Yes', [], 'Admin.Global'),
                        ],
                        [
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->trans('No', [], 'Admin.Global'),
                        ],
                    ],
                    'name' => 'active',
                    'hint' => $this->trans(
                        'If disabled then customers will not be able to use this exchange',
                        [],
                        'Modules.Gamifications.Admin'
                    ),
                ],
                [
                    'label' => $this->trans('Groups', [], 'Modules.Gamifications.Admin'),
                    'type' => 'group',
                    'name' => 'groupBox',
                    'values' => Group::getGroups($this->context->language->id, $this->context->shop->id),
                    'hint' => $this->trans(
                        'Which customer groups can get this reward by exchanging points',
                        [],
                        'Modules.Gamifications.Admin'
                    ),
                ],
            ],
            'submit' => [
                'title' => $this->trans('Save', [], 'Modules.Gamifications.Admin'),
            ],
        ];

        if (Shop::isFeatureActive()) {
            $this->fields_form['input'][] = [
                'type' => 'shop',
                'label' => $this->trans('Shop association', [], 'Modules.Gamifications.Admin'),
                'name' => 'checkBoxShopAsso',
            ];
        }
    }

    /**
     * Init fields form
     */
    protected function initFormFieldsValue()
    {
        if (!in_array($this->display, ['add', 'edit'])) {
            return;
        }

        $groups = Group::getGroups($this->context->language->id, $this->context->shop->id);
        $groupIds = [];

        if ('edit' == $this->display) {
            /** @var GamificationsPointExchangeRepository $pointExchangeRepository */
            $pointExchangeRepository = $this->module->getEntityManager()->getRepository('GamificationsPointExchange');
            $groupIds = $pointExchangeRepository->findAllGroupIds($this->object->id, $this->context->shop->id);
        }

        foreach ($groups as $group) {
            $this->fields_value['groupBox_'.$group['id_group']] = (bool) in_array($group['id_group'], $groupIds);
        }
    }
}
