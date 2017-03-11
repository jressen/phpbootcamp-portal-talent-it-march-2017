<?php

// src/AppBundle/Controller/RegistrationController.php
namespace AppBundle\Controller;

use AppBundle\Form\UserFormType;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class RegistrationController extends Controller
{
  /**
  * @Route("/register", name="register_userregistration")
  */
  public function registerAction(Request $request)
  {
    $user = new User();
    $form = $this->createForm(UserFormType::class,$user);
    // only handles data on POST
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {

      // encode the password for secure database storage
      $encoder = $this->container->get('security.password_encoder');
      $encoded = $encoder->encodePassword($user, $user->getPassword());
      $user->setPassword($encoded);

      $em = $this->getDoctrine()->getManager();
      $em->persist($user);
      try {
        $em->flush();
      } catch (UniqueConstraintViolationException $e) {
        $this->addFlash('danger', 'This user already exists.');
        return $this->render('register/userregistration.html.twig', [
          'entity' => $user,
          'form' => $form->createView(),
        ]);
      }


      // message if the user was created succesfully
      $this->addFlash('succes', 'User was created successfully.');

      return $this->redirectToRoute('security_login');
    }

    return $this->render('register/userregistration.html.twig', [
      'form' => $form->createView(),
    ]);
  }
}
