<?php

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
            AND grl.`id_lang` = '.(int)$this->context->language->id.' 
            AND grs.`id_shop` = '.(int)$this->context->shop->id.'
        ';

        return parent::renderList();
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
                'title' => $this->trans('ID'),
                'width' => 20,
                'type' => 'text',
            ],
            'name' => [
                'title' => $this->trans('Reward name'),
                'type' => 'text',
                'filter_key' => 'grl!name',
            ],
            'points' => [
                'title' => $this->trans('Points'),
                'type' => 'text',
            ],
        ];
    }
}
