<?php
/**
 * This file is part of the Gamifications module.
 *
 * @author    Sarunas Jonusas, <jonusas.sarunas@gmail.com>
 * @copyright Copyright (c) permanent, Sarunas Jonusas
 * @license   Addons PrestaShop license limitation
 */

/**
 * Class AdminGamificationsShoppingPointController
 */
class AdminGamificationsShoppingPointController extends GamificationsAdminController
{
    /**
     * Init custom content
     */
    public function initContent()
    {
        parent::initContent();

        $this->content .= $this->renderShoppingPointOptions();

        $this->context->smarty->assign('content', $this->content);
    }

    /**
     * Custom form processing
     */
    public function postProcess()
    {
        if (!Tools::isSubmit('submitAddconfiguration')) {
            return;
        }

        $configurations = [];

        $configurations[GamificationsConfig::SHOPPING_POINTS_RATIO] =
            (int) Tools::getValue(GamificationsConfig::SHOPPING_POINTS_RATIO);
        $configurations[GamificationsConfig::SHOPPING_POINTS_ORDER_STATES] =
            json_encode(Tools::getValue(GamificationsConfig::SHOPPING_POINTS_ORDER_STATES.'_selected', []));
        $configurations[GamificationsConfig::SHOPPING_POINTS_INCLUDE_SHIPPNG_PRICE] =
            (int) Tools::getValue(GamificationsConfig::SHOPPING_POINTS_INCLUDE_SHIPPNG_PRICE);

        $success = true;
        foreach ($configurations as $name => $value) {
            $success &= Configuration::updateValue($name, $value);
        }

        if (!$success) {
            $this->errors[] = $this->trans('Failed update', [], 'Modules.Gamifications.Admin');
        }

        $this->confirmations[] = $this->trans('Successful update', [], 'Modules.Gamifications.Admin');
    }

    /**
     * Render shopping points options
     */
    protected function renderShoppingPointOptions()
    {
        $referralOptionsForm = new HelperForm();

        $this->initForm();
        $fieldsForm = [];
        $fieldsForm[0]['form'] = $this->fields_form;

        $referralOptionsForm->tpl_vars = [
            'fields_value' => [
                GamificationsConfig::SHOPPING_POINTS_RATIO =>
                    (int) Configuration::get(GamificationsConfig::SHOPPING_POINTS_RATIO),
                GamificationsConfig::SHOPPING_POINTS_ORDER_STATES =>
                    json_decode(Configuration::get(GamificationsConfig::SHOPPING_POINTS_ORDER_STATES), true),
                GamificationsConfig::SHOPPING_POINTS_INCLUDE_SHIPPNG_PRICE =>
                    (int) Configuration::get(GamificationsConfig::SHOPPING_POINTS_INCLUDE_SHIPPNG_PRICE),
            ],
        ];

        $this->content .= $referralOptionsForm->generateForm($fieldsForm);
    }

    /**
     * Init form
     */
    protected function initForm()
    {
        $idCurrency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
        $currency = new Currency($idCurrency);

        $this->fields_form = [
            'legend' => [
                'title' => $this->trans('Shopping points settings', [], 'Modules.Gamifications.Admin'),
            ],
            'input' => [
                [
                    'name' => GamificationsConfig::SHOPPING_POINTS_RATIO,
                    'type' => 'text',
                    'label' => $this->trans('Points ratio', [], 'Modules.Gamifications.Admin'),
                    'hint' => $this->trans(
                        'Give X points for every spent %money%.',
                        ['%money%' => $currency->iso_code],
                        'Modules.Gamifications.Admin'
                    ),
                    'suffix' => $this->trans(
                        'points = for every spent %money%',
                        ['%money%' => $currency->iso_code],
                        'Modules.Gamifications.Admin'
                    ),
                    'class' => 'fixed-width-lg',
                ],
                [
                    'type' => 'swap',
                    'label' => $this->trans('Order states', [], 'Modules.Gamifications.Admin'),
                    'hint' => $this->trans(
                        'Give points after order state is one of selected. If no orders states are selected then points will be given after placing an order.',
                        [],
                        'Modules.Gamifications.Admin'
                    ),
                    'name' => GamificationsConfig::SHOPPING_POINTS_ORDER_STATES,
                    'multiple' => true,
                    'options' => [
                        'query' => OrderState::getOrderStates($this->context->language->id),
                        'id' => 'id_order_state',
                        'name' => 'name'
                    ],
                ],
                [
                    'type' => 'switch',
                    'label' => $this->trans('Include shipping price', [], 'Modules.Gamifications.Admin'),
                    'name' => GamificationsConfig::SHOPPING_POINTS_INCLUDE_SHIPPNG_PRICE,
                    'hint' => $this->trans(
                        'Enabled to include shipping price when calculating points',
                        [],
                        'Modules.Gamifications.Admin'
                    ),
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'shopping_point_shipping_price_on',
                            'value' => 1,
                        ],
                        [
                            'id' => 'shopping_point_shipping_price_off',
                            'value' => 0,
                        ],
                    ],
                ],
            ],
            'submit' => [
                'title' => $this->trans('Save', [], 'Modules.Gamifications.Admin'),
            ],
        ];
    }
}
