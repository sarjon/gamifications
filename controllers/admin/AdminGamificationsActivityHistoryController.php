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
        $this->_select = 'c.`email`';

        $this->_join = '
            LEFT JOIN `'._DB_PREFIX_.'customer` c
                ON c.`id_customer` = a.`id_customer`
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

        foreach ($this->_list as &$listItem) {
            $listItem['activity_type'] = $activityTypeTranslations[(int) $listItem['activity_type']];
        }
    }

    /**
     * Init list
     */
    protected function initList()
    {
        $this->list_no_link = true;

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
            ],
            'date_add' => [
                'title' => $this->trans('Activity date'),
                'align' => 'center',
            ],
        ];
    }
}
