<?php

/**
 * Class GamificationsPlayer
 */
class GamificationsPlayer extends ObjectModel
{
    /**
     * @var int
     */
    public $id_customer;

    /**
     * @var int
     */
    public $id_rank;

    /**
     * @var int
     */
    public $total_points;

    /**
     * @var int
     */
    public $spent_points;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $date_add;

    /**
     * @var string
     */
    public $date_upd;

    /**
     * @var array
     */
    public static $definition = [
        'table' => 'gamifications_player',
        'primary' => 'id_gamifications_player',
        'fields' => [

        ],
    ];
}