<?php

/**
 * Class AdminGamificationRewardController
 */
class AdminGamificationRewardController extends GamificationAdminController
{
    /**
     * AdminGamificationRewardController constructor.
     */
    public function __construct()
    {
        $this->className = 'GamificationReward';
        $this->table = GamificationReward::$definition['table'];
        $this->identifier = GamificationReward::$definition['primary'];

        parent::__construct();
    }

    /**
     * Add custom links in page header
     */
    public function initPageHeaderToolbar()
    {
        if (empty($this->display)) {
            $this->page_header_toolbar_btn['new_reward'] = [
                'href' => self::$currentIndex.'&addgamification_reward&token='.$this->token,
                'desc' => $this->trans('Add new reward'),
                'icon' => 'process-icon-new',
            ];
        }

        parent::initPageHeaderToolbar();
    }

    /**
     * Initialize list with rewards
     */
    protected function initList()
    {
        $this->fields_list = [
            'id_gamification_reward' => [
                'title' => $this->trans('ID'),
                'width' => 20,
                'type' => 'text',
            ],
            'name' => [
                'title' => $this->trans('Reward name'),
                'type' => 'text',
            ],
            'reward_type' => [
                'title' => $this->trans('Reward type'),
                'type' => 'text',
            ],
        ];
    }

    protected function initForm()
    {
        $this->lang = true;
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $defaultCurrency = Currency::getDefaultCurrency();

        $this->fields_form = [
            'legend' => [
                'title' => $this->trans('Reward'),
            ],
            'input' => [
                [
                    'label' => $this->trans('Reward name'),
                    'type' => 'text',
                    'name' => 'name',
                    'required' => true,
                    'lang' => true,
                ],
                [
                    'label' => $this->trans('Reward type'),
                    'type' => 'select',
                    'name' => 'reward_type',
                    'required' => true,
                    'options' => [
                        'id' => 'id',
                        'name' => 'name',
                        'query' => [
                            [
                                'id' => GamificationReward::REWARD_TYPE_POINTS,
                                'name' => $this->trans('Points'),
                            ],
                            [
                                'id' => GamificationReward::REWARD_TYPE_DISCOUNT,
                                'name' => $this->trans('Discount'),
                            ],
                            [
                                'id' => GamificationReward::REWARD_TYPE_FREE_SHIPPING,
                                'name' => $this->trans('Free shipping'),
                            ],
                        ],
                    ],
                ],
                [
                    'label' => $this->trans('Points'),
                    'name' => 'points',
                    'type' => 'text',
                    'hint' => $this->trans('Number of points that customer will get'),
                    'class' => 'fixed-width-sm',
                    'suffix' => $this->trans('points'),

                ],
                [
                    'label' => $this->trans('Discount type'),
                    'type' => 'select',
                    'name' => 'discount_reduction_type',
                    'options' => [
                        'id' => 'id',
                        'name' => 'name',
                        'query' => [
                            [
                                'id' => GamificationReward::DISCOUNT_REDUCTION_PERCENT,
                                'name' => $this->trans('Percent'),
                            ],
                            [
                                'id' => GamificationReward::DISCOUNT_REDUCTION_AMOUNT,
                                'name' => $this->trans('Amount'),
                            ],
                        ],
                    ],
                ],
                [
                    'label' => $this->trans('Discount value'),
                    'type' => 'text',
                    'name' => 'discount_value',
                    'hint' => $this->trans('Percent (%) or amount (%currency%)', ['%currency%' => $defaultCurrency->sign]),
                    'class' => 'fixed-width-sm',
                ],
                [
                    'label' => $this->trans('Discount apply type'),
                    'type' => 'select',
                    'name' => 'discount_apply_type',
                    'hint' =>
                        $this->trans('Whether to apply discount automatically to cart or give customer discount code'),
                    'options' => [
                        'id' => 'id',
                        'name' => 'name',
                        'query' => [
                            [
                                'id' => GamificationReward::DISCOUNT_TYPE_AUTOMATICALLY_APPLIED,
                                'name' => $this->trans('Automatically'),
                            ],
                            [
                                'id' => GamificationReward::DISCOUNT_REDUCTION_AMOUNT,
                                'name' => $this->trans('Provide code'),
                            ],
                        ],
                    ],
                ],
                [
                    'label' => $this->trans('Discount valid days'),
                    'type' => 'text',
                    'name' => 'discount_valid_days',
                    'hitn' => $this->trans('How many days discount will be valid after earning it'),
                    'suffix' => $this->trans('days'),
                    'class' => 'fixed-width-sm',
                ],
                [
                    'label' => $this->trans('Minimum cart amount'),
                    'type' => 'text',
                    'name' => 'minimum_cart_amount',
                    'hint' => $this->trans('Minimum cart amount that discount or free shipping apply'),
                    'class' => 'fixed-width-sm',
                    'suffix' =>  $defaultCurrency->sign,
                ],
            ],
            'submit' => [
                'title' => $this->trans('Save'),
            ],
        ];

        if (Shop::isFeatureActive()) {
            $this->fields_form['input'][] = [
                'type' => 'shop',
                'label' => $this->trans('Shop association'),
                'name' => 'checkBoxShopAsso',
            ];
        }
    }
}
