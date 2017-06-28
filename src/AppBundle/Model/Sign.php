<?php
/**
 * Created by PhpStorm.
 * User: ryorky1
 * Date: 06/27/2017
 * Time: 01:08
 */

namespace AppBundle\Model;


class Sign
{
    const ROCK = 'ROCK';
    const PAPER = 'PAPER';
    const SCISSORS = 'SCISSORS';
    const SPOCK = 'SPOCK';
    const LIZARD = 'LIZARD';
    protected $code;
    protected $victim;
    protected $killer;

    /**
     * construct
     * @param string $code
     */
    public function __construct($code)
    {
        $this->code = $code;
        $this->victim = $this->defineVictimCode();
        $this->killer = $this->defineKillerCode();
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    public function setVictim($victim)
    {
        $this->victim = $victim;
    }

    public function getVictim()
    {
        return $this->victim;
    }

    public function getKiller()
    {
        return $this->killer;
    }

    public function defineVictimCode()
    {
        switch ($this->getCode()) {
            case self::SPOCK:
                return [self::SCISSORS, self::ROCK];
            case self::LIZARD:
                return [self::SPOCK, self::PAPER];
            case self::SCISSORS:
                   return [self::PAPER, self::LIZARD];
            case self::PAPER:
                    return [self::ROCK, self::SPOCK];
            case self::ROCK:
                    return [self::SCISSORS, self::LIZARD];
        }
    }

    public function defineKillerCode()
    {
        switch ($this->getCode()) {
            case self::SPOCK:
                return [self::LIZARD, self::PAPER];
            case self::LIZARD:
                return [self::ROCK, self::SCISSORS];
            case self::SCISSORS:
                    return [self::SPOCK, self::ROCK];
            case self::PAPER:
                    return [self::SCISSORS, self::LIZARD];
            case self::ROCK:
                    return [self::PAPER, self::SPOCK];
        }
    }

}