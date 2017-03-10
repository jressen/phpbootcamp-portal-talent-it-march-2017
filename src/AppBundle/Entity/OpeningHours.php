<?php
/**
 * Created by PhpStorm.
 * User: johnnyressen
 * Date: 10/03/17
 * Time: 10:09
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


class OpeningHours{


    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     * * @Assert\NotBlank(message="post.blank_content")
     */
    private $dayOfWeek;


    /**
     * @var time
     *
     * @ORM\Column(type="Time")
     */
    private $openingTime;


    /**
     * @var time
     *
     * @ORM\Column(type="Time")
     */
    private $closingTime;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getDayOfWeek(): string
    {
        return $this->dayOfWeek;
    }

    /**
     * @param string $dayOfWeek
     */
    public function setDayOfWeek(string $dayOfWeek)
    {
        $this->dayOfWeek = $dayOfWeek;
    }

    /**
     * @return mixed
     */
    public function getOpeningTime()
    {
        return $this->openingTime;
    }

    /**
     * @param mixed $openingTime
     */
    public function setOpeningTime($openingTime)
    {
        $this->openingTime = $openingTime;
    }

    /**
     * @return time
     */
    public function getClosingTime(): time
    {
        return $this->closingTime;
    }

    /**
     * @param time $closingTime
     */
    public function setClosingTime(time $closingTime)
    {
        $this->closingTime = $closingTime;
    }



}