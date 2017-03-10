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
                'desc' => $this->l('Add new Points Exchange'),
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
            $response['text'] = $this->l('Failed to update');

            die(json_encode($response));
        }

        $response['success'] = true;
        $response['text'] = $this->l('Successful update');

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
                'title' => $this->l('ID'),
                'width' => 20,
                'type' => 'text',
            ],
            'points' => [
                'title' => $this->l('Points'),
                'type' => 'text',
            ],
            'name' => [
                'title' => $this->l('Reward name'),
                'type' => 'text',
                'filter_key' => 'grl!name',
            ],
            'active' => [
                'title' => $this->l('Enabled'),
                'active' => 'status',
                'type' => 'bool',
                'ajax' => true,
                'orderby' => false,
                'align' => 'center',
            ],
            'times_exchanged' => [
                'title' => $this->l('Times exchanged'),
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

        if (empty($availableRewards)) {
            $availableRewards[] = [
                'id_gamifications_reward' => '',
                'name' => $this->l('No available rewards'),
            ];
        }

        $this->fields_form = [
            'legend' => [
                'title' => $this->l('Points exchange'),
            ],
            'input' => [
                [
                    'label' => $this->l('Points needed'),
                    'type' => 'text',
                    'name' => 'points',
                    'hint' => $this->l('Points needed to exchange it into reward'),
                    'class' => 'fixed-width-xl',
                    'suffix' => $this->l('points'),
                    'required' => true,
                ],
                [
                    'label' => $this->l('Choose reward'),
                    'type' => 'select',
                    'name' => 'id_reward',
                    'hint' => $this->l('List of available rewards. NOTE: You cannot exchange points into points.'),
                    'required' => true,
                    'options' => [
                        'id' => 'id_gamifications_reward',
                        'name' => 'name',
                        'query' => $availableRewards,
                    ],
                ],
                [
                    'label' => $this->l('Enabled'),
                    'type' => 'switch',
                    'values' => [
                        [
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes'),
                        ],
                        [
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ],
                    ],
                    'name' => 'active',
                    'hint' => $this->l('If disabled then customers will not be able to use this exchange'),
                ],
                [
                    'label' => $this->l('Groups'),
                    'type' => 'group',
                    'name' => 'groupBox',
                    'values' => Group::getGroups($this->context->language->id, $this->context->shop->id),
                    'hint' => $this->l('Which customer groups can get this reward by exchanging points'),
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
            ],
        ];

        if (Shop::isFeatureActive()) {
            $this->fields_form['input'][] = [
                'type' => 'shop',
                'label' => $this->l('Shop association'),
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
