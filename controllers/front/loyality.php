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
     * @var GamificationsPlayerManager
     */
    private $playerManager;

    /**
     * Create or get player object
     */
    public function init()
    {
        parent::init();

        $this->playerManager = new GamificationsPlayerManager($this->module);
        if (!$this->playerManager->loadPlayerObject()) {
            $this->errors[] = $this->trans('Unexpected error occured', [], 'Modules.Gamifications.Shop');
            $this->redirectWithNotifications($this->context->link->getPageLink('my-account'));
        }

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
            'player' => $this->playerManager->getPlayer(),
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
    protected function initDailyRewardsContent()
    {
        if (!$this->activitiesStatus[GamificationsConfig::DAILY_REWARDS_STATUS]) {
            return;
        }

        $nextDailyRewardAvailabeAt = null;
        $canPlayDailyReward = $this->playerManager->isDailyRewardAvailable($nextDailyRewardAvailabeAt);

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
}
