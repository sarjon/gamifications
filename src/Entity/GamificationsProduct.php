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
