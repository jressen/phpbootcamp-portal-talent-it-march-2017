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
/**
 * Class OpeningHours
 * @package AppBundle\Entity
 *
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HoursRepository")
 * @ORM\Table(name="openinghours")
 *
 *
 *
 */
class OpeningHours{




    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank
     */
    private $dayOfWeek;


    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $openingTime;


    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $closingTime;



    /**
     * OpeningHours constructor.
     * @param string $dayOfWeek
     */
    public function __construct($dayOfWeek)
    {
        $this->dayOfWeek = $dayOfWeek;

    }

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
     * @return string
     */
    public function getOpeningTime(): string
    {
        return $this->openingTime;
    }

    /**
     * @param string $openingTime
     */
    public function setOpeningTime(string $openingTime)
    {
        $this->openingTime = $openingTime;
    }

    /**
     * @return string
     */
    public function getClosingTime(): string
    {
        return $this->closingTime;
    }

    /**
     * @param string $closingTime
     */
    public function setClosingTime(string $closingTime)
    {
        $this->closingTime = $closingTime;
    }


   }