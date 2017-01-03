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
class GamificationsRankRepository extends GamificationsAbstractRepository
{
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

    /**
     * Check if order is already processed or not
     *
     * @param Order $order
     *
     * @return bool
     */
    public function isActiveOrder(Order $order)
    {
        $sql = '
            SELECT COUNT(*)
            FROM `'._DB_PREFIX_.'gamifications_rank_order`
            WHERE `id_customer` = '.(int)$order->id_customer.'
                AND `id_order` = '.(int)$order->id.'
                AND `active` = 1
        ';

        $count = $this->db->getValue($sql);

        return (bool) $count;
    }

    /**
     * Find all ranks ids and names
     *
     * @param int $idShop
     * @param int $idLang
     * @param array|int[] $excludeIds
     *
     * @return array
     */
    public function findAllIdsAndNames($idShop, $idLang, $excludeIds = [])
    {
        $sql = '
            SELECT r.`id_gamifications_rank`, rl.`name`
            FROM `'._DB_PREFIX_.'gamifications_rank` r
            LEFT JOIN `'._DB_PREFIX_.'gamifications_rank_lang` rl
                ON rl.`id_gamifications_rank` = r.`id_gamifications_rank`
                    AND rl.`id_lang` = '.(int)$idLang.'
            WHERE r.`id_shop` = '.(int)$idShop.
            (!empty($excludeIds) ?
                ' AND r.`id_gamifications_rank` NOT IN ('.implode(',', array_map('intval', $excludeIds)).')' :
                ''
            ).'
        ';

        $results = $this->db->executeS($sql);

        if (!is_array($results) || !$results) {
            return [];
        }

        return $results;
    }
}
