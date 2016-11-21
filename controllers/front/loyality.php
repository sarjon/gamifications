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
        $canPlayDailyReward = $this->isDailyRewardAvailable($nextDailyRewardAvailabeAt);

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

        if (!$this->isDailyRewardAvailable()) {
            $this->warning[] = $this->trans('Wooops, Daily Reward is not available at the moment.');
            return;
        }

        $customerGroupsIds = $this->context->customer->getGroups();

        /** @var GamificationsDailyRewardRepository $dailyRewardsRepository */
        $dailyRewardsRepository = $this->module->getEntityManager()->getRepository('GamificationsDailyReward');
        $availableDailyRewards = $dailyRewardsRepository
            ->findAllByCustomerGroups($customerGroupsIds, $this->context->shop->id);

        if (empty($availableDailyRewards)) {
            $this->warning[] = $this->trans(
                'No Daily Rewards available at the moment, please check back soon!',
                [],
                'Modules.Gamifications.Shop'
            );
            return;
        }

        $dailyRewardsWithBoost = [];
        foreach ($availableDailyRewards as $dailyReward) {
            $boost = (int) $dailyReward['boost'];
            $idDailyReward = (int) $dailyReward['id_gamifications_daily_reward'];

            $dailyRewardBoost = array_fill(0, $boost, $idDailyReward);
            $dailyRewardsWithBoost = array_merge($dailyRewardsWithBoost, $dailyRewardBoost);
        }

        shuffle($dailyRewardsWithBoost);

        $idDailyReward = (int) GamificationsArrayHelper::getRandomValue($dailyRewardsWithBoost);

        $dailyReward = new GamificationsDailyReward($idDailyReward, null, $this->context->shop->id);
        $dailyReward->times_won = (int) $dailyReward->times_won + 1;
        $dailyReward->save(false, true, false);

        $reward = new GamificationsReward(
            $dailyReward->id_reward,
            null,
            $this->context->shop->id
        );

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

    /**
     * Check if Daily Reward is available for customer
     *
     * @param null|DateTime $nextDailyRewardAvailabe
     *
     * @return bool
     */
    private function isDailyRewardAvailable(&$nextDailyRewardAvailabe = null)
    {
        $nextDailyRewardAvailabe = null;

        $mostRecentDailyRewardActivity = $this->gamificationCustomerRepository->findMostRecentActivity(
            $this->context->customer->id,
            GamificationsActivity::TYPE_DAILY_REWARD,
            $this->context->shop->id
        );

        $isDailyRewardAvailable = false;
        if (is_array($mostRecentDailyRewardActivity)) {

            $now = new DateTime();
            $lastPlayed = new DateTime($mostRecentDailyRewardActivity['date_add']);

            if (1 <= $now->diff($lastPlayed)->d) {
                $isDailyRewardAvailable = true;
            }

            $nextDailyRewardAvailabe = $lastPlayed->modify('+1 day');
        } elseif (null === $mostRecentDailyRewardActivity) {
            $isDailyRewardAvailable = true;
        }

        return $isDailyRewardAvailable;
    }
}
