<?php
/**
 * This file is part of the Gamifications module.
 *
 * @author    Sarunas Jonusas, <jonusas.sarunas@gmail.com>
 * @copyright Copyright (c) permanent, Sarunas Jonusas
 * @license   Addons PrestaShop license limitation
 */

/**
 * Class GamificationsRankRepository
 */
class GamificationsRankRepository
{
    /**
     * @var Db
     */
    private $db;

    /**
     * GamificationsRankRepository constructor.
     */
    public function __construct()
    {
        $this->db = Db::getInstance();
    }

    /**
     * Find all currently used groups
     *
     * @param int $idShop
     *
     * @return array|int[]
     */
    public function findAllUsedGroupIds($idShop)
    {
        $sql = '
            SELECT `id_group`
            FROM `'._DB_PREFIX_.'gamifications_rank`
            WHERE `id_shop` = '.(int)$idShop.'
        ';

        $ids = [];

        $query = $this->db->query($sql);

        while ($row = $this->db->nextRow($query)) {
            $ids[] = (int) $row['id_group'];
        }

        return $ids;
    }
}
