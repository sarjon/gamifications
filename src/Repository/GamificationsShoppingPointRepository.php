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
 * Class GamificationsShoppingPointRepository
 */
class GamificationsShoppingPointRepository extends EntityRepository
{
    /**
     * @param int $idCustomer
     * @param int $idOrder
     * @param int $idShop
     * @param bool $active
     *
     * @return int|null
     */
    public function findShoppingPointIdByCustomerIdAndOrderId($idCustomer, $idOrder, $idShop, $active = true)
    {
        $query = new DbQuery();

        $query->select($this->getIdFieldName());
        $query->from($this->entityMetaData->getTableName());
        $query->where('`id_customer` = '.(int)$idCustomer.' AND `id_order` = '.(int)$idOrder);
        $query->where('`id_shop` = '.(int)$idShop);
        $query->where('`active` = '.(int)$active);

        $results = $this->db->select($query);

        if (!is_array($results) || !$results) {
            return null;
        }

        return (int) $results[0][$this->getIdFieldName()];
    }
}
