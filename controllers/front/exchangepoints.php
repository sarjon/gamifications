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

/**
 * Class GamificationsPointsExchangeModuleFrontController
 */
class GamificationsExchangePointsModuleFrontController extends GamificationsFrontController
{
    const FILENAME = 'exchangepoints';

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

        $imageType = ImageType::getFormattedName('small');

        foreach ($pointExchangeRewards as &$pointExchangeReward) {
            if (GamificationsReward::REWARD_TYPE_GIFT != $pointExchangeReward['reward_type']) {
                continue;
            }

            $reward = new GamificationsReward((int) $pointExchangeReward['id_gamifications_reward']);
            $product = new Product($reward->id_product, false, $this->context->language->id);

            $coverImage = Image::getCover($product->id);
            $idProductAndIdImage = sprintf('%s-%s', $product->id, (int) $coverImage['id_image']);

            if (!Validate::isLoadedObject($product)) {
                continue;
            }

            $pointExchangeReward['image_link'] =
                $this->context->link->getImageLink($product->link_rewrite, $idProductAndIdImage, $imageType);
        }

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
            $this->errors[] = '#veryToken #muchSecurity #suchHacker :)';
            return;
        }

        $idPointsExchangeReward = (int) Tools::getValue('id_point_exchange_reward');
        /** @var GamificationsPointExchangeRepository $pointsExchangeRewardRepository */
        $pointsExchangeRewardRepository = $this->getEntityManager()->getRepository('GamificationsPointExchange');
        $pointsExchangeReward = $pointsExchangeRewardRepository->findOne($idPointsExchangeReward);

        if (!$pointsExchangeReward instanceof GamificationsPointExchange) {
            $this->errors[] = $this->l('Unexpected error occured', self::FILENAME);
            return;
        }

        if (!$this->gamificationCustomer->checkExchangePoints($pointsExchangeReward)) {
            $missingPoints = (int) $pointsExchangeReward->points - (int) $this->gamificationCustomer->total_points;
            $this->warning[] = sprintf(
                $this->l('You still need % more points to get selected reward', self::FILENAME),
                $missingPoints
            );
            return;
        }

        $idReward = (int) $pointsExchangeReward->id_reward;
        $reward = new GamificationsReward($idReward, null, $this->context->shop->id);

        if (!Validate::isLoadedObject($reward)) {
            $this->errors[] = $this->l('Reward was not found', self::FILENAME);
            return;
        }

        $this->gamificationCustomer->removePoints($pointsExchangeReward->points);
        $this->gamificationCustomer->addSpentPoints($pointsExchangeReward->points);
        $this->gamificationCustomer->save();

        $rewardHandler = new GamificationsRewardHandler();
        $result = $rewardHandler
            ->handleCustomerReward($reward, $this->gamificationCustomer, GamificationsActivity::TYPE_POINT_EXCHANGE);

        if (!$result['success']) {
            $this->errors[] =
                $this->l('Unexpected error occured, please contact us if it keeps happening', self::FILENAME);
            return;
        }

        $this->success[] = sprintf(
            $this->l('You have successfully exchanged %s points into %s!', self::FILENAME),
            (int) $pointsExchangeReward->points,
            $reward->name[$this->context->language->id]
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

        $breadcrumb['links'][] = [
            'title' => $this->l('Exchange points', self::FILENAME),
            'url' => $this->context->link->getModuleLink(
                $this->module->name,
                Gamifications::FRONT_EXCHANGE_POINTS_CONTROLLER
            ),
        ];

        return $breadcrumb;
    }
}
