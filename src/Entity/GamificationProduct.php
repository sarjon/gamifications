<?php

/**
 * Class GamificationProduct
 */
class GamificationProduct extends Product
{
    /**
     * @return string
     */
    public static function getRepositoryClassName()
    {
        return 'GamificationProductRepository';
    }
}
