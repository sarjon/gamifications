<?php

/**
 * Class GamificationsPlayerManager
 */
class GamificationsPlayerManager
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @var \PrestaShop\PrestaShop\Core\Foundation\Database\EntityManager
     */
    private $em;

    /**
     * @var GamificationsPlayer
     */
    private $player;

    /**
     * @var GamificationsPlayerRepository
     */
    private $playerRepository;

    /**
     * GamificationsPlayerManager constructor.
     *
     * @param Gamifications $module
     *
     * @throws Exception
     */
    public function __construct(Gamifications $module)
    {
        $this->context = $module->getContext();
        $this->em = $module->getEntityManager();
    }

    /**
     * Get player
     *
     * @return GamificationsPlayer
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * Load player object
     *
     * @return bool TRUE if object was loaded successfully or FALSE otherwise
     */
    public function loadPlayerObject()
    {
        /** @var GamificationsPlayerRepository $playerRepository */
        $playerRepository = $this->em->getRepository('GamificationsPlayer');
        $idPlayer = $playerRepository->findIdByCustomerId($this->context->customer->id, $this->context->shop->id);

        $player = new GamificationsPlayer((int) $idPlayer, null, $this->context->shop->id);

        if (null === $idPlayer && !Validate::isLoadedObject($player)) {
            $player->id_customer = (int) $this->context->customer->id;
            $player->total_points = 0;
            $player->spent_points = 0;

            if (!$player->save()) {
                return false;
            }
        }

        $this->player = $player;
        $this->playerRepository = $playerRepository;

        return true;
    }

    /**
     * Check if Daily Reward is available for customer (player)
     *
     * @param null|DateTime $nextDailyRewardAvailabe
     *
     * @return bool
     */
    public function isDailyRewardAvailable(&$nextDailyRewardAvailabe = null)
    {
        $nextDailyRewardAvailabe = null;

        $mostRecentDailyRewardActivity = $this->playerRepository->findMostRecentActivity(
            $this->player->id,
            GamificationsActivity::TYPE_DAILY_REWARD,
            $this->context->shop->id
        );

        $isDailyRewardAvailable = false;
        if (is_array($mostRecentDailyRewardActivity)) {

            $now = new DateTime();
            $lastPlayed = new DateTime($mostRecentDailyRewardActivity['date_add']);
            $nextDailyRewardAvailabe = $lastPlayed->modify('+1 day');

            if (1 <= $now->diff($lastPlayed)->d) {
                $isDailyRewardAvailable = true;
            }

        } elseif (null === $mostRecentDailyRewardActivity) {
            $isDailyRewardAvailable = true;
        }

        return $isDailyRewardAvailable;
    }
}
