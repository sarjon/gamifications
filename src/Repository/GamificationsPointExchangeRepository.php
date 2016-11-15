<?php

use PrestaShop\PrestaShop\Core\Foundation\Database\EntityRepository;

/**
 * Class GamificationsPointExchangeRepository
 */
class GamificationsPointExchangeRepository extends EntityRepository
{
    /**
     * Find all groups that point exchange apply
     *
     * @param int $idPointExchange
     * @param int $idShop
     *
     * @return array
     */
    public function findAllGroupIds($idPointExchange, $idShop)
    {
        $sql = '
            SELECT gpeg.`id_group`
            FROM `'.$this->getPrefix().'gamifications_point_exchange_group` gpeg
            LEFT JOIN `'.$this->getPrefix().'gamifications_point_exchange_shop` gpes
                ON gpeg.`id_gamifications_point_exchange` = gpes.`id_gamifications_point_exchange`
            WHERE gpeg.`id_gamifications_point_exchange` = '.(int)$idPointExchange.'
                AND gpes.`id_shop` = '.(int)$idShop.'
        ';

        $results = $this->db->select($sql);

        if (!$results || !is_array($results)) {
            return [];
        }

        $groupIds = [];
        foreach ($results as $result) {
            $groupIds[] = (int) $result['id_group'];
        }

        return $groupIds;
    }
}
