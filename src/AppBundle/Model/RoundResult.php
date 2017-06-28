<?php

namespace AppBundle\Model;

use AppBundle\Model\Player;

/**
 * Class Game Round Result Model
 * THis class describes a game round results
 * with results
 *
 * @package AppBundle\Model
 */
class RoundResult
{
    protected $winner;
    //the round data is an array of RoundPlay objects
    protected $roundData = array();

    public function __construct(array $roundData, Player $winner = null)
    {
        //set the round data
        $this->roundData = $roundData;
        $this->winner = $winner;
    }

    /**
     * search the round data and get the sign code of a specific player
     * this method searches the round data
     *
     * @param $username
     *
     * @return null|string
     */
    public function getSignByUsername($username)
    {
        foreach ($this->roundData as $round) {
            if ($round instanceof RoundPlay && $round->getPlayer()->getUsername() == $username) {
                return $round->getSign()->getCode();
            }
        }
        return null;
    }
    /**
     * getRoundData
     * 
     * @return array
     */
    public function getRoundData()
    {
        return $this->roundData;
    }

    /**
     * set winner
     *
     * @param Player $player
     *
     * @return $this
     */
    public function setWinner(Player $player)
    {
        $this->winner = $player;

        return $this;
    }

    /**
     * get winner
     *
     * @return Player
     */
    public function getWinner()
    {
        return $this->winner;
    }

    /**
     * check the winner by username
     *
     * @param $username
     *
     * @return bool
     */
    public function isWinner($username)
    {
        if ($this->winner->getUsername() == $username) {
            return true;
        }
        return false;
    }
}