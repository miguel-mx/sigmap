<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Prometeo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Prometeo controller.
 *
 * @Route("prometeo")
 */
class PrometeoController extends Controller
{
    /**
     * Lists all prometeo entities.
     *
     * @Route("/", name="prometeo_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $prometeos = $em->getRepository('AppBundle:Prometeo')->findAll();

        return $this->render('prometeo/index.html.twig', array(
            'prometeos' => $prometeos,
        ));
    }

    /**
     * Creates a new prometeo entity.
     *
     * @Route("/new", name="prometeo_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $prometeo = new Prometeo();
        $form = $this->createForm('AppBundle\Form\PrometeoType', $prometeo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($prometeo);
            $em->flush();

            return $this->redirectToRoute('prometeo_show', array('id' => $prometeo->getId()));
        }

        return $this->render('prometeo/new.html.twig', array(
            'prometeo' => $prometeo,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a prometeo entity.
     *
     * @Route("/{id}", name="prometeo_show")
     * @Method("GET")
     */
    public function showAction(Prometeo $prometeo)
    {
        $deleteForm = $this->createDeleteForm($prometeo);

        return $this->render('prometeo/show.html.twig', array(
            'prometeo' => $prometeo,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing prometeo entity.
     *
     * @Route("/{id}/edit", name="prometeo_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Prometeo $prometeo)
    {
        $deleteForm = $this->createDeleteForm($prometeo);
        $editForm = $this->createForm('AppBundle\Form\PrometeoType', $prometeo);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('prometeo_edit', array('id' => $prometeo->getId()));
        }

        return $this->render('prometeo/edit.html.twig', array(
            'prometeo' => $prometeo,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a prometeo entity.
     *
     * @Route("/{id}", name="prometeo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Prometeo $prometeo)
    {
        $form = $this->createDeleteForm($prometeo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($prometeo);
            $em->flush();
        }

        return $this->redirectToRoute('prometeo_index');
    }

    /**
     * Creates a form to delete a prometeo entity.
     *
     * @param Prometeo $prometeo The prometeo entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Prometeo $prometeo)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('prometeo_delete', array('id' => $prometeo->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
