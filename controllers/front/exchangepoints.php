<?php

/**
 * Class GamificationsPointsExchangeModuleFrontController
 */
class GamificationsExchangePointsModuleFrontController extends GamificationsFrontController
{
    public $auth = true;

    /**
     * Customize content
     */
    public function initContent()
    {
        parent::initContent();

        /** @var GamificationsPointExchangeRepository $pointExcahngeRepository */
        $pointExcahngeRepository = $this->module->getEntityManager()->getRepository('GamificationsPointExchange');

        $idShop = (int) $this->context->shop->id;
        $idLang = (int) $this->context->language->id;
        $idGroups = $this->context->customer->getGroups();

        $pointExchangeRewards = $pointExcahngeRepository->findAllPointExchangeRewards($idGroups, $idShop, $idLang);

        $this->context->smarty->assign([
            'point_exchange_rewards' => $pointExchangeRewards,
            'gamifications_customer' => (array) $this->gamificationCustomer,
            'csrf_token' => Tools::getToken(),
            'controller' => 'exchangepoints',
        ]);

        $this->setTemplate(sprintf('module:%s/views/templates/front/exchangepoints.tpl', $this->module->name));
    }

    /**
     * Process points exchange
     */
    public function postProcess()
    {
        if (!Tools::isSubmit('exchange_points')) {
            return;
        }

        if (Tools::getValue('csrf_token') !== Tools::getToken()) {
            $this->errors[] = $this->trans('#veryToken #muchSecurity #suchHacker :)', [], 'Modules.Gamifications.Shop');
            return;
        }

        $idPointsExchangeReward = (int) Tools::getValue('id_point_exchange_reward');
        /** @var GamificationsPointExchangeRepository $pointsExchangeRewardRepository */
        $pointsExchangeRewardRepository = $this->getEntityManager()->getRepository('GamificationsPointExchange');
        $pointsExchangeReward = $pointsExchangeRewardRepository->findOne($idPointsExchangeReward);

        if (!$pointsExchangeReward instanceof GamificationsPointExchange) {
            $this->errors[] = $this->trans('Unexpected error occured', [], 'Modules.Gamifications.Shop');
            return;
        }

        if ($pointsExchangeReward->points > $this->gamificationCustomer->total_points) {
            $missingPoints = (int) $pointsExchangeReward->points - (int) $this->gamificationCustomer->total_points;
            $this->warning[] = $this->trans(
                'You still need %points% more points to get selected reward',
                ['%points%' => $missingPoints],
                'Modules.Gamifications.Shop'
            );
            return;
        }

        $idReward = (int) $pointsExchangeReward->id_reward;
        $reward = new GamificationsReward($idReward, null, $this->context->shop->id);

        if (!Validate::isLoadedObject($reward)) {
            $this->errors[] = $this->trans('Reward was not found', [], 'Modules.Gamifications.Shop');
            return;
        }

        $this->gamificationCustomer->total_points -= (int) $pointsExchangeReward->points;
        $this->gamificationCustomer->spent_points += (int) $pointsExchangeReward->points;
        $this->gamificationCustomer->save();

        $rewardHandler = new GamificationsRewardHandler($this->context);
        $result = $rewardHandler
            ->handleCustomerReward($reward, $this->gamificationCustomer, GamificationsActivity::TYPE_POINT_EXCHANGE);

        if (!$result['success']) {
            $this->errors[] = $this->trans(
                'Unexpected error occured, please contact us if it keeps happening',
                [],
                'Modules.Gamifications.Shop'
            );
            return;
        }

        $this->success[] = $this->trans(
            'You have successfully exchanged %points% into %reward_title%!',
            [
                '%points%' => $pointsExchangeReward->points,
                '%reward_title%' => $reward->name[$this->context->language->id],
            ],
            'Module.Gamifications.Shop'
        );
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
}
