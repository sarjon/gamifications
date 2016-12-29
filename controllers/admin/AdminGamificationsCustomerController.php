<?php
/**
 * This file is part of the Gamifications module.
 *
 * @author    Sarunas Jonusas, <jonusas.sarunas@gmail.com>
 * @copyright Copyright (c) permanent, Sarunas Jonusas
 * @license   Addons PrestaShop license limitation
 */

/**
 * Class AdminGamificationsCustomerController
 */
class AdminGamificationsCustomerController extends GamificationsAdminController
{
    /**
     * @var GamificationsCustomer
     */
    protected $object;

    /**
     * AdminGamificationsCustomerController constructor.
     */
    public function __construct()
    {
        $this->className = 'GamificationsCustomer';
        $this->table = GamificationsCustomer::$definition['table'];
        $this->identifier = GamificationsCustomer::$definition['primary'];;

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
        $this->_select = 'c.`email`, c.`firstname`, c.`lastname`, c.`date_add` AS `registration`';

        $this->_join = '
            INNER JOIN `'._DB_PREFIX_.'customer` c
                ON c.`id_customer` = a.`id_customer`
        ';

        $this->_where = 'AND c.`id_shop` = '.(int)$this->context->shop->id;
        $this->_where .= ' AND a.`id_shop` = '.(int)$this->context->shop->id;

        return parent::renderList();
    }

    /**
     * @return string
     */
    public function renderForm()
    {
        $customer = new Customer($this->object->id_customer);

        if (!Validate::isLoadedObject($customer)) {
            Tools::redirectAdmin(
                $this->context->link->getAdminLink(Gamifications::ADMIN_GAMIFICATIONS_CUSTOMER_CONTROLLER)
            );
        }

        return parent::renderForm();
    }

    /**
     * Log message if points where changed
     *
     * @return false|ObjectModel|void
     */
    public function processUpdate()
    {
        $pointsBeforeUpdate = (int) $this->object->total_points;

        $parentReturn = parent::processUpdate();

        if (!$parentReturn instanceof GamificationsCustomer) {
            return $parentReturn;
        }

        $pointsAfterUpdate = (int) $parentReturn->total_points;

        if ($pointsBeforeUpdate != $pointsAfterUpdate) {
            $reward = new GamificationsReward();
            $reward->reward_type = GamificationsReward::REWARD_TYPE_POINTS;
            $addedPoints = $pointsAfterUpdate - $pointsBeforeUpdate;
            GamificationsActivityHistory::log(
                $reward,
                $this->object->id_customer,
                GamificationsActivity::TYPE_MANUALLY_ADDED_POINTS,
                $addedPoints
            );
        }

        return $parentReturn;
    }

    /**
     * Init list
     */
    protected function initList()
    {
        $this->addRowAction('edit');
        $defaultCurrency = Currency::getDefaultCurrency();

        $this->fields_list = [
            GamificationsCustomer::$definition['primary'] => [
                'title' => $this->trans('ID', [], 'Modules.Gamifications.Admin'),
                'width' => 20,
                'align' => 'center',
            ],
            'id_customer' => [
                'title' => $this->trans('Customer ID', [], 'Modules.Gamifications.Admin'),
                'align' => 'center',
            ],
            'email' => [
                'title' => $this->trans('Email', [], 'Modules.Gamifications.Admin'),
                'filter_key' => 'c!email',
            ],
            'firstname' => [
                'title' => $this->trans('First name', [], 'Modules.Gamifications.Admin'),
                'filter_key' => 'c!firstname',
            ],
            'lastname' => [
                'title' => $this->trans('Last name', [], 'Modules.Gamifications.Admin'),
                'filter_key' => 'c!lastname',
            ],
            'total_points' => [
                'title' => $this->trans('Total points', [], 'Modules.Gamifications.Admin'),
                'align' => 'center',
            ],
            'spent_points' => [
                'title' => $this->trans('Spent points', [], 'Modules.Gamifications.Admin'),
                'align' => 'center',
            ],
            'spent_money' => [
                'title' => $this->trans('Spent money', [], 'Modules.Gamifications.Admin'),
                'align' => 'center',
                'prefix' => $defaultCurrency->getSign(),
            ],
            'registration' => [
                'title' => $this->trans('Registered', [], 'Modules.Gamifications.Admin'),
                'type' => 'date',
                'filter_key' => 'c!date_add',
            ],
        ];
    }

    /**
     * Init form
     */
    protected function initForm()
    {
        $this->fields_form = [
            'legend' => [
                'title' => $this->trans('Edit', [], 'Modules.Gamifications.Admin'),
            ],
            'description' => $this->trans(
                'Some fields are disabled, they only provide you information about customer and cannot be changed.',
                [],
                'Modules.Gamifications.Admin'
            ),
            'input' => [
                [
                    'label' => $this->trans('Email', [], 'Modules.Gamifications.Admin'),
                    'type' => 'text',
                    'name' => 'email',
                    'disabled' => true,
                ],
                [
                    'label' => $this->trans('First name', [], 'Modules.Gamifications.Admin'),
                    'type' => 'text',
                    'name' => 'first_name',
                    'disabled' => true,
                ],
                [
                    'label' => $this->trans('Last name', [], 'Modules.Gamifications.Admin'),
                    'type' => 'text',
                    'name' => 'last_name',
                    'disabled' => true,
                ],
                [
                    'label' => $this->trans('Spent points', [], 'Modules.Gamifications.Admin'),
                    'name' => 'spent_points',
                    'type' => 'text',
                    'disabled' => true,
                ],
                [
                    'label' => $this->trans('Customer points', [], 'Modules.Gamifications.Admin'),
                    'name' => 'total_points',
                    'type' => 'text',
                    'class' => 'fixed-width-lg',
                    'hint' => $this->trans('You can add or remove customer points', [], 'Modules.Gamifications.Admin'),
                ],
            ],
            'submit' => [
                'title' => $this->trans('Save', [], 'Modules.Gamifications.Admin'),
            ],
        ];
    }

    /**
     * Init form fields
     */
    protected function initFormFieldsValue()
    {
        $customer = new Customer($this->object->id_customer);

        $this->fields_value = [
            'email' => $customer->email,
            'first_name' => $customer->firstname,
            'last_name' => $customer->lastname,
        ];
    }
}
