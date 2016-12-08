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
 * Class GamificationsShoppingPointActivity
 */
class GamificationsShoppingPointActivity
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * GamificationsShoppingPointActivity constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Process order and reward customer
     *
     * @param Order $order
     * @param bool $createObject
     */
    public function processOrder(Order $order, $createObject)
    {
        $shopingPoint = null;
        if ($createObject) {
            $shopingPoint = GamificationsShoppingPoint::create($order, true);
        }

        $shoppingPointOrderStates = Configuration::get(GamificationsConfig::SHOPPING_POINTS_ORDER_STATES);
        $shoppingPointOrderStates = json_decode($shoppingPointOrderStates, true);
        $shoppingPointPointsRatio = (int) Configuration::get(GamificationsConfig::SHOPPING_POINTS_RATIO);

        if ((!in_array($order->current_state, $shoppingPointOrderStates) && !empty($shoppingPointOrderStates)) ||
            0 >= $shoppingPointPointsRatio
        ) {
            return;
        }

        if (!Validate::isLoadedObject($shopingPoint)) {
            //@todo: get shopping point object
        }

        $context = Context::getContext();
        $customer = new Customer($order->id_customer);

        /** @var GamificationsCustomerRepository $customerRepository */
        $customerRepository = $this->em->getRepository('GamificationsCustomer');

        $idGamificationsCustomer = $customerRepository->findIdByCustomerId($order->id_customer, $context->shop->id);

        if (null === $idGamificationsCustomer) {
            $gamificationsCustomer = GamificationsCustomer::create($customer, true);
        } else {
            $gamificationsCustomer = new GamificationsCustomer($idGamificationsCustomer);
        }
    }
}
