<?php

/**
 * Class GamificationsLoyalityModuleFrontController
 */
class GamificationsLoyalityModuleFrontController extends GamificationsFrontController
{
    public $auth = true;

    /**
     * @var array Statuses of each activity
     */
    private $activitiesStatus;

    /**
     * Create or get player object
     */
    public function init()
    {
        parent::init();

        $activities = [
            GamificationsConfig::DAILY_REWARDS_STATUS,
        ];
        $this->activitiesStatus = Configuration::getMultiple($activities, null, null, $this->context->shop->id);
    }

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
            'is_daily_rewards_enabled' => (bool) $this->activitiesStatus[GamificationsConfig::DAILY_REWARDS_STATUS],
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
        if (!$this->activitiesStatus[GamificationsConfig::DAILY_REWARDS_STATUS]) {
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
        if (!Tools::isSubmit('get_daily_reward')) {
            return;
        }

        if (!$this->isDailyRewardAvailable()) {
            $this->errors[] = $this->trans('Wooops, Daily Reward is not available at the moment.');
            return;
        }

        $customerGroupsIds = $this->context->customer->getGroups();

        /** @var GamificationsDailyRewardRepository $dailyRewardsRepository */
        $dailyRewardsRepository = $this->module->getEntityManager()->getRepository('GamificationsDailyReward');
        $availableDailyRewards = $dailyRewardsRepository
            ->findAllByCustomerGroups($customerGroupsIds, $this->context->shop->id);

        $dailyRewardsWIthBoost = [];
        foreach ($availableDailyRewards as $dailyReward) {
            $boost = (int) $dailyReward['boost'];
            $idDailyReward = (int) $dailyReward['id_gamifications_daily_reward'];

            $dailyRewardBoost = array_fill(0, $boost, $idDailyReward);
            $dailyRewardsWIthBoost = array_merge($dailyRewardsWIthBoost, $dailyRewardBoost);
        }

        shuffle($dailyRewardsWIthBoost);

        $idDailyReward = (int) GamificationsArrayHelper::getRandomValue($dailyRewardsWIthBoost);

        $dailyReward = new GamificationsDailyReward($idDailyReward, null, $this->context->shop->id);
        $dailyReward->times_won = (int) $dailyReward->times_won + 1;
        $dailyReward->save(false, true, false);

        $this->handleDailyReward($dailyReward);
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

    /**
     * Add reward to customer
     *
     * @param GamificationsDailyReward $dailyReward
     */
    private function handleDailyReward(GamificationsDailyReward $dailyReward)
    {
        $activityHistory = new GamificationsActivityHistory();
        $activityHistory->id_customer = (int) $this->context->customer->id;
        $activityHistory->id_reward = (int) $dailyReward->id_reward;
        $activityHistory->id_shop = (int) $this->context->shop->id;
        $activityHistory->activity_type = GamificationsActivity::TYPE_DAILY_REWARD;
        $activityHistory->save();

        $reward = new GamificationsReward($dailyReward->id_reward, $this->context->language->id, $this->context->shop->id);

        switch ((int) $reward->reward_type) {
            case GamificationsReward::REWARD_TYPE_POINTS:
                $this->gamificationCustomer->addPoints($reward->points);
                $this->success[] = $this
                    ->trans('You got %points% points!', ['%points%' => $reward->points], 'Modules.Gamifications.Shop');
                break;
            case GamificationsReward::REWARD_TYPE_RANDOM_AMOUNT_OF_POINTS:
                $min = $reward->points - $reward->radius;
                $max = $reward->points + $reward->radius;
                $randomPoints = rand($min, $max);
                $this->gamificationCustomer->addPoints($randomPoints);
                $this->success[] = $this
                    ->trans('You got %points% points!', ['%points%' => $randomPoints], 'Modules.Gamifications.Shop');
                break;
        }
    }
}
