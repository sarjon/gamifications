<?php

use PrestaShop\PrestaShop\Core\Foundation\Database\EntityRepository;

/**
 * Class GamificationProductRepository
 */
class GamificationProductRepository extends EntityRepository
{
    /**
     * Find all products by query, idShop and idLang
     *
     * @param string $query
     * @param int $limit
     * @param int $idLang
     * @param int $idShop
     *
     * @return array
     */
    public function findAllProductsNamesAndIdsByQuery($query, $limit, $idLang, $idShop)
    {
        $sql = '
            SELECT pl.`id_product`, pl.`name`
            FROM `'.$this->getPrefix().'product_lang` pl
            WHERE pl.`id_shop` = '.(int)$idShop.'
                AND pl.`id_lang` = '.(int)$idLang.'
                AND pl.`name` LIKE "%'.$this->db->escape($query).'%"
            LIMIT '.(int)$limit.'
        ';

        $result = $this->db->select($sql);

        if (!$result || !is_array($result)) {
            return [];
        }

        $products = [];

        foreach ($result as $row) {
            $products[] = ['id_product' => $row['id_product'], 'name' => $row['name']];
        }

        return $products;
    }
}
