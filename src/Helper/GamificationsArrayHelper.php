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

    /**
     * Remove value from array
     *
     * @param mixed $value
     * @param array $data
     *
     * @return void
     */
    public static function removeValue($value, array &$data)
    {
        if (empty($data)) {
            return;
        }

        $key = array_search($value, $data);

        if (false !== $key) {
            unset($data[$key]);
        }
    }
}
