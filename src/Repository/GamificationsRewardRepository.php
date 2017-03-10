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

/**
 * Class GamificationsRewardRepository
 */
class GamificationsRewardRepository extends GamificationsAbstractRepository
{
    /**
     * Find all rewards by given language id
     *
     * @param int $idLang
     * @param int $idShop
     * @param array $exludeRewardTypes
     *
     * @return array
     */
    public function findAllNamesAndIds($idLang, $idShop, array $exludeRewardTypes = [])
    {
        $sql = '
            SELECT gr.`id_gamifications_reward`, grl.`name`
            FROM `'._DB_PREFIX_.'gamifications_reward` gr
            LEFT JOIN `'._DB_PREFIX_.'gamifications_reward_lang` grl
                ON grl.`id_gamifications_reward` = gr.`id_gamifications_reward`
            LEFT JOIN `'._DB_PREFIX_.'gamifications_reward_shop` grs
                ON grs.`id_gamifications_reward` = grl.`id_gamifications_reward`
            WHERE grl.`id_lang` = '.(int)$idLang.'
                AND grs.`id_shop` = '.(int)$idShop.
                (!empty($exludeRewardTypes) ?
                ' AND gr.`reward_type` NOT IN ('.implode(',', array_map('intval', $exludeRewardTypes)).')' :
                ''
            );

        $results = $this->db->executeS($sql);

        if (!$results || !is_array($results)) {
            return [];
        }

        return $results;
    }

    /**
     * Check if reward is in use (in activities, in points exchange)
     *
     * @param int $idReward
     *
     * @return int
     */
    public function isRewardInUse($idReward)
    {
        $count = 0;
        $sqls = [];

        $sqls[] = '
            SELECT COUNT(pe.`id_reward`)
            FROM `'._DB_PREFIX_.'gamifications_point_exchange` pe
            WHERE pe.`id_reward` = '.(int)$idReward.'
        ';

        $sqls[] = '
            SELECT COUNT(dr.`id_reward`)
            FROM `'._DB_PREFIX_.'gamifications_daily_reward` dr
            WHERE dr.`id_reward` = '.(int)$idReward.'
        ';

        foreach ($sqls as $sql) {
            $value = $this->db->getValue($sql);

            if (is_numeric($value)) {
                $count += (int) $value;
            }
        }


        $referralReward = (int) Configuration::get(GamificationsConfig::REFERRAL_REWARD);
        $newCustomerReward = (int) Configuration::get(GamificationsConfig::REFERRAL_NEW_CUSTOMER_REWARD);

        $idsRewards = [$referralReward, $newCustomerReward];

        if (in_array($idReward, $idsRewards)) {
            $count++;
        }

        return (bool) $count;
    }
}
