<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Recommendation;
use AppBundle\Entity\User;
use AppBundle\Form\RecommendationType;

/**
 * Recommendation controller.
 *
 * @Route("/recommendation")
 */
class RecommendationController extends Controller
{
    /**
     * Lists all Recommendation entities.
     *
     * @Route("/", name="recommendation_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $recommendations = $em->getRepository('AppBundle:Recommendation')->findAll();

        return $this->render('recommendation/index.html.twig', array(
            'recommendations' => $recommendations,
        ));
    }

    /**
     * Creates a new Recommendation entity.
     * @Route("/{slug}/{email}/new", name="recommendation_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $slug, $email)
    {
        // TODO: Especificar fecha límite
        $now = new \DateTime();
        $deadline = new \DateTime('2018-05-27');
        if($now >= $deadline)
            return $this->render(':recommendation:closed.html.twig');

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->findOneBySlug($slug);

        $support = $user->getSupport();

        if($support == NULL)
            throw $this->createNotFoundException('Access denied (support)');

        if($support->getMailprof1() != $email && $support->getMailprof2() != $email)
           throw $this->createNotFoundException('Access denied (email)');

        $recommended = $user->getSupport()->isRecomended($email);
        if($recommended) {
            $this->addFlash(
                'notice',
                'Thank you for your help! The organizing committee... '
            );

            return $this->redirectToRoute('recommendation_show', array('slug' => $slug, 'id' => $recommended->getId()));
        }

        $recommendation = new Recommendation();
        $recommendation->setSupport($user->getSupport());
        $recommendation->setEmail($email);

        $form = $this->createForm('AppBundle\Form\RecommendationType', $recommendation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($recommendation);
            $em->flush();

            $this->addFlash(
                'notice',
                'Thank you for your help! The organizing committee... '
            );

            return $this->redirectToRoute('recommendation_show', array('slug' => $slug, 'id' => $recommendation->getId()));
        }

        return $this->render('recommendation/new.html.twig', array(
            'recommendation' => $recommendation,
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Recommendation entity.
     *
     * @Route("/{slug}/{id}", name="recommendation_show")
     * @Method("GET")
     */
    public function showAction($slug, Recommendation $recommendation)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->findOneBySlug($slug);

        $deleteForm = $this->createDeleteForm($recommendation);

        return $this->render('recommendation/show.html.twig', array(
            'recommendation' => $recommendation,
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Recommendation entity.
     *
     * @Route("/{id}/edit", name="recommendation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Recommendation $recommendation)
    {
        $deleteForm = $this->createDeleteForm($recommendation);
        $editForm = $this->createForm('AppBundle\Form\RecommendationType', $recommendation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($recommendation);
            $em->flush();

            return $this->redirectToRoute('recommendation_edit', array('id' => $recommendation->getId()));
        }

        return $this->render('recommendation/edit.html.twig', array(
            'recommendation' => $recommendation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Recommendation entity.
     *
     * @Route("/{id}", name="recommendation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Recommendation $recommendation)
    {
        $form = $this->createDeleteForm($recommendation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($recommendation);
            $em->flush();
        }

        return $this->redirectToRoute('recommendation_index');
    }

    /**
     * Creates a form to delete a Recommendation entity.
     *
     * @param Recommendation $recommendation The Recommendation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Recommendation $recommendation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('recommendation_delete', array('id' => $recommendation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
