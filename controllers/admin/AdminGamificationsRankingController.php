<?php
/**
 * This file is part of the Gamifications module.
 *
 * @author    Sarunas Jonusas, <jonusas.sarunas@gmail.com>
 * @copyright Copyright (c) permanent, Sarunas Jonusas
 * @license   Addons PrestaShop license limitation
 */

/**
 * Class AdminGamificationsRankingController
 */
class AdminGamificationsRankingController extends GamificationsAdminController
{
    /**
     * @var GamificationsRank
     */
    protected $object;

    /**
     * AdminGamificationsRankingController constructor.
     */
    public function __construct()
    {
        $this->table = GamificationsRank::$definition['table'];
        $this->identifier = GamificationsRank::$definition['primary'];
        $this->className = 'GamificationsRank';

        parent::__construct();
    }

    /**
     * Add custom links in page header
     */
    public function initPageHeaderToolbar()
    {
        if (empty($this->display)) {
            $this->page_header_toolbar_btn['new_rank'] = [
                'href' => self::$currentIndex.'&addgamifications_rank&token='.$this->token,
                'desc' => $this->trans('Add new Customer Rank', [], 'Modules.Gamifications.Admin'),
                'icon' => 'process-icon-new',
            ];
        }

        parent::initPageHeaderToolbar();
    }

    /**
     * Render list
     *
     * @return false|string
     */
    public function renderList()
    {
        $this->_select = 'rl.`name` AS `parent_name`';

        $this->_join = '
            LEFT JOIN `'._DB_PREFIX_.'gamifications_rank_lang` rl
                ON rl.`id_gamifications_rank` = a.`id_parent`
                    AND rl.`id_lang` = '.(int)$this->context->language->id.'
        ';

        $this->_where = 'AND a.`id_shop` = '.(int)$this->context->shop->id;

        return parent::renderList();
    }

    public function postProcess()
    {
        $parentReturn = parent::postProcess();

        if (!empty($this->errors) && false === $parentReturn) {
            $this->warnings[] =
                $this->trans('Make sure your each rank has unique parent rank!', [], 'Modules.Gamifications.Admin');
        }

        return $parentReturn;
    }

    /**
     * Init list
     */
    protected function initList()
    {
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->lang = true;
        $defaultCurrency = Currency::getDefaultCurrency();

        $this->fields_list = [
            GamificationsRank::$definition['primary'] => [
                'title' => $this->trans('ID', [], 'Modules.Gamifications.Admin'),
                'type' => 'text',
                'width' => 20,
            ],
            'name' => [
                'title' => $this->trans('Rank name', [], 'Modules.Gamifications.Admin'),
                'type' => 'text',
            ],
            'parent_name' => [
                'title' => $this->trans('Parent rank name', [], 'Modules.Gamifications.Admin'),
                'type' => 'text',
                'filter_key' => 'rl!name',
            ],
            'must_spend_money' => [
                'title' => $this->trans('Must spend money', [], 'Modules.Gamifications.Admin'),
                'type' => 'text',
                'suffix' => $defaultCurrency->getSign(),
            ],
            'must_spend_points' => [
                'title' => $this->trans('Must spend points', [], 'Modules.Gamifications.Admin'),
                'type' => 'text',
            ],
            'date_upd' => [
                'title' => $this->trans('Created at', [], 'Modules.Gamifications.Admin'),
                'align' => 'center',
                'type' => 'date',
                'filter_key' => 'a!date_add',
            ],
            'date_add' => [
                'title' => $this->trans('Updated at', [], 'Modules.Gamifications.Admin'),
                'align' => 'center',
                'type' => 'date',
                'filter_key' => 'a!date_upd',
            ],
        ];
    }

