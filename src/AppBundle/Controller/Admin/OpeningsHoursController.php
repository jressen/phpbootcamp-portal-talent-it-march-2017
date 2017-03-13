<?php
/**
 * Created by PhpStorm.
 * User: johnnyressen
 * Date: 10/03/17
 * Time: 11:04
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\OpeningHours;
use AppBundle\Form\OpeningsHoursType;
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
        $newHours = new OpeningHours();
        $newHours->setDayOfWeek("");
        $newHours->setOpeningTime("");
        $newHours->setClosingTime("");

        // See http://symfony.com/doc/current/book/forms.html#submitting-forms-with-multiple-buttons
        $form = $this->createForm(OpeningsHoursType::class, $newHours);
            //->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See http://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid() && $this->validateData($newHours)) {
            try {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($newHours);
                $entityManager->flush();
            }
            catch(Exception $exception){

                $this->addFlash('failed', 'Er is iets misgegaan bij het wegschrijven van de data');

            }
            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See http://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'post.created_successfully');

            return $this->redirectToRoute('admin_hours_new');
        }


        return $this->render('admin/blog/newHours.html.twig', [
            'post' => $newHours,
            'form' => $form->createView(),
        ]);
    }

    private function validateData(OpeningHours $hours){

        $regExp = "[0,2][0,9]:[0,5][0,9]";
        $result = false;

        // check if day is one the real days
        switch ($hours->getDayOfWeek()) {

            case "Maandag":
                $result = true;
                break;
            case "Dinsdag":
                $result = true;
                break;
            case "Woensdag":
                $result = true;
                break;
            case "Donderdag":
                $result = true;
                break;
            case "Vrijdag":
                $result = true;
                break;
            case "Zaterdag":
                $result = true;
                break;
            case "Zondag":
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