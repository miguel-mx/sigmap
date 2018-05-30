<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;

/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * User Dashboard
     *
     * @Route("/", name="user_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();

        return $this->render('user/dash.html.twig', array(
            'user' => $user,
        ));
    }

    /**
     * Finds and displays a User entity.
     *
     * @Route("/{slug}", name="user_show")
     * @Method("GET")
     */
    public function showAction(User $user)
    {
        $userLogged = $this->getUser();

        if($user != $userLogged)
            throw $this->createAccessDeniedException('You cannot access this page!');

        $deleteForm = $this->createDeleteForm($user);

        return $this->render('user/dash.html.twig', array(
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{slug}/edit", name="user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user)
    {
        $userLogged = $this->getUser();

        if($user != $userLogged)
            throw $this->createAccessDeniedException('You cannot access this page!');

        $editForm = $this->createForm('AppBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Displays a form to edit Abstract of an existing User entity.
     *
     * @Route("/{slug}/talk-edit", name="talk-edit")
     * @Method({"GET", "POST"})
     */
    public function talkEditAction(Request $request, User $user)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        // TODO: Especificar fecha límite
        $now = new \DateTime();
        $deadline = new \DateTime('2018-06-01');
        if($now >= $deadline)
            return $this->render(':user:closed.html.twig');

        $editForm = $this->createForm('AppBundle\Form\TalkType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('user_index');
//            return $this->redirectToRoute('user_edit', array('slug' => $user->getSlug()));
        }

        return $this->render('user/talk.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Displays a form to edit Visits of an existing User entity.
     *
     * @Route("/{slug}/visit-edit", name="visit-edit")
     * @Method({"GET", "POST"})
     */
    public function visitEditAction(Request $request, User $user)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        // TODO: Especificar fecha límite
//        $now = new \DateTime();
//        $deadline = new \DateTime('2018-06-01');
//        if($now >= $deadline)
//            return $this->render(':user:closed.html.twig');

        $editForm = $this->createForm('AppBundle\Form\VisitType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('user_index');
//            return $this->redirectToRoute('user_edit', array('slug' => $user->getSlug()));
        }

        return $this->render('user/visit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Change Talk acceptance
     *
     * @Route("/{slug}/talk-acceptance", name="talk-acceptance")
     * @Method("GET")
     */
    public function grantAction(User $user)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();

        if($user->getAccepted())
            $user->setAccepted(0);
        else
            $user->setAccepted(1);

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('admin_show', array('slug' => $user->getSlug()));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{slug}/poster", name="user_poster")
     * @Method({"GET"})
     */
    public function posterAction(Request $request, User $user)
    {
            $em = $this->getDoctrine()->getManager();
            $user->setPoster(1);
            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'notice',
                'Your poster request has been saved!'
            );

            return $this->redirectToRoute('user_index');
    }


    /**
     * Deletes a User entity.
     *
     * @Route("/{id}", name="user_delete")
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