    /**
     * Init customer rank form
     */
    protected function initForm()
    {
        $defaultCurrency = Currency::getDefaultCurrency();

        $this->fields_form = [
            'legend' => [
                'title' => 'Customer rank',
            ],
            'input' => [
                [
                    'name' => 'name',
                    'label' => $this->trans('Rank name', [], 'Modules.Gamifications.Admin'),
                    'hint' => $this->trans(
                        'E.g. Bronze, Silver, Gold or Level 1, Level 2 & etc.',
                        [],
                        'Modules.Gamifications.Admin'
                    ),
                    'type' => 'text',
                    'required' => true,
                    'lang' => true,
                ],
                [
                    'label' => $this->trans(
                        'Must spend money (%money%)',
                        ['%money%' => $defaultCurrency->getSign()],
                        'Modules.Gamifications.Admin'
                    ),
                    'hint' => $this->trans(
                        'How much money customer has to spend to get this rank. Calculated in default currency.',
                        [],
                        'Modules.Gamifications.Admin'
                    ),
                    'name' => 'must_spend_money',
                    'type' => 'text',
                    'class' => 'fixed-width-sm',
                    'suffix' => $defaultCurrency->getSign(),
                    'required' => true,
                ],
                [
                    'label' => $this->trans('Must spend points', [], 'Modules.Gamifications.Admin'),
                    'hint' => $this->trans(
                        'How many points customer has to spend to get this rank.',
                        [],
                        'Modules.Gamifications.Admin'
                    ),
                    'name' => 'must_spend_points',
                    'type' => 'text',
                    'class' => 'fixed-width-sm',
                    'suffix' => $this->trans('points', [], 'Modules.Gamifications.Admin'),
                    'required' => true,
                ],
                [
                    'label' => $this->trans('Group', [], 'Modules.Gamifications.Admin'),
                    'hint' =>
                        $this->trans('Assign unique customer group to each rank.', [], 'Modules.Gamifications.Admin')
                        .' '.
                        $this->trans(
                                'When customer reaches defined criterias he will be assigned to this group.',
                                [],
                                'Modules.Gamifications.Admin'
                            ),
                    'type' => 'select',
                    'name' => 'id_group',
                    'required' => true,
                    'options' => [
                        'id' => 'id_group',
                        'name' => 'name',
                        'query' => $this->getAvailableGroups(),
                    ],
                ],
                [
                    'label' => $this->trans('Parent rank', [], 'Modules.Gamifications.Admin'),
                    'hint' => $this->trans(
                        'Current rank goes after parent rank. E.g. Bronze (Parent rank) -> Silver (Current rank)',
                        [],
                        'Modules.Gamifications.Admin'
                    ),
                    'type' => 'select',
                    'name' => 'id_parent',
                    'options' => [
                        'id' => 'id_gamifications_rank',
                        'name' => 'name',
                        'query' => $this->getAvailableParentRanks(),
                    ],
                    'required' => true,
                ],
                [
                    'type' => 'hidden',
                    'name' => 'id_shop',
                ],
            ],
            'submit' => [
                'title' => $this->trans('Save', [], 'Modules.Gamifications.Admin'),
            ],
        ];

        $this->fields_value['id_shop'] = (int) $this->context->shop->id;
    }

    /**
     * Get available customers groups
     *
     * @return array
     */
    public function getAvailableGroups()
    {
        /** @var GamificationsRankRepository $rankRepository */
        $rankRepository = $this->module->getEntityManager()->getRepository('GamificationsRank');
        $usedGroups = $rankRepository->findAllUsedGroupIds($this->context->shop->id);

        $defaultGroups = ['PS_UNIDENTIFIED_GROUP', 'PS_GUEST_GROUP', 'PS_CUSTOMER_GROUP'];
        $excludeGroups = Configuration::getMultiple($defaultGroups);

        $excludeGroups = array_merge($excludeGroups, $usedGroups);

        if ('edit' == $this->display) {
            GamificationsArrayHelper::removeValue($this->object->id_group, $excludeGroups);
        }

        $groups = Group::getGroups($this->context->language->id, $this->context->shop->id);

        foreach ($groups as $key => $group) {
            if (in_array($group['id_group'], $excludeGroups)) {
                unset($groups[$key]);
            }
        }

        return $groups;
    }

    /**
     * Get available parent ranks
     *
     * @return array
     */
    public function getAvailableParentRanks()
    {
        $excludeIds = [];
        if ('edit' == $this->display) {
            $excludeIds[] = $this->object->id;
        }

        $idShop = $this->context->shop->id;
        $idLang = $this->context->language->id;

        /** @var GamificationsRankRepository $rankRepository */
        $rankRepository = $this->module->getEntityManager()->getRepository('GamificationsRank');
        $ranks = $rankRepository->findAllIdsAndNames($idShop, $idLang, $excludeIds);

        $ranks[] = [
            'name' => $this->trans('none', [], 'Modules.Gamifications.Admin'),
            'id_gamifications_rank' => 0,
        ];

        return $ranks;
    }
}
