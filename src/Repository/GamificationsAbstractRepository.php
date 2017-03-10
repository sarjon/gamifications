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
