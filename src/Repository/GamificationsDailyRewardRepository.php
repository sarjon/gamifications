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
 * Class GamificationsDailyRewardRepository
 */
class GamificationsDailyRewardRepository extends EntityRepository
{
    /**
     * Find all customer group for daily reward
     *
     * @param int $idDailyReward
     * @param int $idShop
     *
     * @return array
     */
    public function findAllGroupIds($idDailyReward, $idShop)
    {
        $sql = '
            SELECT gdrg.`id_group`
            FROM `'.$this->getPrefix().'gamifications_daily_reward_group` gdrg
            LEFT JOIN `'.$this->getPrefix().'gamifications_daily_reward_shop` gdrs
                ON gdrg.`id_gamifications_daily_reward` = gdrs.`id_gamifications_daily_reward`
            WHERE gdrg.`id_gamifications_daily_reward` = '.(int)$idDailyReward.'
                AND gdrs.`id_shop` = '.(int)$idShop.'
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
     * Find all daily rewards by customer groups
     *
     * @param int $idShop
     * @param array $groupIds
     *
     * @return array
     */
    public function findAllByCustomerGroups($idShop, array $groupIds = [])
    {
        $sql = '
            SELECT gdr.`id_gamifications_daily_reward`, gdr.`id_reward`, gdr.`boost`
            FROM `'.$this->getPrefix().'gamifications_daily_reward` gdr
            LEFT JOIN `'.$this->getPrefix().'gamifications_daily_reward_shop` gdrs
                ON gdrs.`id_gamifications_daily_reward` = gdr.`id_gamifications_daily_reward`
            LEFT JOIN `'.$this->getPrefix().'gamifications_daily_reward_group` gdrg
                ON gdrg.`id_gamifications_daily_reward` = gdr.`id_gamifications_daily_reward`
            WHERE gdrs.`id_shop` = '.(int)$idShop.'
                AND gdr.`active` = 1
                '.(!empty($groupIds) ?
                ' AND gdrg.`id_group` IN ('.implode(',', array_map('intval', $groupIds)).')' : '').'
            GROUP BY gdr.`id_gamifications_daily_reward`';

        $results = $this->db->select($sql);

        if (!$results) {
            return [];
        }

        return $results;
    }
}
