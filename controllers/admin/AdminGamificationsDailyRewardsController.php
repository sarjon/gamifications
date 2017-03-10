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
 * Class AdminGamificationsDailyRewardsController
 */
class AdminGamificationsDailyRewardsController extends GamificationsAdminController
{
    /**
     * AdminGamificationsDailyRewardsController constructor.
     */
    public function __construct()
    {
        $this->className = 'GamificationsDailyReward';
        $this->table = GamificationsDailyReward::$definition['table'];
        $this->identifier = GamificationsDailyReward::$definition['primary'];
        Shop::addTableAssociation(GamificationsDailyReward::$definition['table'], ['type' => 'shop']);

        parent::__construct();
    }

    /**
     * Add custom links in page header
     */
    public function initPageHeaderToolbar()
    {
        if (empty($this->display)) {
            $this->page_header_toolbar_btn['new_daily_reward'] = [
                'href' => self::$currentIndex.'&addgamifications_daily_reward&token='.$this->token,
                'desc' => $this->l('Add new Daily Reward'),
                'icon' => 'process-icon-new',
            ];
        }

        parent::initPageHeaderToolbar();
    }

    /**
     * Add addition query to list
     *
     * @return false|string
     */
    public function renderList()
    {
        $this->_select = 'grl.`name`, gr.`reward_type`';

        $this->_join = '
            LEFT JOIN `'._DB_PREFIX_.'gamifications_reward` gr
                ON gr.`id_gamifications_reward` = a.`id_reward`
            LEFT JOIN `'._DB_PREFIX_.'gamifications_reward_lang` grl
                ON grl.`id_gamifications_reward` = gr.`id_gamifications_reward`
                    AND grl.`id_lang` = '.(int)$this->context->language->id.'
            LEFT JOIN `'._DB_PREFIX_.'gamifications_reward_shop` grs
                ON grs.`id_gamifications_reward` = gr.`id_gamifications_reward`
                    AND grs.`id_shop` = '.(int)$this->context->shop->id.'
        ';

        return parent::renderList();
    }

    /**
     * Customize list
     *
     * @param int $idLang
     * @param null $orderBy
     * @param null $orderWay
     * @param int $start
     * @param null $limit
     * @param bool $idLangShop
     */
    public function getList($idLang, $orderBy = null, $orderWay = null, $start = 0, $limit = null, $idLangShop = false)
    {
        parent::getList($idLang, $orderBy, $orderWay, $start, $limit, $idLangShop);

        if (empty($this->_list)) {
            return;
        }

        $rewardTypeTranslations = GamificationsReward::getRewardsTranslations();

        foreach ($this->_list as &$listItem) {
            $listItem['reward_type'] = $rewardTypeTranslations[$listItem['reward_type']];
        }
    }

    /**
     * Update status via AJAX
     */
    public function ajaxProcessStatusGamificationsDailyReward()
    {
        $idDailyReward = (int) Tools::getValue('id_gamifications_daily_reward');

        $dailyReward = new GamificationsDailyReward($idDailyReward, null, $this->context->shop->id);
        $dailyReward->active =  !((bool) $dailyReward->active);

        $response = [];
        $response['success'] = false;

        if (!Validate::isLoadedObject($dailyReward) || !$dailyReward->save()) {
            $response['error'] = true;
            $response['text'] = $this->l('Failed to update');

            die(json_encode($response));
        }

        $response['success'] = true;
        $response['text'] = $this->l('Successful update');

        die(json_encode($response));
    }

    /**
     * Initialize list
     */
    protected function initList()
    {
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $this->fields_list = [
            'id_gamifications_daily_reward' => [
                'title' => $this->l('ID'),
                'type' => 'text',
                'width' => 20,
            ],
            'name' => [
                'title' => $this->l('Reward name'),
                'type' => 'text',
            ],
            'reward_type' => [
                'title' => $this->l('Reward type'),
                'type' => 'select',
                'list' => GamificationsReward::getRewardsTranslations(),
                'filter_key' => 'gr!reward_type',
            ],
            'boost' => [
                'title' => $this->l('Boost'),
                'type' => 'text',
                'align' => 'center',
            ],
            'active' => [
                'title' => $this->l('Enabled'),
                'active' => 'status',
                'type' => 'bool',
                'ajax' => true,
                'orderby' => false,
                'align' => 'center',
            ],
            'times_won' => [
                'title' => $this->l('Times won'),
                'type' => 'text',
                'align' => 'center',
            ],
        ];
    }

    /**
     * Initialize form
     */
    protected function initForm()
    {
        /** @var GamificationsRewardRepository $rewardRepository */
        $rewardRepository = $this->module->getEntityManager()->getRepository('GamificationsReward');
        $availableRewards =
            $rewardRepository->findAllNamesAndIds($this->context->language->id, $this->context->shop->id);

        if (empty($availableRewards)) {
            $availableRewards[] = [
                'id_gamifications_reward' => '',
                'name' => $this->l('No available rewards'),
            ];
        }

        $this->fields_form = [
            'legend' => [
                'title' => $this->l('Daily reward'),
            ],
            'input' => [
                [
                    'label' => $this->l('Choose reward'),
                    'hint' => $this->l('Choose reward that customer will have a chance to get.'),
                    'type' => 'select',
                    'name' => 'id_reward',
                    'required' => true,
                    'options' => [
                        'id' => 'id_gamifications_reward',
                        'name' => 'name',
                        'query' => $availableRewards,
                    ],
                ],
                [
                    'label' => $this->l('Boost'),
                    'type' => 'text',
                    'name' => 'boost',
                    'hint' => $this->l('The chance of getting this reward.').' '.
                        $this->l('The higher the boost the bigger chance of getting this reward.'),
                    'class' => 'fixed-width-xl',
                    'desc' =>
                        $this->l('Recommended boost is between 1 (less chance to get) and 100 (more chance to get).'),
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
                    'hint' => $this->l('If disabled then no one will be able to get this reward at Daily Rewards'),
                ],
                [
                    'label' => $this->l('Groups'),
                    'type' => 'group',
                    'name' => 'groupBox',
                    'values' => Group::getGroups($this->context->language->id, $this->context->shop->id),
                    'hint' => $this->l('Which customer groups can get this Daily Reward'),
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
            /** @var GamificationsDailyRewardRepository $dailyRewardRepository */
            $dailyRewardRepository = $this->module->getEntityManager()->getRepository('GamificationsDailyReward');
            $groupIds = $dailyRewardRepository->findAllGroupIds($this->object->id, $this->context->shop->id);
        }

        foreach ($groups as $group) {
            $this->fields_value['groupBox_'.$group['id_group']] = in_array($group['id_group'], $groupIds);
        }
    }

    /**
     * Display help message if setting is enabled
     */
    protected function displayHelp()
    {
        return $this->context->smarty->fetch(
            $this->module->getLocalPath().'views/templates/admin/daily_rewards_info.tpl'
        );
    }
}
