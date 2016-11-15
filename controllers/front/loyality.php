<?php

/**
 * Class GamificationsLoyalityModuleFrontController
 */
class GamificationsLoyalityModuleFrontController extends GamificationsFrontController
{
    public $auth = true;

    /**
     * @var GamificationsPlayer
     */
    private $player;

    /**
     * @var array Statuses of each activity
     */
    private $activitiesStatus;

    /**
     * @var GamificationsPlayerRepository
     */
    private $playerRepository;

    /**
     * Create or get player object
     */
    public function init()
    {
        parent::init();

        /** @var GamificationsPlayerRepository $playerRepository */
        $playerRepository = $this->module->getEntityManager()->getRepository('GamificationsPlayer');
        $idPlayer = $playerRepository->findIdByCustomerId($this->context->customer->id, $this->context->shop->id);

        $player = new GamificationsPlayer((int) $idPlayer, null, $this->context->shop->id);

        if (null === $idPlayer && !Validate::isLoadedObject($player)) {
            $player->id_customer = (int) $this->context->customer->id;
            $player->total_points = 0;
            $player->spent_points = 0;
            $player->username = $this->context->customer->firstname;

            if (!$player->save()) {
                $this->errors[] = $this->trans('Unexpected error occured', [], 'Modules.Gamifications.Shop');
                $this->redirectWithNotifications($this->context->link->getPageLink('my-account'));
            }
        }

        $this->player = $player;
        $this->playerRepository = $playerRepository;

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

        $this->initDailyRewards();

        $this->context->smarty->assign([
            'player' => $this->player,
            'activities_status' => $this->activitiesStatus,
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
    protected function initDailyRewards()
    {
        if (!$this->activitiesStatus[GamificationsConfig::DAILY_REWARDS_STATUS]) {
            return;
        }

        $canPlayDailyReward = $this->canCustomerPlayDailyReward();

        $this->context->smarty->assign([
            'can_play_daily_reward' => $canPlayDailyReward,
        ]);
    }

    /**
     * Process everything thats related to daily rewards
     */
    protected function postProcessDailyRewards()
    {

    }

    /**
     * Check if customer can play daily reward
     *
     * @return bool
     */
    private function canCustomerPlayDailyReward()
    {
        $mostRecentDailyRewardActivity = $this->playerRepository->findMostRecentActivity(
            $this->player->id,
            GamificationsActivity::TYPE_DAILY_REWARD,
            $this->context->shop->id
        );

        $canPlayDailyReward = false;
        if (null !== $mostRecentDailyRewardActivity) {
            $now = new DateTime();
            $lastPlayed = new DateTime($mostRecentDailyRewardActivity['date_add']);

            if (1 <= $now->diff($lastPlayed)->d) {
                $canPlayDailyReward = true;
            }
        }

        return $canPlayDailyReward;
    }
}
