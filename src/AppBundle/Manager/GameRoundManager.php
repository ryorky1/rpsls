<?php

namespace AppBundle\Manager;

use AppBundle\Model\Game;
use AppBundle\Model\Player;
use AppBundle\Model\RoundPlay;
use AppBundle\Model\Sign;
use AppBundle\Manager\SignManager;
use AppBundle\Manager\GameManager;
use AppBundle\Model\RoundResult;


class GameRoundManager
{
    protected $gameManager;
    protected $signManager;

    public function __construct(GameManager $gameManager, SignManager $signManager)
    {
        $this->signManager = $signManager;
        $this->gameManager = $gameManager;
    }

    public function playRound($gameIdentifier, Sign $humanPlayerSign = null)
    {
        $game = $this->gameManager->getGameSession($gameIdentifier);
        if (!$game instanceof Game) {
            return false;
        }
        //set the round play for the human player
        $humanRound = new RoundPlay($game->getPlayerHuman(), $humanPlayerSign);
        //get AI Sign based on the mode (expert or easy)
        $aiPlayerSign = $this->signManager->getRandomSign($game->getPlayerHuman()->getUsername());
        //set the round play for the AI player
        $aiRound = new RoundPlay($game->getPlayerAi(), $aiPlayerSign);
        $winner = $this->getWinner($humanRound, $aiRound);
        if ($winner instanceof Player) {
            //set game score
            $game->addScore($winner);
        }
        //save player history
        $this->signManager->savePlayerRound($humanRound);
        //update the game session
        $this->gameManager->setGameSession($game);
        //return the round data view
        $roundResult = new RoundResult([$humanRound, $aiRound], $winner);

        return $roundResult;
    }

    /**
     * get winner of the round
     *
     * @param RoundPlay $playerOneRound
     * @param RoundPlay $playerTwoRound
     *
     * @return null|Player
     */
    public function getWinner(RoundPlay $playerOneRound, RoundPlay $playerTwoRound)
    {
        if (in_array($playerTwoRound->getSign()->getCode(), $playerOneRound->getSign()->getVictim())) {
            return $playerOneRound->getPlayer();
        } elseif (in_array($playerOneRound->getSign()->getCode(), $playerTwoRound->getSign()->getVictim()))
        {
            return $playerTwoRound->getPlayer();
        }
        //equality
        return null;
    }
}