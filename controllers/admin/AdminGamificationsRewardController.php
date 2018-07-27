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
    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);

        if (in_array($this->display, ['add', 'edit', 'list'])) {
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
                'desc' => $this->l('Add new reward'),
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
                'title' => $this->l('ID'),
                'width' => 20,
                'type' => 'text',
            ],
            'name' => [
                'title' => $this->l('Reward name'),
                'type' => 'text',
            ],
            'reward_type' => [
                'title' => $this->l('Reward type'),
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
                'title' => $this->l('Reward'),
            ],
            'input' => [
                [
                    'label' => $this->l('Reward name'),
                    'type' => 'text',
                    'name' => 'name',
                    'required' => true,
                    'lang' => true,
                ],
                [
                    'label' => $this->l('Description'),
                    'type' => 'textarea',
                    'name' => 'description',
                    'lang' => true,
                    'desc' => $this->l('Up to 255 characters'),
                ],
                [
                    'label' => $this->l('Reward type'),
                    'type' => 'select',
                    'name' => 'reward_type',
                    'required' => true,
                    'options' => [
                        'id' => 'id',
                        'name' => 'name',
                        'query' => [
                            [
                                'id' => GamificationsReward::REWARD_TYPE_POINTS,
                                'name' => $this->l('Fixed amount of points'),
                            ],
                            [
                                'id' => GamificationsReward::REWARD_TYPE_RANDOM_AMOUNT_OF_POINTS,
                                'name' => $this->l('Random amount of points'),
                            ],
                            [
                                'id' => GamificationsReward::REWARD_TYPE_DISCOUNT,
                                'name' => $this->l('Discount'),
                            ],
                            [
                                'id' => GamificationsReward::REWARD_TYPE_FREE_SHIPPING,
                                'name' => $this->l('Free shipping'),
                            ],
                            [
                                'id' => GamificationsReward::REWARD_TYPE_GIFT,
                                'name' => $this->l('Gift'),
                            ],
                        ],
                    ],
                ],
                [
                    'label' => $this->l('Points'),
                    'name' => 'points',
                    'type' => 'text',
                    'hint' => $this->l('Number of points that customer will get'),
                    'class' => 'fixed-width-sm',
                    'suffix' => $this->l('points'),

                ],
                [
                    'label' => $this->l('Radius'),
                    'name' => 'radius',
                    'type' => 'text',
                    'hint' =>
                        $this->l('E.g. if entered 100 points and radius 30,').' '.
                        $this->l('then range is 70 - 130 points (+-30 points)'),
                    'class' => 'fixed-width-sm',
                    'suffix' => $this->l('points radius'),
                ],
                [
                    // THIS FIELD IS NOT SAVED
                    'label' => $this->l('Product'),
                    'name' => 'product_name',
                    'type' => 'text',
                    'hint' => $this->l('Enter product name or full reference and available products will show up'),
                    'class' => 'fixed-width-xxl',
                ],
                [
                    'label' => '',
                    'name' => 'id_product',
                    'type' => 'hidden',
                ],
                [
                    'label' => $this->l('Discount type'),
                    'type' => 'select',
                    'name' => 'discount_reduction_type',
                    'options' => [
                        'id' => 'id',
                        'name' => 'name',
                        'query' => [
                            [
                                'id' => GamificationsReward::DISCOUNT_REDUCTION_PERCENT,
                                'name' => $this->l('Percent (%)'),
                            ],
                            [
                                'id' => GamificationsReward::DISCOUNT_REDUCTION_AMOUNT,
                                'name' => sprintf($this->l('Amount (%s)'), $defaultCurrency->iso_code),
                            ],
                        ],
                    ],
                ],
                [
                    'label' => $this->l('Discount value'),
                    'type' => 'text',
                    'name' => 'discount_value',
                    'hint' => $this->l('Percent (%) or amount'),
                    'class' => 'fixed-width-sm',
                ],
                [
                    'label' => $this->l('Valid days'),
                    'type' => 'text',
                    'name' => 'discount_valid_days',
                    'hitn' => $this->l('How many days discount will be valid after earning it'),
                    'suffix' => $this->l('days'),
                    'class' => 'fixed-width-sm',
                ],
                [
                    'label' => $this->l('Minimum cart amount'),
                    'type' => 'text',
                    'name' => 'minimum_cart_amount',
                    'hint' => $this->l('Minimum cart amount that discount or free shipping apply'),
                    'class' => 'fixed-width-sm',
                    'suffix' =>  $defaultCurrency->sign,
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
            ],
        ];

        if (Shop::isFeatureActive()) {
            $this->fields_form['input'][] = [
                'type' => 'shop',
                'label' => $this->l('Shop association'),
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
            if ($this->object->id_product) {
                $product = new Product(
                    $this->object->id_product,
                    false,
                    $this->context->language->id,
                    $this->context->shop->id
                );

                if (Validate::isLoadedObject($product)) {
                    $this->object->product_name = sprintf('%s (ref: %s)', $product->name, $product->reference);
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
