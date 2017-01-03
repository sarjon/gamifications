<?php
/**
 * This file is part of the Gamifications module.
 *
 * @author    Sarunas Jonusas, <jonusas.sarunas@gmail.com>
 * @copyright Copyright (c) permanent, Sarunas Jonusas
 * @license   Addons PrestaShop license limitation
 */

abstract class GamificationsAbstractRepository
{
    /**
     * @var Db
     */
    protected $db;

    /**
     * GamificationsAbstractRepository constructor.
     */
    public function __construct()
    {
        $this->db = Db::getInstance();
    }
}
