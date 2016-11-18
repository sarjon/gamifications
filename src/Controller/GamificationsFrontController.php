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
        /** @var GamificationsCustomerRepository $playerRepository */
        $playerRepository = $this->module->getEntityManager()->getRepository('GamificationsCustomer');
        $idGamificationsCustomer =
            $playerRepository->findIdByCustomerId($this->context->customer->id, $this->context->shop->id);

        $player = new GamificationsCustomer((int) $idGamificationsCustomer, null, $this->context->shop->id);

        if (null === $idGamificationsCustomer && !Validate::isLoadedObject($player)) {
            $player->id_customer = (int) $this->context->customer->id;
            $player->total_points = 0;
            $player->spent_points = 0;

            if (!$player->save()) {
                return false;
            }
        }

        $this->gamificationCustomer = $player;
        $this->gamificationCustomerRepository = $playerRepository;

        return true;
    }
}