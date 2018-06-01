<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Support;
use AppBundle\Form\SupportType;

/**
 * Support controller.
 *
 * @Route("/support")
 */
class SupportController extends Controller
{
    /**
     * Lists all Support entities.
     *
     * @Route("/", name="support_index")
     * @Method("GET")
     */
    public function indexAction()
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();

        $supports = $em->getRepository('AppBundle:Support')->findAll();

        return $this->render('support/index.html.twig', array(
            'supports' => $supports,
        ));
    }

    /**
     * Creates a new Support entity.
     *
     * @Route("/new", name="support_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        // TODO: Especificar fecha límite
        $now = new \DateTime();
        $deadline = new \DateTime('2018-05-20');

        if($now >= $deadline)
            return $this->render(':support:closed.html.twig');

        // If support, redirects to edit
        $user = $this->getUser();

        if($user->getSupport()) {
            // redirect to a route with parameters
            return $this->redirectToRoute('support_edit', array('id' => $user->getSupport()->getId()));
        }

        $support = new Support();

        $form = $this->createForm('AppBundle\Form\SupportType', $support);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $support->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($support);
            $em->flush();

            // Email recomendaciones
            $mailer = $this->get('mailer');

            $subject = 'Request for recommendation letter for ' . $user->getName(). ' ' .$user->getSurname();

            // Envía correo de solicitud de recomendación 1
            $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom('sigmap2018@matmor.unam.mx')
                ->setTo(array($support->getMailprof1()))
                ->setBcc(array('miguel@matmor.unam.mx'))
                ->setBody($this->renderView('support/reference-request.txt.twig', array(
                    'support' => $support, 'email' => $support->getMailprof1(), 'name' => $support->getProf1())
                ))
            ;
            $mailer->send($message);

            // Envía correo de solicitud de recomendación 2
            $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom('sigmap2018@matmor.unam.mx')
                ->setTo(array($support->getMailprof2()))
                ->setBcc(array('miguel@matmor.unam.mx'))
                ->setBody($this->renderView('support/reference-request.txt.twig', array(
                    'support' => $support, 'email' => $support->getMailprof2(), 'name' => $support->getProf2())
                ))
            ;
            $mailer->send($message);

            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('user_index');
        }

        return $this->render('support/new.html.twig', array(
            'support' => $support,
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Support entity.
     *
     * @Route("/{id}", name="support_show")
     * @Method("GET")
     */
    public function showAction(Support $support)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $deleteForm = $this->createDeleteForm($support);

        return $this->render('support/show.html.twig', array(
            'support' => $support,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Change grant status
     *
     * @Route("/{id}/grant", name="support_grant")
     * @Method("GET")
     */
    public function grantAction(Support $support)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();

        if($support->getApproved())
            $support->setApproved(0);
        else
            $support->setApproved(1);

        $em->persist($support);
        $em->flush();

        return $this->redirectToRoute('admin_show', array('slug' => $support->getUser()->getSlug()));
    }

    /**
     * Displays a form to edit an existing Support entity.
     *
     * @Route("/{id}/edit", name="support_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Support $support)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $deleteForm = $this->createDeleteForm($support);
        $editForm = $this->createForm('AppBundle\Form\SupportType', $support);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($support);
            $em->flush();

            return $this->redirectToRoute('support_edit', array('id' => $support->getId()));
        }

        return $this->render('support/edit.html.twig', array(
            'support' => $support,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Support entity.
     *
     * @Route("/{id}", name="support_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Support $support)
    {
        $form = $this->createDeleteForm($support);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($support);
            $em->flush();
        }

        return $this->redirectToRoute('support_index');
    }

    /**
     * Creates a form to delete a Support entity.
     *
     * @param Support $support The Support entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Support $support)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('support_delete', array('id' => $support->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
