<?php
/**
 * This file is part of the Gamifications module.
 *
 * @author    Sarunas Jonusas, <jonusas.sarunas@gmail.com>
 * @copyright Copyright (c) permanent, Sarunas Jonusas
 * @license   Addons PrestaShop license limitation
 */

/**
 * Class AdminGamificationsReferralController
 */
class AdminGamificationsReferralController extends GamificationsAdminController
{
    /**
     * Init custom content
     */
    public function initContent()
    {
        $this->initReferralOptions();

        parent::initContent();
    }

    public function setMedia()
    {
        parent::setMedia();

        $referralProgramJsUri = $this->module->getPathUri().'views/js/admin/referral_program.js';

        $this->addJS($referralProgramJsUri);
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

        $configurations[GamificationsConfig::REFERRAL_REWARD_TIME] =
            (int) Tools::getValue(GamificationsConfig::REFERRAL_REWARD_TIME);
        $configurations[GamificationsConfig::REFERRAL_REWARD] =
            (int) Tools::getValue(GamificationsConfig::REFERRAL_REWARD);
        $configurations[GamificationsConfig::REFERRAL_NEW_CUSTOMER_REWARD_ENABLED] =
            (int) Tools::getValue(GamificationsConfig::REFERRAL_NEW_CUSTOMER_REWARD_ENABLED);
        $configurations[GamificationsConfig::REFERRAL_NEW_CUSTOMER_ORDER_STATES] =
            json_encode(Tools::getValue(GamificationsConfig::REFERRAL_NEW_CUSTOMER_ORDER_STATES.'_selected', []));
        $configurations[GamificationsConfig::REFERRAL_NEW_CUSTOMER_REWARD] =
            (int) Tools::getValue(GamificationsConfig::REFERRAL_NEW_CUSTOMER_REWARD);

        $idShopGroup = (int) $this->context->shop->id_shop_group;
        $idShop = (int) $this->context->shop->id;

        $success = true;
        foreach ($configurations as $name => $value) {
            $success &= Configuration::updateValue($name, $value, false, $idShopGroup, $idShop);
        }

        if (!$success) {
            $this->errors[] = $this->trans('Failed update', [], 'Modules.Gamifications.Admin');
        }

        $this->confirmations[] = $this->trans('Succesful update', [], 'Modules.Gamifications.Admin');
    }

    /**
     * Init free input type values
     */
    protected function initReferralOptions()
    {
        $referralOptionsForm = new HelperForm();

        $this->initForm();
        $fieldsForm = [];
        $fieldsForm[0]['form'] = $this->fields_form;

        $referralOptionsForm->tpl_vars = [
            'fields_value' => [
                GamificationsConfig::REFERRAL_REWARD_TIME =>
                    (int) Configuration::get(GamificationsConfig::REFERRAL_REWARD_TIME),
                GamificationsConfig::REFERRAL_REWARD =>
                    (int) Configuration::get(GamificationsConfig::REFERRAL_REWARD),
                GamificationsConfig::REFERRAL_NEW_CUSTOMER_REWARD_ENABLED =>
                    (int) Configuration::get(GamificationsConfig::REFERRAL_NEW_CUSTOMER_REWARD_ENABLED),
                GamificationsConfig::REFERRAL_NEW_CUSTOMER_ORDER_STATES =>
                    json_decode(Configuration::get(GamificationsConfig::REFERRAL_NEW_CUSTOMER_ORDER_STATES)),
                GamificationsConfig::REFERRAL_NEW_CUSTOMER_REWARD =>
                    (int) json_decode(Configuration::get(GamificationsConfig::REFERRAL_NEW_CUSTOMER_REWARD)),
            ],
        ];

        $this->content .= $referralOptionsForm->generateForm($fieldsForm);
    }

