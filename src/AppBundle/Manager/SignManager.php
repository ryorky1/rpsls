<?php

namespace AppBundle\Manager;

use AppBundle\Entity\PlayerHistory;
use AppBundle\Model\Game;
use AppBundle\Model\RoundPlay;
use AppBundle\Model\Sign;
use Doctrine\ORM\EntityManager;

/**
 * Class SignManager
 * in order to manage the different signs
 *
 * @package AppBundle\Manager
 */
class SignManager
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    private function getSignsCollection()
    {
        //create
        $rockSign = new Sign(Sign::ROCK);
        //paper
        $paperSign = new Sign(Sign::PAPER);
        //scissors
        $scissorsSign = new Sign(Sign::SCISSORS);
        //spock
        $spockSign = new Sign(Sign::SPOCK);
        //lizard
        $lizardSign = new Sign(Sign::LIZARD);

        return [$rockSign, $scissorsSign, $paperSign, $spockSign, $lizardSign];
    }

    /**
     * Get random Sign depending on the game mode
     * If mode easy simply get a random sign
     * else if mode is expert must search into the database to analyse the sequences in order to give a possible sign
     *
     * @param int $mode
     * @param null $username
     *
     * @return mixed
     */
    public function getRandomSign($username = null)
    {
        $signVictim = false;
        if ($signVictim) {
            return $this->getSignKiller($signVictim);
        }

        $signCollection = $this->getSignsCollection();

        return $signCollection[array_rand($signCollection)];

    }
    /**
     * get killer sign object
     * 
     * @param string $signCode
     * 
     * @return Sign
     */
    public function getSignKiller($signCode)
    {
        $sign = new Sign($signCode);
        return new Sign($sign->getKiller());
        
    }

    public function getPlayerHistory($username)
    {
       return $this->em->getRepository('AppBundle:PlayerHistory')->findPlayerHistoryList($username);
    }

    public function savePlayerRound(RoundPlay $roundPlay)
    {
        $playerHistory = new PlayerHistory();
        $playerHistory->setUsername($roundPlay->getPlayer()->getUsername());
        $playerHistory->setSign($roundPlay->getSign()->getCode());
        $this->em->persist($playerHistory);
        $this->em->flush();
    }

    private function findSequence($needle, $haystack)
    {
        $keys = array_keys($haystack, $needle[0]);
        $sequences = array();
        foreach ($keys as $key) {
            $add = true;
            $result = array();
            foreach ($needle as $i => $value) {
                if (!(isset($haystack[$key + $i]) && $haystack[$key + $i] == $value)) {
                    $add = false;
                    break;
                }
                $result[] = $key + $i;
            }
            if ($add == true) {
                $sequences[] = $result;
            }
        }

        return $sequences;
    }

    private function getNextCandidate($sequence, $dataArray)
    {
        $lastElement = end($sequence);
        if (isset($dataArray[$lastElement + 1])) {
            return $dataArray[$lastElement + 1];
        }

        return false;
    }

    /**
     * find victim candidate !
     * Get the last 3 rounds of the users. search the sequence i his history
     * then from the possible candidates for the next sign , get a random!
     * 
     * @param array $dataPlayer
     * @param int $precision
     * 
     * @return string
     */
    public function findVictimCandidate(array $dataPlayer, $precision = 3)
    {
        if (count($dataPlayer) == 0){
            return false;
        }
        //set sequence to search
        $needleSequence = array_slice($dataPlayer, -$precision);
        //init candidates array
        $candidates = [];
        $sequenceCollection = $this->findSequence($needleSequence, $dataPlayer);
        foreach($sequenceCollection as $sequence) {
            $candidate = $this->getNextCandidate($sequence, $dataPlayer);
            if (false !== $candidate) {
                $candidates[] = $candidate;
            }
        }
        $candidates = array_unique($candidates);
        if (count($candidates) == 0 ) {
            return false;
        }

        return $candidates[array_rand($candidates)];
    }
} 