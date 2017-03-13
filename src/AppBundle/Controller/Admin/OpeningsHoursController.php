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
    public function newAction(Request $request)
    {
        $newHours = new OpeningHours("");
        $newHours->setOpeningTime("");
        $newHours->setClosingTime("");

        // See http://symfony.com/doc/current/book/forms.html#submitting-forms-with-multiple-buttons
        $form = $this->createForm(OpeningsHoursType::class, $newHours);
            //->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        /**
         * Function to search in the database for a record and update that record with new data.
         */
        if ($form->isSubmitted() && $form->isValid() && $this->validateData($newHours)) {
            try {
                $entityManager = $this->getDoctrine()->getManager();
                $hourToUpdate = $entityManager->getRepository('AppBundle:OpeningHours')->findOneBy(['dayOfWeek' => $newHours->getDayOfWeek()]);

                $hourToUpdate->setOpeningTime($newHours->getOpeningTime());
                $hourToUpdate->setClosingTime($newHours->getClosingTime());

                $entityManager->merge($hourToUpdate);
                $entityManager->flush();

                $this->addFlash('success', 'post.created_successfully');
            }
            catch(UniqueConstraintViolationException $exception){

                $this->addFlash('success', 'day.double');

            }
            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See http://symfony.com/doc/current/book/controller.html#flash-messages

            return $this->redirectToRoute('admin_hours_new');
        }


        return $this->render('admin/blog/newHours.html.twig', [
            'post' => $newHours,
            'form' => $form->createView(),
        ]);
    }

    private function validateData(OpeningHours $hours){

        $regExp = "/^[0,-2][0-9]\:[0-5][0-9]$/";
        $result = false;

        // check if day is one the real days
        switch ($hours->getDayOfWeek()) {

            case "Mon":
                $result = true;
                break;
            case "Tue":
                $result = true;
                break;
            case "Wed":
                $result = true;
                break;
            case "Thu":
                $result = true;
                break;
            case "Fri":
                $result = true;
                break;
            case "Sat":
                $result = true;
                break;
            case "Sun":
                $result = true;
                break;
        }

        // check if time is according to the regular expression
        if ( strlen($hours->getOpeningTime()) == 5 && preg_match($regExp, $hours->getOpeningTime()) && preg_match($regExp, $hours->getClosingTime())) {

             $result = true;

        }


        return $result;

    }

}