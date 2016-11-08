<?php

/**
 * Class AdminGamificationsPreferenceController
 */
class AdminGamificationsPreferenceController extends GamificationsAdminController
{
    protected function initOptions()
    {
        $this->fields_options = [
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
        ];
    }
}
