<?php

use PrestaShop\PrestaShop\Core\Foundation\Database\EntityManager;

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_.'gamification/vendor/autoload.php';

/**
 * Class Gamification
 */
class Gamification extends Module
{
    /**
     * Module admin controllers
     */
    const ADMIN_GAMIFICATION_MODULE_CONTROLLER = 'AdminGamificationModule';
    const ADMIN_GAMIFICATION_PREFERENCE_CONTROLLER = 'AdminGamificationPreference';
    const ADMIN_GAMIFICATION_CHALLANGE_CONTROLLER = 'AdminGamificationChallange';
    const ADMIN_GAMIFICATION_REWARD_CONTROLLER = 'AdminGamificationReward';

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
        $this->name = 'gamification';
        $this->author = 'Šarūnas Jonušas';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->need_instance = 0;

        parent::__construct();

        $this->ps_versions_compliancy = ['min' => '1.7.0.0', 'max' => _PS_VERSION_];
        $this->displayName = $this->trans('Gamification', [], 'Modules.Gamification');
        $this->description = $this->trans(
            'Increase customers loyality by adding various activities to your shop! 
             Daily rewards, challanges, ranks, points and prizes',
            [],
            'Modules.Gamification'
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
        $installer = new GamificationInstaller($this);

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
        $installer = new GamificationInstaller($this);

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
        return Tools::redirectAdmin($this->context->link->getAdminLink(self::ADMIN_GAMIFICATION_PREFERENCE_CONTROLLER));
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
}
