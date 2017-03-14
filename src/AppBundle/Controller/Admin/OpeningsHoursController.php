<?php
/**
 * Created by PhpStorm.
 * User: johnnyressen
 * Date: 10/03/17
 * Time: 11:04
 */

namespace AppBundle\Controller\Admin;

use AppBundle\AppBundle;
use AppBundle\Entity\OpeningHours;
use AppBundle\Form\OpeningsHoursType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Repository\HoursRepository;

/**
*
*@Security("has_role('ROLE_ADMIN')")
*/


class OpeningsHoursController extends Controller
{

    public static $weekdays = ['label.monday' => 'Mon',
                        'label.tuesday' => 'Tue',
                        'label.wednesday' => 'Wed',
                        'label.thursday' => 'Thu',
                        'label.friday' => 'fri',
                        'label.saturday' => 'Sat',
                        'label.sunday' => 'Sun'];

    /**
     * Creates a new openingsHours entity.
     *
     * @Route("/new", name="admin_hours_new")
     * @Method({"GET", "POST"})
     *
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function newAction(Request $request){

        //Creation of new object
        $newHours = new OpeningHours("");

        // creation of form
        $form = $this->createForm(OpeningsHoursType::class, $newHours);
        $form->handleRequest($request);

        /**
         * Function to search in the database for a record and update that record with new data.
         */
        if ($form->isSubmitted() && $form->isValid() && $this->validateData($newHours)) {

            try {
                $entityManager = $this->getDoctrine()->getManager();
                // find object in database
                $hourToUpdate = $entityManager->getRepository('AppBundle:OpeningHours')->findOneBy(['dayOfWeek' => $newHours->getDayOfWeek()]);
                // if object exists -> update
                if (!$hourToUpdate == null) {

                    $hourToUpdate->setOpeningTime($newHours->getOpeningTime());
                    $hourToUpdate->setClosingTime($newHours->getClosingTime());
                    $entityManager->merge($hourToUpdate);

                }
                // if objects doesn't exists insert
                else{

                    $entityManager->persist($newHours);
                }
                $entityManager->flush();

                $this->addFlash('success', 'post.created_successfully');
            }
            catch(UniqueConstraintViolationException $exception){

                $this->addFlash('warning', 'day.double');

            }

            return $this->redirectToRoute('admin_hours_new');
        }


        return $this->render('admin/blog/newHours.html.twig', [
            'post' => $newHours,
            'form' => $form->createView(),
        ]);
    }

    private function validateData(OpeningHours $hours){

        $regExp = "/^[0-2][0-9]\:[0-5][0-9]$/";
        $result = false;

        // check if time is according to the regular expression
        if ( $this->checkInputDay($hours) && strlen($hours->getOpeningTime()) == 5 && strlen($hours->getClosingTime()) == 5 && preg_match($regExp, $hours->getOpeningTime()) && preg_match($regExp, $hours->getClosingTime())) {

            $result = true;

        }

        if (!$result){
            $this->addFlash('warning', 'day.wrong_format');

        }

        return $result;
    }


    // check if value is one the values below
    private function checkInputDay(OpeningHours $openingHours){

        $result = false;
        $currentday = $openingHours->getDayOfWeek();
        return in_array($currentday, self::$weekdays);

    }
}