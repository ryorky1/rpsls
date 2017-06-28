<?php

namespace AppBundle\Model;

use AppBundle\Model\Player;
use AppBundle\Model\Sign;

/**
 * Class Game Round Play Model
 * THis class describes a game round for a player
 *
 * @package AppBundle\Model
 */
class RoundPlay
{
    protected $player;
    protected $sign;

    public function __construct(Player $player, Sign $sign)
    {
        $this->player = $player;
        $this->sign = $sign;
    }

    public function setSign(Sign $sign)
    {
        $this->sign = $sign;
    }

    public function getSign()
    {
        return $this->sign;
    }

    public function getPlayer()
    {
        return $this->player;
    }

    public function setPlayer(Player $player)
    {
        $this->player = $player;
    }
}