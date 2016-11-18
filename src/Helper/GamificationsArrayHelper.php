<?php

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
