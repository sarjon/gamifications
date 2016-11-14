<?php

/**
 * Class AdminGamificationsRewardController
 */
class AdminGamificationsRewardController extends GamificationsAdminController
{
    /**
     * @var GamificationsReward
     */
    protected $object;

    /**
     * AdminGamificationsRewardController constructor.
     */
    public function __construct()
    {
        $this->className = 'GamificationsReward';
        $this->table = GamificationsReward::$definition['table'];
        $this->identifier = GamificationsReward::$definition['primary'];
        Shop::addTableAssociation(GamificationsReward::$definition['table'], ['type' => 'shop']);

        parent::__construct();
    }

    /**
     * Process ajax, add & save reward
     *
     * @return bool
     */
    public function postProcess()
    {
        if ($this->isXmlHttpRequest()) {

            $query = Tools::getValue('q');
            $limit = (int) Tools::getValue('limit');

            /** @var GamificationsProductRepository $productRepository */
            $productRepository = $this->module->getEntityManager()->getRepository('GamificationsProduct');
            $products = $productRepository->findAllProductsNamesAndIdsByQuery(
                $query,
                $limit,
                $this->context->language->id,
                $this->context->shop->id
            );

            die(json_encode(['products' => $products]));
        }

        return parent::postProcess();
    }

    /**
     * Add custom js & css to controller
     */
    public function setMedia()
    {
        parent::setMedia();

        if (in_array($this->display, ['add', 'edit'])) {
            $this->addJqueryPlugin('autocomplete');
            $this->addJS($this->module->getPathUri().'views/js/admin/reward.js');

            Media::addJsDef([
                '$gamificationsRewardControllerUrl' =>
                    $this->context->link->getAdminLink(Gamifications::ADMIN_GAMIFICATIONS_REWARD_CONTROLLER),
            ]);
        }
    }

    /**
     * Add custom links in page header
     */
    public function initPageHeaderToolbar()
    {
        if (empty($this->display)) {
            $this->page_header_toolbar_btn['new_reward'] = [
                'href' => self::$currentIndex.'&addgamifications_reward&token='.$this->token,
                'desc' => $this->trans('Add new reward'),
                'icon' => 'process-icon-new',
            ];
        }

        parent::initPageHeaderToolbar();
    }

    /**
     * Customize list
     *
     * @param int $idLang
     * @param null $orderBy
     * @param null $orderWay
     * @param int $start
     * @param null $limit
     * @param bool $idLangShop
     */
    public function getList($idLang, $orderBy = null, $orderWay = null, $start = 0, $limit = null, $idLangShop = false)
    {
        parent::getList($idLang, $orderBy, $orderWay, $start, $limit, $idLangShop);

        if (empty($this->_list)) {
            return;
        }

        $rewardTypeTranslations = GamificationsReward::getRewardsTranslations();

        foreach ($this->_list as &$listItem) {
            $listItem['reward_type'] = $rewardTypeTranslations[$listItem['reward_type']];
        }
    }

    /**
     * Initialize list with rewards
     */
    protected function initList()
    {
        $this->lang = true;
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $this->fields_list = [
            GamificationsReward::$definition['primary'] => [
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
                'type' => 'select',
                'list' => GamificationsReward::getRewardsTranslations(),
                'filter_key' => 'a!reward_type',
            ],
        ];
    }

    /**
     * Initialize form
     */
    protected function initForm()
    {
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
                                'id' => GamificationsReward::REWARD_TYPE_POINTS,
                                'name' => $this->trans('Fixed amount of points'),
                            ],
                            [
                                'id' => GamificationsReward::REWARD_TYPE_RANDOM_AMOUNT_OF_POINTS,
                                'name' => $this->trans('Random amount of points'),
                            ],
                            [
                                'id' => GamificationsReward::REWARD_TYPE_DISCOUNT,
                                'name' => $this->trans('Discount'),
                            ],
                            [
                                'id' => GamificationsReward::REWARD_TYPE_FREE_SHIPPING,
                                'name' => $this->trans('Free shipping'),
                            ],
                            [
                                'id' => GamificationsReward::REWARD_TYPE_PRIZE,
                                'name' => $this->trans('Prize'),
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
                    'label' => $this->trans('Radius'),
                    'name' => 'radius',
                    'type' => 'text',
                    'hint' =>
                        $this->trans('E.g. if entered 100 points and radius 30,').' '.
                        $this->trans('then range is 70 - 130 points (+-30 points)'),
                    'class' => 'fixed-width-sm',
                    'suffix' => $this->trans('points radius'),
                ],
                [
                    // THIS FIELD IS NOT SAVED
                    'label' => $this->trans('Product as a prize'),
                    'name' => 'prize_name',
                    'type' => 'text',
                    'hint' => $this->trans('Enter product name and available products will show up'),
                    'class' => 'fixed-width-xxl',

                ],
                [
                    'label' => '',
                    'name' => 'prize',
                    'type' => 'hidden',
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
                                'id' => GamificationsReward::DISCOUNT_REDUCTION_PERCENT,
                                'name' => $this->trans('Percent (%)'),
                            ],
                            [
                                'id' => GamificationsReward::DISCOUNT_REDUCTION_AMOUNT,
                                'name' => $this->trans('Amount (%currency%)', ['%currency%' => $defaultCurrency->sign]),
                            ],
                        ],
                    ],
                ],
                [
                    'label' => $this->trans('Discount value'),
                    'type' => 'text',
                    'name' => 'discount_value',
                    'hint' =>
                        $this->trans('Percent (%) or amount (%currency%)', ['%currency%' => $defaultCurrency->sign]),
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
                                'id' => GamificationsReward::DISCOUNT_TYPE_AUTOMATICALLY_APPLIED,
                                'name' => $this->trans('Automatically'),
                            ],
                            [
                                'id' => GamificationsReward::DISCOUNT_REDUCTION_AMOUNT,
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

    /**
     * Add custom fields to object if it's loaded
     *
     * @param bool $opt
     *
     * @return false|GamificationsReward
     */
    protected function loadObject($opt = false)
    {
        $parentResponse = parent::loadObject($opt);

        if (Validate::isLoadedObject($this->object)) {

            if ($this->object->prize) {
                $product = new Product(
                    $this->object->prize,
                    false,
                    $this->context->language->id,
                    $this->context->shop->id
                );

                if (Validate::isLoadedObject($product)) {
                    $this->object->prize_name = $product->name;
                }
            }

        }

        return $parentResponse;
    }

    /**
     * Display help message if setting is enabled
     */
    protected function displayHelp()
    {
        return $this->context->smarty->fetch($this->module->getLocalPath().'views/templates/admin/rewards_info.tpl');
    }
}
