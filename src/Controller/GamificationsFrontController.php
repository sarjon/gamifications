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
 * Class GamificationsFrontController
 */
abstract class GamificationsFrontController extends ModuleFrontController
{
    /**
     * @var Gamifications
     */
    public $module;

    /**
     * @var GamificationsCustomer
     */
    protected $gamificationCustomer;

    /**
     * @var GamificationsCustomerRepository
     */
    protected $gamificationCustomerRepository;

    /**
     * Custom init
     */
    public function init()
    {
        parent::init();

        if (!$this->loadGamificationsCustomerObject()) {
            $this->errors[] = $this->l('Unexpected error occured');
            $this->redirectWithNotifications($this->context->link->getPageLink('my-account'));
        }
    }

    /**
     * Load gamifications customer object
     *
     * @return bool
     */
    private function loadGamificationsCustomerObject()
    {
        /** @var GamificationsCustomerRepository $customerRepository */
        $customerRepository = $this->module->getEntityManager()->getRepository('GamificationsCustomer');
        $id = $customerRepository->findIdByCustomerId($this->context->customer->id, $this->context->shop->id);

        $gamificationsCustomer = new GamificationsCustomer((int) $id, null, $this->context->shop->id);

        if (null === $id && !Validate::isLoadedObject($gamificationsCustomer)) {
            if (!GamificationsCustomer::create($this->context->customer)) {
                return false;
            }
        }

        $this->gamificationCustomer = $gamificationsCustomer;
        $this->gamificationCustomerRepository = $customerRepository;

        return true;
    }

    /**
     * Get entity manager
     *
     * @return \PrestaShop\PrestaShop\Core\Foundation\Database\EntityManager
     */
    protected function getEntityManager()
    {
        return $this->module->getEntityManager();
    }
}
