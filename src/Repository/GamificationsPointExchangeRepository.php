<?php
/**
 * This file is part of the Gamifications module.
 *
 * @author    Sarunas Jonusas, <jonusas.sarunas@gmail.com>
 * @copyright Copyright (c) permanent, Sarunas Jonusas
 * @license   Addons PrestaShop license limitation
 */

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

    /**
     * Find all availabe point exchange rewards for given customer groups
     *
     * @param array $idGroups
     * @param int $idShop
     * @param int $idLang
     *
     * @return array
     */
    public function findAllPointExchangeRewards(array $idGroups, $idShop, $idLang)
    {
        $sql = '
            SELECT pe.`points`, pe.`id_gamifications_point_exchange`, r.`id_gamifications_reward`, 
              r.`reward_type`, rl.`name`, rl.`description`
            FROM `'.$this->getPrefix().'gamifications_point_exchange` pe
            LEFT JOIN `'.$this->getPrefix().'gamifications_point_exchange_shop` pes
                ON pes.`id_gamifications_point_exchange` = pe.`id_gamifications_point_exchange`
            LEFT JOIN `'.$this->getPrefix().'gamifications_point_exchange_group` peg
                ON peg.`id_gamifications_point_exchange` = pe.`id_gamifications_point_exchange`
            LEFT JOIN `'.$this->getPrefix().'gamifications_reward` r
                ON r.`id_gamifications_reward` = pe.`id_reward`
            LEFT JOIN `'.$this->getPrefix().'gamifications_reward_lang` rl
                ON rl.`id_gamifications_reward` = pe.`id_reward`
            WHERE pe.`active` = 1
                AND pes.`id_shop` = '.(int)$idShop.'
                AND rl.`id_lang` = '.(int)$idLang.'
                AND peg.`id_group` IN ('.implode(',', array_map('intval', $idGroups)).')
        ';

        $results = $this->db->select($sql);

        if (!is_array($results)) {
            return [];
        }

        return $results;
    }
}
