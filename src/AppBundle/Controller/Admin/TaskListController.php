<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use AppBundle\Entity\TaskList;

/**
 * @Route("/admin/tasks")
 */

class TaskListController extends Controller{

    /**
     * @Route("/tasklist", name="task_list")
     */
    public function listAction(Request $request)
    {
        $tasks = $this->getDoctrine()
            ->getRepository('AppBundle:TaskList')
            ->findAll();

        return $this->render('admin/tasks/index.html.twig', array(
            'tasks' => $tasks

        ));
    }

    /**
     * @Route("/create", name="task_create")
     */
    public function createAction(Request $request)
    {
        $taskToDo = new TaskList();

        $form = $this->createFormBuilder($taskToDo)
            ->add('task', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('due_date', DateType::class, array('attr' => array('style' => 'margin-bottom:15px')))
            ->add('Save', SubmitType::class, array('label'=> 'Create Task', 'attr' => array('class' => 'btn btn-primary', 'style' => 'margin-bottom:15px')))
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() &&  $form->isValid()){
            $description = $form['description']->getData();
            $due_date = $form['due_date']->getData();
            $task = $form['task']->getData();

            $taskToDo->setTask($task);
            $taskToDo->setDescription($description);
            $taskToDo->setDuedate($due_date);

            $sn = $this->getDoctrine()->getManager();
            $sn -> persist($taskToDo);
            $sn -> flush();

            $this->addFlash(
                'notice',
                'Task Added'
            );
            return $this->redirectToRoute('task_list');

        }

        return $this->render('admin/tasks/create.html.twig', array(
            'form' => $form->createView()

        ));
    }

    /**
     * @Route("/details/{id}", name="task_details")
     */
    public function detailsAction($id)
    {
        $tasks = $this->getDoctrine()
            ->getRepository('AppBundle:TaskList')
            ->find($id);

        return $this->render('admin/tasks/details.html.twig', array(
            'tasks' => $tasks
        ));
    }

    /**
     * @Route("/edit/{id}", name="task_edit")
     */
    public function editAction($id,Request $request)
    {
        $taskToDo = $this->getDoctrine()
            ->getRepository('AppBundle:TaskList')
            ->find($id);

        $taskToDo->setTask($taskToDo->getTask());
        $taskToDo->setDescription($taskToDo->getDescription());
        $taskToDo->setDuedate($taskToDo->getDueDate());

        $form = $this->createFormBuilder($taskToDo)
            ->add('task', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('due_date', DateType::class, array('attr' => array('style' => 'margin-bottom:15px')))
            ->add('Save', SubmitType::class, array('label'=> 'Update Task', 'attr' => array('class' => 'btn btn-primary', 'style' => 'margin-bottom:15px')))
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() &&  $form->isValid()){
            $description = $form['description']->getData();
            $due_date = $form['due_date']->getData();
            $task = $form['task']->getData();

            $sn = $this->getDoctrine()->getManager();
            $taskToDo = $sn->getRepository('AppBundle:TaskList')->find($id);

            $taskToDo->setTask($task);
            $taskToDo->setDescription($description);
            $taskToDo->setDuedate($due_date);

            $sn -> flush();

            $this->addFlash(
                'notice',
                'Task Updated'
            );
            return $this->redirectToRoute('task_list');

        }

        return $this->render('/admin/tasks/edit.html.twig', array(
            'task' => $taskToDo,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/delete/{id}", name="task_delete")
     */
    public function deleteAction($id)
    {

        $sn = $this->getDoctrine()->getManager();
        $task = $sn->getRepository('AppBundle:TaskList')->find($id);

        $sn->remove($task);
        $sn->flush();
        //  $tasks = $this->getDoctrine()
        // ->getRepository('AppBundle:Task')
        // ->find($id);

        $this->addFlash(
            'notice',
            'Task Removed'
        );
        return $this->redirectToRoute('task_list');
    }
}