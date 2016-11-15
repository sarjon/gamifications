<?php

use PrestaShop\PrestaShop\Core\Foundation\Database\EntityRepository;

/**
 * Class GamificationsRewardRepository
 */
class GamificationsRewardRepository extends EntityRepository
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
            FROM `'.$this->getPrefix().'gamifications_reward` gr
            LEFT JOIN `'.$this->getPrefix().'gamifications_reward_lang` grl
                ON grl.`id_gamifications_reward` = gr.`id_gamifications_reward`
            LEFT JOIN `'.$this->getPrefix().'gamifications_reward_shop` grs
                ON grs.`id_gamifications_reward` = grl.`id_gamifications_reward`
            WHERE grl.`id_lang` = '.(int)$idLang.'
                AND grs.`id_shop` = '.(int)$idShop.'
                '.(!empty($exludeRewardTypes) ?
                ' AND gr.`reward_type` NOT IN ('.implode(',', array_map('intval', $exludeRewardTypes)).')' :
                ''
            );

        $results = $this->db->select($sql);

        if (!$results || !is_array($results)) {
            return [];
        }

        return $results;
    }
}
