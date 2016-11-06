<?php

/**
 * Class AdminGamificationPreferenceController
 */
class AdminGamificationPreferenceController extends GamificationAdminController
{
    protected function initOptions()
    {
        $this->fields_options = [
            'challange_preferences' => [
                'title' => $this->trans('Challange preferences'),
                'fields' => [
                    GamificationConfig::CHALLANGES_STATUS => [
                        'title' => $this->trans('Enable challanges'),
                        'hint' => $this->trans('Challanges feature in Front Office'),
                        'validation' => 'isBool',
                        'type' => 'bool',
                    ],
                    GamificationConfig::CHALLANGES_DISPLAY_REWARDS => [
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
