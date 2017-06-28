<?php

namespace AppBundle\Manager;

use AppBundle\Model\Game;
use AppBundle\Model\Player;
use Symfony\Component\HttpFoundation\Session\Session;


class GameManager
{
    protected $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Start a game
     *
     * @param string $username
     * @param int    $mode
     * @param bool   $autoMode
     *
     * @return string
     * @throws \Exception
     */
    public function startHumanGame($username)
    {
        try {
            $playerOne = new Player($username);
            $playerTwo = new Player($this->getRandomAIName(), Player::MODE_AI);
            $gameIdentifier = 'game_' . time();
            $game = new Game($playerOne, $playerTwo, $gameIdentifier);
            $this->setGameSession($game);

            return $gameIdentifier;
        } catch (\Exception $ex) {
            throw new \Exception('Could not start the game!');
        }
    }

    /**
     * get a random name for the AI player
     *
     * @return string
     */
    private function getRandomAIName()
    {
        $starWarsNames = [
            'Optimus Prime',
            'Groot',
            'Captain Kirk',
            'Neo',
            'Ferris Bueller',
            'Gandalf',
            'Jack Sparrow',
            'Loki',
        ];

        return $starWarsNames[array_rand($starWarsNames)];
    }

    public function setGameSession(Game $game)
    {
        //add game to session
        $this->session->set($game->getIdentifier(), $game);
        $this->session->save();
    }

    public function hasGameSession($gameIdentifier)
    {
        return $this->session->has($gameIdentifier);
    }

    public function getGameSession($gameIdentifier)
    {
        if ($this->hasGameSession($gameIdentifier)) {
            return $this->session->get($gameIdentifier);
        }

        return false;
    }

    public function destroyGameSession($gameIdentifier)
    {
        try {
            $this->session->set($gameIdentifier, null);
            $this->session->remove($gameIdentifier);
            $this->session->save();
        } catch (\Exception $ex) {
            throw $ex;
        }
        return true;
    }
}