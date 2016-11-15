<?php

use PrestaShop\PrestaShop\Core\Foundation\Database\EntityRepository;

/**
 * Class GamificationsPlayerRepository
 */
class GamificationsPlayerRepository extends EntityRepository
{
    /**
     * Find player id by customer id
     *
     * @param int $idCustomer
     * @param int $idShop
     *
     * @return int|null
     */
    public function findIdByCustomerId($idCustomer, $idShop)
    {
        $sql = '
            SELECT gp.`id_gamifications_player`
            FROM `'.$this->getPrefix().'gamifications_player` gp
            LEFT JOIN `'.$this->getPrefix().'gamifications_player_shop` gps
                ON gps.`id_gamifications_player` = gp.`id_gamifications_player`
            WHERE gp.`id_customer` = '.(int)$idCustomer.'
                AND gps.`id_shop` = '.(int)$idShop.'
            LIMIT 1
        ';

        $results = $this->db->select($sql);

        if (!$results || !isset($results[0]['id_gamifications_player'])) {
            return null;
        }

        return (int) $results[0]['id_gamifications_player'];
    }

    /**
     * Find most recent
     *
     * @param int $idPlayer
     * @param int $activityType
     * @param int $idShop
     *
     * @return array|null
     */
    public function findMostRecentActivity($idPlayer, $activityType, $idShop)
    {
        $sql = '
            SELECT gah.`id_reward`, gah.`date_add` 
            FROM `'.$this->getPrefix().'gamifications_activity_history` gah
            WHERE gah.`id_gamifications_player` = '.(int)$idPlayer.'
                AND gah.`activity_type` = '.(int)$activityType.'
                AND gah.`id_shop` = '.(int)$idShop.'
            ORDER BY gah.`date_add` DESC
            LIMIT 1
        ';

        $results = $this->db->select($sql);

        if (!$results || !is_array($results[0])) {
            return null;
        }

        return $results[0];
    }
}
