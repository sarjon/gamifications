<?php

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
            $this->errors[] = $this->trans('Unexpected error occured', [], 'Modules.Gamifications.Shop');
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
            $gamificationsCustomer->id_customer = (int) $this->context->customer->id;
            $gamificationsCustomer->total_points = 0;
            $gamificationsCustomer->spent_points = 0;
            $gamificationsCustomer->referral_code = strtolower(Tools::passwdGen(16));

            if (!$gamificationsCustomer->save()) {
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