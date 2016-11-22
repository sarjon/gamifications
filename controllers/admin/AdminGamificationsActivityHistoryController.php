<?php

/**
 * Class AdminGamificationsActivityHistoryController
 */
class AdminGamificationsActivityHistoryController extends GamificationsAdminController
{
    /**
     * AdminGamificationsActivityHistoryController constructor.
     */
    public function __construct()
    {
        $this->className = 'GamificationsActivityHistory';
        $this->table = GamificationsActivityHistory::$definition['table'];
        $this->identifier = GamificationsActivityHistory::$definition['primary'];

        parent::__construct();
    }

    /**
     * Custom list rendering
     *
     * @return false|string
     */
    public function renderList()
    {
        $this->_select = 'c.`email`, rl.`name` AS `reward_name`';

        $this->_join = '
            LEFT JOIN `'._DB_PREFIX_.'customer` c
                ON c.`id_customer` = a.`id_customer`
        ';

        $this->_join .= '
            LEFT JOIN `'._DB_PREFIX_.'gamifications_reward_lang` rl
                ON rl.`id_gamifications_reward` = a.`id_reward`
                    AND rl.`id_lang` = '.(int)$this->context->language->id.'
        ';

        $this->_where = 'AND c.`id_shop` = '.(int)$this->context->shop->id;

        return parent::renderList();
    }

    /**
     * Remove Add new from tool bar
     */
    public function initToolbar()
    {
        parent::initToolbar();

        unset($this->toolbar_btn['new']);
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

        $activityTypeTranslations = GamificationsActivity::getActivityTypeTranslations();
        $rewardsTranslations = GamificationsReward::getRewardsTranslations();
        $rewardTypePoints = [
            GamificationsReward::REWARD_TYPE_POINTS,
            GamificationsReward::REWARD_TYPE_RANDOM_AMOUNT_OF_POINTS,
        ];

        foreach ($this->_list as &$listItem) {

            if (!in_array($listItem['reward_type'], $rewardTypePoints)) {
                $listItem['points'] = '-';
            }

            $listItem['activity_type'] = $activityTypeTranslations[(int) $listItem['activity_type']];
            $listItem['reward_type'] = $rewardsTranslations[(int) $listItem['reward_type']];
        }
    }

    /**
     * Init list
     */
    protected function initList()
    {
        $this->list_no_link = true;
        $this->explicitSelect = true;
        $this->bulk_actions = [
            'delete' => [
                'text' => $this->trans('Delete selected'),
                'confirm' => $this->trans('Delete selected items?'),
            ],
        ];

        $this->fields_list = [
            GamificationsActivityHistory::$definition['primary'] => [
                'title' => $this->trans('ID'),
                'width' => 20,
                'align' => 'center',
            ],
            'email' => [
                'title' => $this->trans('Email'),
                'align' => 'center',
            ],
            'activity_type' => [
                'title' => $this->trans('Activity type'),
                'align' => 'center',
            ],
            'reward_name' => [
                'title' => $this->trans('Reward'),
                'align' => 'center',
                'filter_key' => 'rl!name',
            ],
            'reward_type' => [
                'title' => $this->trans('Reward type'),
                'align' => 'center',
            ],
            'points' => [
                'title' => $this->trans('Points'),
                'align' => 'center',
            ],
            'date_add' => [
                'title' => $this->trans('Activity date'),
                'align' => 'center',
                'type' => 'date',
                'filter_key' => 'a!date_add',
            ],
        ];
    }
}
