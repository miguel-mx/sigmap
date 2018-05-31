<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\User;
use AppBundle\Entity\Prometeo;


/**
 * Admin controller.
 *
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * Lists all User entities.
     *
     * @Route("/", name="admin_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $lastLogin = $user->getLastLogin()->format("Y-m-d");

        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findAll();
        $totalStudents = $em->getRepository('AppBundle:User')->countStudents();
        $totalTalks = $em->getRepository('AppBundle:User')->countTalks();
        $totalPosters = $em->getRepository('AppBundle:User')->countPosters();
        $totalPayments = $em->getRepository('AppBundle:Prometeo')->countPayments();

        $payments = $em->getRepository('AppBundle:Prometeo')->findPaymentsbyDate($lastLogin);
        $last_registrations = $em->getRepository('AppBundle:User')->findUsersbyDate($lastLogin);

        $totalDinner = $em->getRepository('AppBundle:User')->countDinner();
        $totalMorelia = $em->getRepository('AppBundle:User')->countMorelia();
        $totalPatzcuaro = $em->getRepository('AppBundle:User')->countPatzcuaro();

        return $this->render('admin/index.html.twig', array(
            'totalStudents' => $totalStudents,
            'totalTalks' => $totalTalks,
            'totalPosters' => $totalPosters,
            'totalPayments' => $totalPayments,
            'last_registrations' => $last_registrations,
            'payments' => $payments,
            'users' => $users,
            'totalDinner' => $totalDinner,
            'totalMorelia' => $totalMorelia,
            'totalPatzcuaro' => $totalPatzcuaro,
        ));
    }


    /**
     * Finds and displays a User entity.
     *
     * @Route("/{slug}", name="admin_show")
     * @Method("GET")
     */
    public function showAction(User $user)
    {

//        $deleteForm = $this->createDeleteForm($user);

        return $this->render('admin/show.html.twig', array(
            'user' => $user,
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", name="admin_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('AppBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_edit', array('id' => $user->getId()));
        }

        return $this->render('user/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a User entity.
     *
     * @Route("/{id}", name="admin_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * Creates a form to delete a User entity.
     *
     * @param User $user The User entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

}