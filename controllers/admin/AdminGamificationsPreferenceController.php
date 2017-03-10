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
 * Class AdminGamificationsPreferenceController
 */
class AdminGamificationsPreferenceController extends GamificationsAdminController
{
    public function init()
    {
        $this->initOptions();

        parent::init();
    }

    /**
     * Initalize options
     */
    protected function initOptions()
    {
        if (!empty($this->fields_options)) {
            return;
        }

        $this->fields_options = [
            'general' => [
                'title' => $this->l('General preferences'),
                'fields' => [
                    GamificationsConfig::DISPLAY_HELP => [
                        'title' => $this->l('Back Office help'),
                        'hint' =>
                            $this->l('Choose whether to display various help messages & explanations about module'),
                        'validation' => 'isBool',
                        'type' => 'bool',
                    ],
                    GamificationsConfig::FRONT_OFFICE_TITLE => [
                        'title' => $this->l('Front Office title'),
                        'hint' => $this->l('Title that are displayed in Front Office account page'),
                        'validation' => 'isGenericName',
                        'type' => 'textLang',
                        'class' => 'fixed-width-lg'
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                ],
            ],
            'activities' => [
                'title' => $this->l('Activity preferences'),
                'fields' => [
                    GamificationsConfig::DAILY_REWARDS_STATUS => [
                        'title' => $this->l('Enable Daily Rewards'),
                        'validation' => 'isBool',
                        'type' => 'bool',
                    ],
                    GamificationsConfig::REFERRAL_PROGRAM_STATUS => [
                        'title' => $this->l('Enable Refferal Program'),
                        'validation' => 'isBool',
                        'type' => 'bool',
                    ],
                    GamificationsConfig::SHOPPING_POINTS_STATUS => [
                        'title' => $this->l('Enable Shopping points'),
                        'validation' => 'isBool',
                        'type' => 'bool',
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                ],
            ],
        ];
    }
}
