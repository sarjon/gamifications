<?php

/**
 * Class AdminGamificationsDailyRewardsController
 */
class AdminGamificationsDailyRewardsController extends GamificationsAdminController
{
    public function initContent()
    {
        $isDisplayExpalanationsOn = (bool) Configuration::get(GamificationsConfig::DISPLAY_EXPLANATIONS);

        if ($isDisplayExpalanationsOn) {
            $this->content .= $this->context->smarty->fetch(
                $this->module->getLocalPath().'views/templates/admin/DailyRewards/info.tpl'
            );
        }

        parent::initContent();
    }
}
