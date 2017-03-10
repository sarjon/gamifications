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
            SELECT gc.`id_gamifications_customer`
            FROM `'.$this->getPrefix().'gamifications_customer` gc
            WHERE gc.`id_customer` = '.(int)$idCustomer.'
                AND gc.`id_shop` = '.(int)$idShop.'
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

    /**
     * Find gamifications customer data by referral code
     *
     * @param string $referralCode
     * @param int $idShop
     *
     * @return array|null
     */
    public function findByReferralCode($referralCode, $idShop)
    {
        $sql = '
            SELECT gc.`id_gamifications_customer`, gc.`id_customer`
            FROM `'.$this->getPrefix().'gamifications_customer` gc
            WHERE gc.`referral_code` = "'.$this->db->escape($referralCode).'"
                AND gc.`id_shop` = '.(int)$idShop.'
            LIMIT 1
        ';

        $results = $this->db->select($sql);

        if (!$results || !isset($results[0])) {
            return null;
        }

        return $results[0];
    }

    /**
     * Find invited customers count
     *
     * @param int $idCustomer
     * @param int $idShop
     *
     * @return int
     */
    public function findInvitedCustomersCount($idCustomer, $idShop)
    {
        $sql =
            '
            SELECT COUNT(`id_gamifications_referral`)
            FROM `'.$this->getPrefix().'gamifications_referral`
            WHERE `id_referral_customer` = '.(int)$idCustomer.'
                AND `id_shop` = '.(int)$idShop.'
        ';

        $db = Db::getInstance();

        $count = $db->getValue($sql);

        return (int) $count;
    }
}
