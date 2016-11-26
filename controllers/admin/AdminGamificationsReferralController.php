<?php

/**
 * Class AdminGamificationsReferralController
 */
class AdminGamificationsReferralController extends GamificationsAdminController
{
    /**
     * Initialize options
     */
    protected function initOptions()
    {
        $this->fields_options = [
            'general' => [
                'title' => $this->trans('Referral program settings', [], 'Modules.Gamifications.Admin'),
                'fields' => [
                    GamificationsConfig::REFERRAL_REWARD_TIME => [
                        'title' => $this->trans('Referrer reward time', [], 'Modules.Gamifications.Admin'),
                        'hint' =>
                            $this->trans('Time when referrer gets reward', [], 'Modules.Gamifications.Admin'),
                        'cast' => 'intval',
                        'type' => 'radio',
                        'validation' => 'isUnsignedInt',
                        'choices' => [
                            GamificationsActivity::REFERRAL_REWARD_ON_NEW_CUSTOMER_REGISTRATION =>
                                $this->trans('On new customer registration', [], 'Modules.Gamifications.Admin'),
                            GamificationsActivity::REFERRAL_REWARD_ON_NEW_CUSTOMER_ORDER =>
                                $this->trans('On new customer order', [], 'Modules.Gamifications.Admin'),
                        ],
                    ],
                    GamificationsConfig::REFERRAL_ORDER_STATE => [
                        'title' => $this->trans('New customer order states', [], 'Modules.Gamifications.Admin'),
                        'type' => 'free',
                        'hint' => $this->trans(
                            'Order states after which referrer will get reward',
                            [],
                            'Modules.Gamifications.Admin'
                        ),
                    ],
                    GamificationsConfig::REFERRAL_REWARD => [
                        'title' => $this->trans('Referrer customer reward', [], 'Modules.Gamifications.Admin'),
                        'validation' => 'isUnsignedInt',
                        'cast' => 'intval',
                        'type' => 'select',
                        'list' => $this->getRewardsSelect(),
                        'identifier' => 'id_gamifications_reward',
                    ],
                ],
                'submit' => [
                    'title' => $this->trans('Save'),
                ],
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
