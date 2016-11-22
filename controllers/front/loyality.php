<?php

/**
 * Class GamificationsLoyalityModuleFrontController
 */
class GamificationsLoyalityModuleFrontController extends GamificationsFrontController
{
    public $auth = true;

    /**
     * Perform all processing here
     */
    public function postProcess()
    {
        $this->postProcessDailyRewards();
    }

    /**
     * Init content
     */
    public function initContent()
    {
        parent::initContent();

        $this->initDailyRewardsContent();

        $this->context->smarty->assign([
            'gamifications_customer' => $this->gamificationCustomer,
            'is_daily_rewards_enabled' => (bool) Configuration::get(GamificationsConfig::DAILY_REWARDS_STATUS),
        ]);

        $this->setTemplate('module:gamifications/views/templates/front/loyality.tpl');
    }

    /**
     * Generate breadcrumb
     *
     * @return array
     */
    public function getBreadcrumbLinks()
    {
        $breadcrumb = parent::getBreadcrumbLinks();

        $breadcrumb['links'][] = $this->addMyAccountToBreadcrumb();

        $frontOfficeTitle = Configuration::get(GamificationsConfig::FRONT_OFFICE_TITLE, $this->context->language->id);

        $breadcrumb['links'][] = [
            'title' => $frontOfficeTitle,
            'url' => $this->context->link->getModuleLink($this->module->name, Gamifications::FRONT_LOYALITY_CONTROLLER),
        ];

        return $breadcrumb;
    }

    /**
     * Initialize daily rewards
     */
    protected function initDailyRewardsContent()
    {
        $isDailyRewardsEnabled = (bool) Configuration::get(GamificationsConfig::DAILY_REWARDS_STATUS);
        if (!$isDailyRewardsEnabled) {
            return;
        }

        $nextDailyRewardAvailabeAt = null;
        $dailyRewardActivity = new GamificationsDailyRewardActivity($this->context, $this->module->getEntityManager());
        $canPlayDailyReward = $dailyRewardActivity->isDailyRewardAvailable($nextDailyRewardAvailabeAt);

        if (!$nextDailyRewardAvailabeAt instanceof DateTime) {
            $nextDailyRewardAvailabeAt = new DateTime();
        }

        $this->context->smarty->assign([
            'can_play_daily_reward' => (bool) $canPlayDailyReward,
            'next_daily_reward_availabe_at' => $nextDailyRewardAvailabeAt->format('Y-m-d H:i'),
        ]);
    }

    /**
     * Process everything thats related to daily rewards
     */
    protected function postProcessDailyRewards()
    {
        $isDailyRewardsEnabled = (bool) Configuration::get(GamificationsConfig::DAILY_REWARDS_STATUS);
        if (!Tools::isSubmit('get_daily_reward') || !$isDailyRewardsEnabled) {
            return;
        }

        $dailyRewardActivity = new GamificationsDailyRewardActivity($this->context, $this->module->getEntityManager());

        if (!$dailyRewardActivity->isDailyRewardAvailable()) {
            $this->warning[] = $this->trans('Wooops, Daily Reward is not available at the moment.');
            return;
        }

        $reward = $dailyRewardActivity->getDailyReward();

        if (null === $reward) {
            $this->warning[] = $this->trans(
                'No Daily Rewards available at the moment, please check back soon!',
                [],
                'Modules.Gamifications.Shop'
            );
            return;
        }

        $rewardHandler = new GamificationsRewardHandler($this->context);
        $results = $rewardHandler
            ->handleCustomerReward($reward, $this->gamificationCustomer, GamificationsActivity::TYPE_DAILY_REWARD);

        if (!$results['success']) {
            $this->errors[] =
                $this->trans('Unexpected error occured, you should report it', [], 'Modules.Gamifications.Shop');
            return;
        }

        $this->success[] = $this->trans($results['message'], [], 'Modules.Gamifications.Shop');
    }
}
