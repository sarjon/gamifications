<?php

/**
 * Class AdminGamificationsCustomerController
 */
class AdminGamificationsCustomerController extends GamificationsAdminController
{
    /**
     * AdminGamificationsCustomerController constructor.
     */
    public function __construct()
    {
        $this->className = 'GamificationsCustomer';
        $this->table = GamificationsCustomer::$definition['table'];
        $this->identifier = GamificationsCustomer::$definition['primary'];

        parent::__construct();
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
     * Custom list rendering
     *
     * @return false|string
     */
    public function renderList()
    {
        $this->_select = 'c.`email`, c.`firstname`, c.`lastname`';

        $this->_join = '
            LEFT JOIN `'._DB_PREFIX_.'customer` c
                ON c.`id_customer` = a.`id_customer`
        ';

        $this->_where = 'AND c.`id_shop` = '.(int)$this->context->shop->id;

        return parent::renderList();
    }

    /**
     * Init list
     */
    protected function initList()
    {
        $this->list_no_link = true;

        $this->fields_list = [
            GamificationsCustomer::$definition['primary'] => [
                'title' => $this->trans('ID'),
                'width' => 20,
                'align' => 'center',
            ],
            'id_customer' => [
                'title' => $this->trans('ID Customer'),
                'align' => 'center',
            ],
            'email' => [
                'title' => $this->trans('Email'),
                'filter_key' => 'c!email',
            ],
            'firstname' => [
                'title' => $this->trans('Email'),
                'filter_key' => 'c!firstname',
            ],
            'lastname' => [
                'title' => $this->trans('Email'),
                'filter_key' => 'c!lastname',
            ],
            'total_points' => [
                'title' => $this->trans('Total points'),
                'align' => 'center',
            ],
            'spent_points' => [
                'title' => $this->trans('Spent points'),
                'align' => 'center',
            ],
        ];
    }
}