<?php
/**
 * Created by PhpStorm.
 * User: johnnyressen
 * Date: 10/03/17
 * Time: 10:52
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class HoursRepository extends EntityRepository
{
    public function showOpeningHours() {

        $dql =

        $entityManager = $this->getDoctrine()->getManager();
        $openingHoursRepository = $entityManager->getRepository('openingHours');
        $openingHours = $openingHoursRepository->findAll();

        foreach ($openingHours as $openingHour) {
            echo sprintf("%s\n", $openingHour->getOpeningHours());
        }
    }

}