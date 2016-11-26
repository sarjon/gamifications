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
                        'title' => $this->trans('Display help'),
                        'hint' =>
                            $this->trans('Choose whether to display various help messages & explanations').' '.
                            $this->trans('about activites, rewards, points & etc. in Back Office'),
                        'validation' => 'isBool',
                        'type' => 'bool',
                    ],
                    GamificationsConfig::FRONT_OFFICE_TITLE => [
                        'title' => $this->trans('Front Office title'),
                        'hint' => $this->trans('Title that are displayed in Front Office account page'),
                        'validation' => 'isGenericName',
                        'type' => 'textLang',
                        'class' => 'fixed-width-lg'
                    ],
                ],
                'submit' => [
                    'title' => $this->trans('Save'),
                ],
            ],
            'activities' => [
                'title' => $this->trans('Activity preferences'),
                'fields' => [
                    GamificationsConfig::DAILY_REWARDS_STATUS => [
                        'title' => $this->trans('Enable Daily Rewards'),
                        'hint' => $this->trans('Daily Rewards feature in Front Office'),
                        'validation' => 'isBool',
                        'type' => 'bool',
                    ],
                    GamificationsConfig::REFERRAL_STATUS => [
                        'title' => $this->trans('Enable Refferal Program'),
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
