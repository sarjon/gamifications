<?php
/**
 * This file is part of the Gamifications module.
 *
 * @author    Sarunas Jonusas, <jonusas.sarunas@gmail.com>
 * @copyright Copyright (c) permanent, Sarunas Jonusas
 * @license   Addons PrestaShop license limitation
 */

use PrestaShop\PrestaShop\Core\Foundation\Database\EntityManager;

/**
 * Class GamificationsRankActivity
 */
class GamificationsRankActivity
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * GamificationsRankActivity constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Process order
     *
     * @param Order $order
     * @param bool $create
     */
    public function processOrder(Order $order, $create = false)
    {
        if ($create) {
            GamificationsRank::createRankOrder($order);
        }

        /** @var GamificationsRankRepository $rankRepository */
        $rankRepository = $this->em->getRepository('GamificationsRank');
        $isActiveOrder = $rankRepository->isActiveOrder($order);
        if (!$isActiveOrder) {
            return;
        }

        $orderState = $order->getCurrentOrderState();
        if (!$orderState->paid) {
            return;
        }

        $spentMoney = $order->getTotalPaid(Currency::getDefaultCurrency());

        $gamificationsCustomer = $this->getGamificationsCustomer($order);
        $gamificationsCustomer->addSpentMoney($spentMoney);

        $this->handleRank($gamificationsCustomer);

        GamificationsRank::completeRankOrder($order);
    }

    /**
     * Handle customer ranks
     *
     * @param $gamificationsCustomer
     */
    protected function handleRank(GamificationsCustomer $gamificationsCustomer)
    {

    }

    /**
     * Get gamifications customer from order info
     *
     * @param Order $order
     *
     * @return GamificationsCustomer
     */
    protected function getGamificationsCustomer(Order $order)
    {
        /** @var GamificationsCustomerRepository $customerRepository */
        $customerRepository = $this->em->getRepository('GamificationsCustomer');
        $id = $customerRepository->findIdByCustomerId($order->id_customer, $order->id_shop);

        $gamificationsCustomer = new GamificationsCustomer($id, null, $order->id_shop);
        if (null === $id || !Validate::isLoadedObject($gamificationsCustomer)) {
            $gamificationsCustomer = GamificationsCustomer::create($order->getCustomer(), true);
        }

        return $gamificationsCustomer;
    }
}
