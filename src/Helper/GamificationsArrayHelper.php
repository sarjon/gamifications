<?php
/**
 * This file is part of the Gamifications module.
 *
 * @author    Sarunas Jonusas, <jonusas.sarunas@gmail.com>
 * @copyright Copyright (c) permanent, Sarunas Jonusas
 * @license   Addons PrestaShop license limitation
 */

/**
 * Class GamificationsArrayHelper
 */
class GamificationsArrayHelper
{
    /**
     * Get random value from array
     *
     * @param array $data
     *
     * @return mixed|null
     */
    public static function getRandomValue(array $data)
    {
        if (empty($data)) {
            return null;
        }

        $randomKey = array_rand($data);

        return $data[$randomKey];
    }
}
