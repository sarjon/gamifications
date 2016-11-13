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
                'title' => $this->trans('General preferences'),
                'fields' => [
                    GamificationsConfig::DISPLAY_EXPLANATIONS => [
                        'title' => $this->trans('Display explanations'),
                        'hint' =>
                            $this->trans('Choose whether to display various explanations').' '.
                            $this->trans('about activites, rewards, points & etc. in Back Office'),
                        'validation' => 'isBool',
                        'type' => 'bool',
                    ],
                    GamificationsConfig::FRONT_OFFICE_TITLE => [
                        'title' => $this->trans('Front Office title'),
                        'hint' =>
                            $this->trans('Choose whether to display various explanations').' '.
                            $this->trans('about activites, rewards, points & etc. in Back Office'),
                        'validation' => 'isGenericName',
                        'type' => 'textLang',
                        'class' => 'fixed-width-lg'
                    ],
                ],
                'submit' => [
                    'title' => $this->trans('Save'),
                ],
            ],
            'challange_preferences' => [
                'title' => $this->trans('Challange preferences'),
                'fields' => [
                    GamificationsConfig::CHALLANGES_STATUS => [
                        'title' => $this->trans('Enable challanges'),
                        'hint' => $this->trans('Challanges feature in Front Office'),
                        'validation' => 'isBool',
                        'type' => 'bool',
                    ],
                    GamificationsConfig::CHALLANGES_DISPLAY_REWARDS => [
                        'title' => $this->trans('Display rewards'),
                        'hint' =>
                            $this->trans('Display rewards that customer gets after completing challange'),
                        'validation' => 'isBool',
                        'type' => 'bool',
                    ],
                ],
                'submit' => [
                    'title' => $this->trans('Save'),
                ],
            ],
            'daily_rewards' => [
                'title' => $this->trans('Daily rewards'),
                'fields' => [
                    GamificationsConfig::DAILY_DAILY_REWARDS_STATUS => [
                        'title' => $this->trans('Enable Daily Rewards'),
                        'hint' => $this->trans('Daily Rewards feature in Front Office'),
                        'validation' => 'isBool',
                        'type' => 'bool',
                    ],
                ],
                'submit' => [
                    'title' => $this->trans('Save'),
                ],
            ],
        ];
    }
}
