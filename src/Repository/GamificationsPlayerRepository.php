<?php

use PrestaShop\PrestaShop\Core\Foundation\Database\EntityRepository;

/**
 * Class GamificationsCustomerRepository
 */
class GamificationsCustomerRepository extends EntityRepository
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
            SELECT gp.`id_gamifications_customer`
            FROM `'.$this->getPrefix().'gamifications_customer` gp
            LEFT JOIN `'.$this->getPrefix().'gamifications_customer_shop` gps
                ON gps.`id_gamifications_customer` = gp.`id_gamifications_customer`
            WHERE gp.`id_customer` = '.(int)$idCustomer.'
                AND gps.`id_shop` = '.(int)$idShop.'
            LIMIT 1
        ';

        $results = $this->db->select($sql);

        if (!$results || !isset($results[0]['id_gamifications_customer'])) {
            return null;
        }

        return (int) $results[0]['id_gamifications_customer'];
    }

    /**
     * Find most recent activity
     *
     * @param int $idCustomer
     * @param int $activityType
     * @param int $idShop
     *
     * @return array|null
     */
    public function findMostRecentActivity($idCustomer, $activityType, $idShop)
    {
        $sql = '
            SELECT gah.`id_reward`, gah.`date_add` 
            FROM `'.$this->getPrefix().'gamifications_activity_history` gah
            WHERE gah.`id_customer` = '.(int)$idCustomer.'
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
