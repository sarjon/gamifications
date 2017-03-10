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
        $shoppingPoint = null;
        if ($createObject) {
            $shoppingPoint = GamificationsShoppingPoint::create($order, true);
        }

        $shoppingPointOrderStates = Configuration::get(GamificationsConfig::SHOPPING_POINTS_ORDER_STATES);
        $shoppingPointOrderStates = json_decode($shoppingPointOrderStates, true);
        $shoppingPointPointsRatio = (int) Configuration::get(GamificationsConfig::SHOPPING_POINTS_RATIO);

        if ((!in_array($order->current_state, $shoppingPointOrderStates) && !empty($shoppingPointOrderStates)) ||
            0 >= $shoppingPointPointsRatio
        ) {
            return;
        }

        if (!Validate::isLoadedObject($shoppingPoint)) {
            $idCustomer = (int) $order->id_customer;
            $idOrder = (int) $order->id;
            $idShop = (int) $order->id_shop;

            /** @var GamificationsShoppingPointRepository $shoppingPointRepository */
            $shoppingPointRepository = $this->em->getRepository('GamificationsShoppingPoint');
            $idShoppingPoint =
                $shoppingPointRepository->findShoppingPointIdByCustomerIdAndOrderId($idCustomer, $idOrder, $idShop);
            $shoppingPoint = new GamificationsShoppingPoint($idShoppingPoint);
            if (null === $idShoppingPoint || !Validate::isLoadedObject($shoppingPoint)) {
                return;
            }
        }

        $this->rewardCustomer($shoppingPoint, $order);
    }

    /**
     * Reward customer
     *
     * @param GamificationsShoppingPoint $shoppingPoint
     * @param Order $order
     */
    protected function rewardCustomer(GamificationsShoppingPoint $shoppingPoint, Order $order)
    {
        $shoppingPointPointsRatio = (int) Configuration::get(GamificationsConfig::SHOPPING_POINTS_RATIO);

        $idDefaultCurrency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
        $defaultCurrency = new Currency($idDefaultCurrency);

        $totalPaid = $order->getTotalPaid($defaultCurrency);
        $totalPaid = floor($totalPaid);

        $earnedPoints = $shoppingPointPointsRatio * $totalPaid;

        if (0 >= $earnedPoints) {
            return;
        }

        /** @var GamificationsCustomerRepository $customerRepository */
        $customerRepository = $this->em->getRepository('GamificationsCustomer');
        $idGamificationsCustomer = $customerRepository->findIdByCustomerId($order->id_customer, $order->id_shop);

        if (null === $idGamificationsCustomer) {
            $customer = new Customer($shoppingPoint->id_customer);
            $gamificationsCustomer = GamificationsCustomer::create($customer, true);
        } else {
            $gamificationsCustomer = new GamificationsCustomer($idGamificationsCustomer);
        }

        if ($gamificationsCustomer->addPoints($earnedPoints)) {
            $shoppingPoint->active = false;
            $shoppingPoint->save();

            $reward = new GamificationsReward();
            $reward->reward_type = GamificationsReward::REWARD_TYPE_POINTS;
            $reward->points = $earnedPoints;
            GamificationsActivityHistory::log(
                $reward,
                $shoppingPoint->id_customer,
                GamificationsActivity::TYPE_SHOPPING_POINT
            );
        }
    }

    /**
     * Calculate how many ponts customer will get after placing an order
     *
     * @return int
     */
    public function calculatePossiblePoints()
    {
        $context = Context::getContext();

        $orderTotalPrice = $context->cart->getOrderTotal();

        $includeShippingPrice = (bool) Configuration::get(GamificationsConfig::SHOPPING_POINTS_INCLUDE_SHIPPNG_PRICE);

        if (!$includeShippingPrice) {
            $shippingPrice = $context->cart->getTotalShippingCost();
            $orderTotalPrice -= $shippingPrice;
        }

        $convertedPrice = Tools::convertPrice($orderTotalPrice, $context->currency, false);
        $convertedPrice = floor($convertedPrice);

        $shoppingPointPointsRatio = (int) Configuration::get(GamificationsConfig::SHOPPING_POINTS_RATIO);

        $possiblePoints = $shoppingPointPointsRatio * $convertedPrice;

        return $possiblePoints;
    }
}
