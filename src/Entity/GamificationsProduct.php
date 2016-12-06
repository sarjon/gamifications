<?php
/**
 * This file is part of the Gamifications module.
 *
 * @author    Sarunas Jonusas, <jonusas.sarunas@gmail.com>
 * @copyright Copyright (c) permanent, Sarunas Jonusas
 * @license   Addons PrestaShop license limitation
 */

/**
 * Class GamificationsProduct
 */
class GamificationsProduct extends Product
{
    /**
     * @return string
     */
    public static function getRepositoryClassName()
    {
        return 'GamificationsProductRepository';
    }
}
