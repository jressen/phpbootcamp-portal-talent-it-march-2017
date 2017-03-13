<?php
/**
 * Created by PhpStorm.
 * User: johnnyressen
 * Date: 10/03/17
 * Time: 10:52
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\OpeningHours;
use Doctrine\ORM\Query;

class HoursRepository extends EntityRepository
{
    /**
     * @return Query
     */

    public function getAll(){

        return $this->getEntityManager()
        ->createQuery('
        SELECT h
        FROM AppBundle:OpeningHours h');

    }

}