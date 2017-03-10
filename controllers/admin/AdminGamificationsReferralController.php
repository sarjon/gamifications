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
 * Class AdminGamificationsReferralController
 */
class AdminGamificationsReferralController extends GamificationsAdminController
{
    protected $displayHelpInForm = true;

    /**
     * Init custom content
     */
    public function initContent()
    {
        parent::initContent();

        $this->content .= $this->renderReferralOptions();

        $this->context->smarty->assign('content', $this->content);
    }

    public function setMedia($newTheme = false)
    {
        parent::setMedia($newTheme);

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

        $success = true;
        foreach ($configurations as $name => $value) {
            $success &= Configuration::updateValue($name, $value);
        }

        if (!$success) {
            $this->errors[] = $this->l('Failed update');
        }

        $this->confirmations[] = $this->l('Successful update');
    }

    /**
     * Init free input type values
     */
    protected function renderReferralOptions()
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
                'title' => $this->l('Referral program settings'),
            ],
            'input' => [
                [
                    'name' => GamificationsConfig::REFERRAL_REWARD,
                    'label' => $this->l('Referrer reward'),
                    'hint' => $this->l('Reward that referrer will get'),
                    'type' => 'select',
                    'options' => [
                        'query' => $this->getRewardsSelect(),
                        'id' => 'id_gamifications_reward',
                        'name' => 'name',
                    ],
                ],
                [
                    'name' => GamificationsConfig::REFERRAL_REWARD_TIME,
                    'label' => $this->l('Reward referrer when'),
                    'hint' => $this->l('Time when referrer customer gets reward for inviting new customer'),
                    'type' => 'radio',
                    'values' => [
                        [
                            'id' => GamificationsConfig::REFERRAL_REWARD_TIME.'_on_registration',
                            'value' => GamificationsActivity::REFERRAL_REWARD_ON_NEW_CUSTOMER_REGISTRATION,
                            'label' => $this->l('new customer registers'),
                        ],
                        [
                            'id' => GamificationsConfig::REFERRAL_REWARD_TIME.'_on_order',
                            'value' => GamificationsActivity::REFERRAL_REWARD_ON_NEW_CUSTOMER_ORDER,
                            'label' => $this->l('new customer places order'),
                        ],
                    ],
                ],
                [
                    'type' => 'swap',
                    'label' => $this->l('New customer order state'),
                    'hint' => $this->l('Reward referrer customer when new customer\'s order state is one of selected'),
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
                    'label' => $this->l('Reward new customer'),
                    'hint' => $this->l('Enabled if you want to reward new customer'),
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
                ],
                [
                    'name' => GamificationsConfig::REFERRAL_NEW_CUSTOMER_REWARD,
                    'label' => $this->l('New customer reward'),
                    'hint' => $this->l('Reward that referrer will get'),
                    'type' => 'select',
                    'options' => [
                        'query' => $this->getRewardsSelect(),
                        'id' => 'id_gamifications_reward',
                        'name' => 'name',
                    ],
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
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

        if (empty($rewards)) {
            $rewards[] = [
                'id_gamifications_reward' => 0,
                'name' => $this->l('No available rewards'),
            ];
        } else {
            $noReward = [
                'id_gamifications_reward' => 0,
                'name' => $this->l('No reward'),
            ];

            array_unshift($rewards, $noReward);
        }

        return $rewards;
    }

    /**
     * Display help panel
     */
    protected function displayHelp()
    {
        return $this->context->smarty->fetch(
            $this->module->getLocalPath().'views/templates/admin/referral_program_info.tpl'
        );
    }
}
