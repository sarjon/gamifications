<?php

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
