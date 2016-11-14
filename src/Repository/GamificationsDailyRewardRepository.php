<?php

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
}
