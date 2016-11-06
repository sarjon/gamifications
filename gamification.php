<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Class Gamification
 */
class Gamification extends Module
{
    /**
     * Gamification constructor.
     */
    public function __construct()
    {
        $this->name = 'gamification';
        $this->author = 'Šarūnas Jonušas';
        $this->tab = 'front_office_features';
        //@todo: change version before release
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
    }
}
