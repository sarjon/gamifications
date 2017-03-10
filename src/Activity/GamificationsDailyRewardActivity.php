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

use PrestaShop\PrestaShop\Core\Foundation\Database\EntityManager;

/**
 * Class GamificationsDailyRewardActivity
 */
class GamificationsDailyRewardActivity
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * GamificationsDailyRewardActivity constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {

        $this->context = Context::getContext();
        $this->em = $em;
    }

    /**
     * Get Daily Reward
     *
     * @return GamificationsReward|null
     */
    public function getDailyReward()
    {
        $customerGroupsIds = $this->context->customer->getGroups();

        /** @var GamificationsDailyRewardRepository $dailyRewardsRepository */
        $dailyRewardsRepository = $this->em->getRepository('GamificationsDailyReward');
        $availableDailyRewards = $dailyRewardsRepository
            ->findAllByCustomerGroups($this->context->shop->id, $customerGroupsIds);

        if (empty($availableDailyRewards)) {
            return null;
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

        $reward = new GamificationsReward($dailyReward->id_reward, null, $this->context->shop->id);

        return $reward;
    }

    /**
     * Check if Daily Reward is available
     *
     * @param null $nextDailyRewardAvailabe
     *
     * @return bool
     */
    public function isDailyRewardAvailable(&$nextDailyRewardAvailabe = null)
    {
        $nextDailyRewardAvailabe = null;

        /** @var GamificationsCustomerRepository $gamificationsCustomerRepository */
        $gamificationsCustomerRepository = $this->em->getRepository('GamificationsCustomer');

        $mostRecentDailyRewardActivity = $gamificationsCustomerRepository->findMostRecentActivity(
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