    /**
     * Init fields form
     * Used for henerating referral options
     */
    protected function initForm()
    {
        $this->fields_form = [
            'legend' => [
                'title' => $this->trans('Referral program settings', [], 'Modules.Gamifications.Admin'),
            ],
            'input' => [
                [
                    'name' => GamificationsConfig::REFERRAL_REWARD,
                    'label' => $this->trans('Referrer reward', [], 'Modules.Gamifications.Admin'),
                    'hint' => $this->trans('Reward that referrer will get', [], 'Modules.Gamifications.Admin'),
                    'type' => 'select',
                    'options' => [
                        'query' => $this->getRewardsSelect(),
                        'id' => 'id_gamifications_reward',
                        'name' => 'name',
                    ],
                ],
                [
                    'name' => GamificationsConfig::REFERRAL_REWARD_TIME,
                    'label' => $this->trans('Reward referrer when', [], 'Modules.Gamifications.Admin'),
                    'hint' => $this->trans(
                        'Time when referrer customer gets reward for inviting new customer',
                        [],
                        'Modules.Gamifications.Admin'
                    ),
                    'type' => 'radio',
                    'values' => [
                        [
                            'id' => GamificationsConfig::REFERRAL_REWARD_TIME.'_on_registration',
                            'value' => GamificationsActivity::REFERRAL_REWARD_ON_NEW_CUSTOMER_REGISTRATION,
                            'label' => $this->trans('new customer registers', [], 'Modules.Gamifications.Admin'),
                        ],
                        [
                            'id' => GamificationsConfig::REFERRAL_REWARD_TIME.'_on_order',
                            'value' => GamificationsActivity::REFERRAL_REWARD_ON_NEW_CUSTOMER_ORDER,
                            'label' => $this->trans('new customer places order', [], 'Modules.Gamifications.Admin'),
                        ],
                    ],
                ],
                [
                    'type' => 'swap',
                    'label' => $this->trans('New customer order state', [], 'Modules.Gamifications.Admin'),
                    'hint' => $this->trans(
                        'Reward referrer customer when new customer\'s order state is one of selected',
                        [],
                        'Modules.Gamifications.Admin'
                    ),
                    'name' => GamificationsConfig::REFERRAL_NEW_CUSTOMER_ORDER_STATES,
                    'multiple' => true,
                    'options' => [
                        'query' => OrderState::getOrderStates($this->context->language->id),
                        'id' => 'id_order_state',
                        'name' => 'name'
                    ],
                ],
                [
                    'name' => GamificationsConfig::REFERRAL_NEW_CUSTOMER_REWARD_ENABLED,
                    'label' => $this->trans('Reward new customer', [], 'Modules.Gamifications.Admin'),
                    'hint' =>
                        $this->trans('Enabled if you want to reward new customer', [], 'Modules.Gamifications.Admin'),
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
                ],
                [
                    'name' => GamificationsConfig::REFERRAL_NEW_CUSTOMER_REWARD,
                    'label' => $this->trans('New customer reward', [], 'Modules.Gamifications.Admin'),
                    'hint' => $this->trans('Reward that referrer will get', [], 'Modules.Gamifications.Admin'),
                    'type' => 'select',
                    'options' => [
                        'query' => $this->getRewardsSelect(),
                        'id' => 'id_gamifications_reward',
                        'name' => 'name',
                    ],
                ],
            ],
            'submit' => [
                'title' => $this->trans('Save', [], 'Modules.Gamifications.Admin'),
            ],
        ];
    }

    /**
     * Get reward for select input
     *
     * @return array
     */
    protected function getRewardsSelect()
    {
        $idLang = (int) $this->context->language->id;
        $idShop = (int) $this->context->shop->id;

        /** @var GamificationsRewardRepository $rewardRepository */
        $rewardRepository = $this->module->getEntityManager()->getRepository('GamificationsReward');
        $rewards = $rewardRepository->findAllNamesAndIds($idLang, $idShop);

        return $rewards;
    }
}
