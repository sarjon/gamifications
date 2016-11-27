<?php

/**
 * Class AdminGamificationsPreferenceController
 */
class AdminGamificationsPreferenceController extends GamificationsAdminController
{
    /**
     * Initalize options
     */
    protected function initOptions()
    {
        $this->fields_options = [
            'general' => [
                'title' => $this->trans('General preferences', [], 'Modules.Gamifications.Admin'),
                'fields' => [
                    GamificationsConfig::DISPLAY_EXPLANATIONS => [
                        'title' => $this->trans('Back Office help', [], 'Modules.Gamifications.Admin'),
                        'hint' => $this->trans(
                            'Choose whether to display various help messages & explanations about gamifications module',
                            [],
                            'Modules.Gamifications.Admin'
                        ),
                        'validation' => 'isBool',
                        'type' => 'bool',
                    ],
                    GamificationsConfig::FRONT_OFFICE_TITLE => [
                        'title' => $this->trans('Front Office title', [], 'Modules.Gamifications.Admin'),
                        'hint' => $this->trans(
                            'Title that are displayed in Front Office account page',
                            [],
                            'Modules.Gamifications.Admin'
                        ),
                        'validation' => 'isGenericName',
                        'type' => 'textLang',
                        'class' => 'fixed-width-lg'
                    ],
                ],
                'submit' => [
                    'title' => $this->trans('Save', [], 'Modules.Gamifications.Admin'),
                ],
            ],
            'activities' => [
                'title' => $this->trans('Activity preferences', [], 'Modules.Gamifications.Admin'),
                'description' => $this->trans(
                    'Configure activity before enabling it to avoid customers dissatisfaction',
                    [] ,
                    'Modules.Gamifications.Admin'
                ),
                'fields' => [
                    GamificationsConfig::DAILY_REWARDS_STATUS => [
                        'title' => $this->trans('Enable Daily Rewards', [], 'Modules.Gamifications.Admin'),
                        'validation' => 'isBool',
                        'type' => 'bool',
                    ],
                    GamificationsConfig::REFERRAL_PROGRAM_STATUS => [
                        'title' => $this->trans('Enable Refferal Program', [], 'Modules.Gamifications.Admin'),
                        'validation' => 'isBool',
                        'type' => 'bool',
                    ],
                ],
                'submit' => [
                    'title' => $this->trans('Save', [], 'Modules.Gamifications.Admin'),
                ],
            ],
        ];
    }
}
