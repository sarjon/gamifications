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
     *
     * @return array
     */
    public function findAllNamesAndIds($idLang, $idShop)
    {
        $sql = '
            SELECT grl.`id_gamifications_reward`, grl.`name`
            FROM `'.$this->getPrefix().'gamifications_reward_lang` grl
            LEFT JOIN `'.$this->getPrefix().'gamifications_reward_shop` grs
                ON grs.`id_gamifications_reward` = grl.`id_gamifications_reward`
            WHERE grl.`id_lang` = '.(int)$idLang.'
                AND grs.`id_shop` = '.(int)$idShop.'
        ';

        $results = $this->db->select($sql);

        if (!$results || !is_array($results)) {
            return [];
        }

        return $results;
    }
}
