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
}
