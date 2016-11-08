<?php

use PrestaShop\PrestaShop\Core\Foundation\Database\EntityManager;

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Class Gamifications
 */
class Gamifications extends Module
{
    /**
     * Module admin controllers
     */
    const ADMIN_GAMIFICATIONS_MODULE_CONTROLLER = 'AdminGamificationsModule';
    const ADMIN_GAMIFICATIONS_PREFERENCE_CONTROLLER = 'AdminGamificationsPreference';
    const ADMIN_GAMIFICATIONS_CHALLANGE_CONTROLLER = 'AdminGamificationsChallange';
    const ADMIN_GAMIFICATIONS_REWARD_CONTROLLER = 'AdminGamificationsReward';

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * Gamification constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->name = 'gamifications';
        $this->author = 'Šarūnas Jonušas';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->need_instance = 0;

        parent::__construct();

        $this->requireAutoloader();

        $this->ps_versions_compliancy = ['min' => '1.7.0.0', 'max' => _PS_VERSION_];
        $this->displayName = $this->trans('Gamification', [], 'Modules.Gamifications');
        $this->description = $this->trans(
            'Increase customers loyality by adding various activities to your shop! 
             Daily rewards, challanges, ranks, points and prizes',
            [],
            'Modules.Gamifications'
        );

        $this->em = $em;
    }

    /**
     * Process module installation
     *
     * @return bool
     */
    public function install()
    {
        $installer = new GamificationsInstaller($this);

        if (!parent::install() || !$installer->install()) {
            return false;
        }

        return true;
    }

    /**
     * Process module uninstall
     *
     * @return bool
     */
    public function uninstall()
    {
        $installer = new GamificationsInstaller($this);

        if (!$installer->uninstall() || !parent::uninstall()) {
            return false;
        }

        return true;
    }

    /**
     * Redirect to Preference controller
     */
    public function getContent()
    {
        return Tools::redirectAdmin(
            $this->context->link->getAdminLink(self::ADMIN_GAMIFICATIONS_PREFERENCE_CONTROLLER)
        );
    }

    /**
     * Get entity manager
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * Require autoloader
     */
    private function requireAutoloader()
    {
        require_once $this->getLocalPath().'vendor/autoload.php';
    }
}
