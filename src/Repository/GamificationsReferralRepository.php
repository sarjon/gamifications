<?php

use PrestaShop\PrestaShop\Core\Foundation\Database\EntityRepository;

/**
 * Class GamificationsReferralRepository
 */
class GamificationsReferralRepository extends EntityRepository
{
    /**
     * Find referral customer id by new customer id
     *
     * @param int $idNewCustomer
     * @param int $idShop
     *
     * @return int|null Return id if referral customer were found or NULL otherwise
     */
    public function findReferralCustomerId($idNewCustomer, $idShop)
    {
        $sql = '
            SELECT `id_referral_customer`
            FROM `'.$this->getPrefix().'gamifications_referral`
            WHERE `id_invited_customer` = '.(int)$idNewCustomer.'
                AND `id_shop` = '.(int)$idShop.'
                AND `active` = 1
            LIMIT 1
        ';

        $results = $this->db->select($sql);

        if (!is_array($results) || !$results) {
            return null;
        }

        return (int) $results[0]['id_referral_customer'];
    }
}
