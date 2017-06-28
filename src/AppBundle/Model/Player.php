<?php

namespace AppBundle\Model;

/**
 * Class Player
 * A player has a username and a mode
 *
 * @package AppBundle\Model
 */
class Player
{
    const MODE_AI    = 0;
    const MODE_HUMAN = 1;
    /**
     * @var string username
     */
    protected $username;

    /**
     * mode of the player human or AI
     * @var $mode
     */
    protected $mode;

    /**
     * constructor
     *
     * @param string $username
     * @param string $mode      default human
     */
    public function __construct($username, $mode = self::MODE_HUMAN)
    {
        $this->username = $username;
        $this->mode = $mode;
    }

    /**
     * get player modes array
     *
     * @return array
     */
    public function getPlayerModes()
    {
        return [self::MODE_AI, self::MODE_HUMAN];
    }

    /**
     * set Mode
     *
     * @param int $mode
     *
     * @return Player
     *
     * @throws \Exception
     */
    public function setMode($mode)
    {
        if (!in_array($mode, array(self::MODE_AI, self::MODE_HUMAN))) {
            throw new \Exception('UNKNOWN PLAYER MODE');
        }
        $this->setMode($mode);

        return $this;
    }

    /**
     * get Mode
     *
     * @return int
     */
    public function getMode()
    {
        return $this->mode;
    }
    /*
     * set username
     *
     * @param string $username
     *
     * @return Player
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * getUsername
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

} 