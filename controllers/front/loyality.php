<?php
/**
 * This file is part of the Gamifications module.
 *
 * @author    Sarunas Jonusas <jonusas.sarunas@gmail.com>
 * @copyright Copyright (c) permanent, Sarunas Jonusas
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Class GamificationsLoyalityModuleFrontController
 */
class GamificationsLoyalityModuleFrontController extends GamificationsFrontController
{
    const FILENAME = 'loyality';

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
        $this->initReferralContent();
        $this->initNextAvailableReward();

        $this->context->smarty->assign([
            'controller' => 'loyality',
            'gamifications_customer' => (array) $this->gamificationCustomer,
            'is_daily_rewards_enabled' => (bool) Configuration::get(GamificationsConfig::DAILY_REWARDS_STATUS),
            'is_referral_program_enabled' => (bool) Configuration::get(GamificationsConfig::REFERRAL_PROGRAM_STATUS),
            'front_office_title' =>
                Configuration::get(GamificationsConfig::FRONT_OFFICE_TITLE, $this->context->language->id),
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
        $dailyRewardActivity = new GamificationsDailyRewardActivity($this->module->getEntityManager());
        $canPlayDailyReward = $dailyRewardActivity->isDailyRewardAvailable($nextDailyRewardAvailabeAt);

        if (!$nextDailyRewardAvailabeAt instanceof DateTime) {
            $nextDailyRewardAvailabeAt = new DateTime();
        }

        $now = new DateTime();

        if ($now->format('Y-m-d') === $nextDailyRewardAvailabeAt->format('Y-m-d')) {
            $nextDailyRewardText =
                sprintf($this->l('today at %s', self::FILENAME), $nextDailyRewardAvailabeAt->format('H:i'));
        } elseif ($now->modify('+1 day')->format('Y-m-d') === $nextDailyRewardAvailabeAt->format('Y-m-d')) {
            $nextDailyRewardText =
                sprintf($this->l('tomorrow at %s', self::FILENAME), $nextDailyRewardAvailabeAt->format('H:i'));
        } else {
            $nextDailyRewardText = $nextDailyRewardAvailabeAt->format('Y-m-d H:i');
        }

        $this->context->smarty->assign([
            'can_play_daily_reward' => (bool) $canPlayDailyReward,
            'next_daily_reward_availabe_at' => $nextDailyRewardText,
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

        $dailyRewardActivity = new GamificationsDailyRewardActivity($this->module->getEntityManager());

        if (!$dailyRewardActivity->isDailyRewardAvailable()) {
            $this->warning[] = $this->l('Wooops, Daily Reward is not available at the moment.', self::FILENAME);
            return;
        }

        $reward = $dailyRewardActivity->getDailyReward();

        if (null === $reward) {
            $this->warning[] =
                $this->l('No Daily Rewards available at the moment, please check back soon!', self::FILENAME);
            return;
        }

        $rewardHandler = new GamificationsRewardHandler($this->context);
        $results = $rewardHandler
            ->handleCustomerReward($reward, $this->gamificationCustomer, GamificationsActivity::TYPE_DAILY_REWARD);

        if (!$results['success']) {
            $this->errors[] = $this->l('Unexpected error occured, you should report it', self::FILENAME);
            return;
        }

        $this->success[] = $results['message'];
    }

    /**
     * Initialize referral program content
     */
    protected function initReferralContent()
    {
        $isReferralProgramEnabled = (bool) Configuration::get(GamificationsConfig::REFERRAL_PROGRAM_STATUS);

        if (!$isReferralProgramEnabled) {
            return;
        }

        $path = sprintf('modules/%s/views/js/front/referral.js', $this->module->name);
        $this->registerJavascript('modules-gamifications-referraljs', $path, ['media' => 'all', 'priority' => 150]);

        $idLang = (int) $this->context->language->id;
        $idShop = (int) $this->context->shop->id;

        $referralRewardName = null;
        $newCustomerRewardName = null;

        $idReferralReward   = (int) Configuration::get(GamificationsConfig::REFERRAL_REWARD);
        $rewardNewCustomer  = (bool) Configuration::get(GamificationsConfig::REFERRAL_NEW_CUSTOMER_REWARD_ENABLED);

        if ($idReferralReward) {
            $referralReward = new GamificationsReward($idReferralReward, $idLang, $idShop);
            $referralRewardName = $referralReward->name;
        }

        if ($rewardNewCustomer) {
            $idNewCusomerReward = (int) Configuration::get(GamificationsConfig::REFERRAL_NEW_CUSTOMER_REWARD);
            if ($idNewCusomerReward) {
                $referralReward = new GamificationsReward($idNewCusomerReward, $idLang, $idShop);
                $newCustomerRewardName = $referralReward->name;
            }
        }

        $referralUrl = sprintf('%s?', $this->context->link->getPageLink('authentication'));
        $referralUrl .= http_build_query([
            'create_account' => 1,
            'referral_code' => $this->gamificationCustomer->referral_code,
        ]);

        /** @var GamificationsCustomerRepository $customerRepository */
        $customerRepository    = $this->getEntityManager()->getRepository('GamificationsCustomer');
        $invitedCustomersCount = $customerRepository->findInvitedCustomersCount($this->context->customer->id, $idShop);

        $this->context->smarty->assign([
            'referral_url'             => $referralUrl,
            'referral_reward_name'     => $referralRewardName,
            'new_customer_reward_name' => $newCustomerRewardName,
            'invited_customers_count'  => $invitedCustomersCount,
        ]);
    }

    /**
     * Initialize next available reward
     */
    private function initNextAvailableReward()
    {
        $customerPoints = $this->gamificationCustomer->total_points;
        $customerGroups = $this->context->customer->getGroups();
        $idShop = $this->context->shop->id;

        /** @var GamificationsPointExchangeRepository $pointExchangeRepo */
        $pointExchangeRepo = $this->getEntityManager()->getRepository('GamificationsPointExchange');
        $idPointExchange =
            $pointExchangeRepo->findClosestPointExchangeRewardByPoints($customerPoints, $customerGroups, $idShop);

        $reward = null;
        $pointExchange = null;
        if (null !== $idPointExchange) {
            $pointExchange = new GamificationsPointExchange($idPointExchange);

            $reward = new GamificationsReward($pointExchange->id_reward, $this->context->language->id);

            if (GamificationsReward::REWARD_TYPE_GIFT == $reward->reward_type) {
                $product = new Product($reward->id_product, false, $this->context->language->id);

                $coverImage = Image::getCover($product->id);
                $idProductAndIdImage = sprintf('%s-%s', $product->id, (int) $coverImage['id_image']);

                if (Validate::isLoadedObject($product)) {
                    $imageType = ImageType::getFormattedName('small');

                    $reward->image_link =
                        $this->context->link->getImageLink($product->link_rewrite, $idProductAndIdImage, $imageType);
                }
            }
        }

        $this->context->smarty->assign([
            'next_reward' => (array) $reward,
            'point_exchange' => (array) $pointExchange,
        ]);
    }
}
