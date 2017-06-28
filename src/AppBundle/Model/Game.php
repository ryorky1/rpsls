<?php

namespace AppBundle\Model;

use AppBundle\Model\Player;

/**
 * Class Game
 * This class describes a Rock-paper-scissors game set
 * @package AppBundle\Model
 */
class Game
{
    const GAME_TIME = 3600;
    protected $rounds;
    protected $playerHuman;
    protected $playerAi;
    protected $timestamp;
    protected $score;
    protected $identifier;

    /**
     * constructor of the Game
     *
     * @param Player $playerOne player object
     * @param Player $playerTwo player object
     * @param string $identifier game identifier
     * @param boolean $start    start the game timer
     */
    public function __construct(
        Player $playerOne,
        Player $playerTwo,
        $identifier = null,
        $start = true
    )
    {
        $this->playerHuman = $playerOne;
        $this->playerAi = $playerTwo;
        $this->initScore();
        $this->rounds = array();
        if (true === $start) {
            $this->setTimestamp();
        }
        $this->identifier = $identifier;
    }

    public function initScore()
    {
        $this->score = array(
            $this->playerHuman->getUsername() => 0,
            $this->playerAi->getUsername() => 0,
        );

        return $this;
    }

    /**
     * set game identifier
     *
     * @param $identifier
     *
     * @return $this
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * get identifier
     *
     * @return string|null
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function getScore()
    {
        return $this->score;
    }

    public function getPlayerScoreByUsername($username)
    {
        if (!isset($this->score[$username])) {
            $this->score[$username] = 0;
        }

        return $this->score[$username];
    }
    public function getPlayerScore(Player $player)
    {
        if (!isset($this->score[$player->getUsername()])) {
            $this->score[$player->getUsername()] = 0;
        }

        return $this->score[$player->getUsername()];
    }

    public function addScore(Player $player)
    {
        $this->score[$player->getUsername()] = $this->getPlayerScore($player) + 1;
    }

    public function getPlayerHuman()
    {
        return $this->playerHuman;
    }

    public function getPlayerAi()
    {
        return $this->playerAi;
    }

    public function setTimestamp(\DateTime $timestamp = null)
    {
        $this->timestamp = time();

        return $this;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * isGameFinished
     * check if the game is finished
     * @return bool
     */
    public function isGameFinished()
    {
        $now = new \DateTime();
        if ($now->getTimestamp() - $this->timestamp >= self::GAME_TIME) {
            return true;
        }

        return false;

    }


} 