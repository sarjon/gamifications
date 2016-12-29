<?php
/**
 * This file is part of the Gamifications module.
 *
 * @author    Sarunas Jonusas, <jonusas.sarunas@gmail.com>
 * @copyright Copyright (c) permanent, Sarunas Jonusas
 * @license   Addons PrestaShop license limitation
 */

/**
 * Class GamificationsRankActivity
 */
class GamificationsRankActivity
{
    /**
     * Process order
     *
     * @param Order $order
     * @param bool $create
     */
    public function process(Order $order, $create = false)
    {
        if ($create) {
            GamificationsRank::createRankOrder($order);
        }

        //@todo: process order
    }
}
