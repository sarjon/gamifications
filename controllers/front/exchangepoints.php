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

        $this->initRewardsContent();

        $this->context->smarty->assign([
            'controller' => 'exchangepoints',
        ]);

        $this->setTemplate(sprintf('module:%s/views/templates/front/exchangepoints.tpl', $this->module->name));
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
     * Initialize rewards content
     */
    protected function initRewardsContent()
    {
        /** @var GamificationsPointExchangeRepository $pointExcahngeRepository */
        $pointExcahngeRepository = $this->module->getEntityManager()->getRepository('GamificationsPointExchange');

        $idShop = (int) $this->context->shop->id;
        $idLang = (int) $this->context->language->id;
        $idGroups = $this->context->customer->getGroups();

        $pointExchangeRewards = $pointExcahngeRepository->findAllPointExchangeRewards($idGroups, $idShop, $idLang);

        $this->context->smarty->assign([
            'point_exchange_rewards' => $pointExchangeRewards,
            'gamifications_customer' => (array) $this->gamificationCustomer,
        ]);
    }
}
