<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Payment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Utils\Crypt\Crypt\GPG\Crypt_GPG;


/**
 * Payment controller.
 *
 * @Route("payment")
 */
class PaymentController extends Controller
{
    /**
     * Lists all payment entities.
     *
     * @Route("/", name="payment_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();

        $payments = $em->getRepository('AppBundle:Payment')->findAll();

        return $this->render('payment/index.html.twig', array(
            'payments' => $payments,
        ));
    }

    /**
     * Test sent data.
     *
     * @Route("/test", name="payment_data")
     * @Method("POST")
     */
    public function testAction()
    {
        $data = ''; //$this->getParameter('');

        return $this->render('payment/test.html.twig', array(
            'data' => $data,
        ));
    }

    /**
     * Creates a new payment entity.
     *
     * @Route("/new", name="payment_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $payment = new Payment();

        // Validar que no haya hecho ya un pago éste usuario.

        $form = $this->createForm('AppBundle\Form\PaymentType', $payment);
        $form->handleRequest($request);

        // If support, redirects to edit
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {

            $payment->setUser($user);
            $payment->setIdproducto('198');
            $payment->setIsocode('en');

            $loger = $this->get('logger');
            $loger->info('Inicio send to Ciencias');

            // Respuesta_fciencias
            $respuesta_fciencias = $this->sendToPrometeo($payment);

            $loger->info('Respuesta Facultad de Ciencias');
            $loger->info($respuesta_fciencias);

            $respuesta_array = json_decode($respuesta_fciencias, true);

            // Comentar ésto
            $payment->setResponse($respuesta_fciencias);

//            $em = $this->getDoctrine()->getManager();
//            $em->persist($payment);
//            $em->flush();
//
//            $this->addFlash(
//                'notice',
//                'Your payment information was sent! ' . $response
//            );

//            return $this->redirectToRoute('payment_show', array('id' => $payment->getId()));

            return $this->render('payment/test.html.twig', array(
                'payment' => $payment,
                'respuesta_fciencias' => $respuesta_fciencias,
                'respuesta_array' => $respuesta_array,
            ));
        }

        return $this->render('payment/new.html.twig', array(
            'payment' => $payment,
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a payment entity.
     *
     * @Route("/{id}", name="payment_show")
     * @Method("GET")
     */
    public function showAction(Payment $payment)
    {
        $deleteForm = $this->createDeleteForm($payment);

        return $this->render('payment/show.html.twig', array(
            'payment' => $payment,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing payment entity.
     *
     * @Route("/{id}/edit", name="payment_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Payment $payment)
    {
        $deleteForm = $this->createDeleteForm($payment);
        $editForm = $this->createForm('AppBundle\Form\PaymentType', $payment);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('payment_edit', array('id' => $payment->getId()));
        }

        return $this->render('payment/edit.html.twig', array(
            'payment' => $payment,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a payment entity.
     *
     * @Route("/{id}", name="payment_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Payment $payment)
    {
        $form = $this->createDeleteForm($payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($payment);
            $em->flush();
        }

        return $this->redirectToRoute('payment_index');
    }

    /**
     * Creates a form to delete a payment entity.
     *
     * @param Payment $payment The payment entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Payment $payment)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('payment_delete', array('id' => $payment->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /*
     * Send information to Prometeo
     * @return string
     *
     */
    public function sendToPrometeo(Payment $payment)
    {
        $datos = array(
            'cuenta' => array(
                'correo' => $payment->getUser()->getEmail(),
                /*'pass'  => 'Ad+fg1',*/
                'nombre' => $payment->getUser()->getName(),
                'apaterno' => $payment->getUser()->getSurname(),
                'amaterno' => '',
                'birthdate' => $payment->getBirthdate()->format('Y-m-d'),
                'sexo'   => $payment->getUser()->getGender()
            ),
            'direccion' => array(
                'rfc' => $payment->getRfc(),
                'empresa' => $payment->getEmpresa(),
                'calle' => $payment->getCalle(),
                'numexterior' => $payment->getNumexterior(),
                'colonia' => $payment->getColonia(),
                'cpostal' => $payment->getCpostal(),
                'pais'  => $payment->getPais()->getId(),
                'estado' => $payment->getEstado()->getId(),
                'delegacion' => $payment->getDelegacion(),
                'telefono' => $payment->getTelefono(),
            ),
            'producto' => array(
                'idproducto' => $payment->getIdproducto(),
            ),
            'idioma' => array(
                'isocode' => $payment->getIsocode(),
            ),

        );
        $json_data =  json_encode($datos);

        // Cifrado de datos
        // -----------------------------------------------------

        $fprintkey = 'DD6F58783C72B6E81706B990B50D5A2F58965044';

        $opciones = array(
            'privateKeyring' => '/var/www/.gnupg/secring.gpg',
            'publicKeyring'  => '/var/www/.gnupg/pubring.gpg',
            'trustDb'        => '/var/www/.gnupg/trustdb.gpg',
            'homedir'        => '/var/www/.gnupg',
            'debug'          => false
        );

        $gpg = new Crypt_GPG($opciones);
        $gpg->addencryptKey($fprintkey);

        try{
            $criptograma = $gpg->encrypt($json_data);
        }catch(\Exception $exception){
            echo 'Error al cifrar el mensaje'. $exception;
            exit(1);
        }

        //return $criptograma;

        // Envío de información a Prometeo
        // ---------------------------------------------------
        $ch = curl_init('https://tiendad.fciencias.unam.mx/serviciosweb/WSAddClient.php');

        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($criptograma)
            ),
            CURLOPT_POSTFIELDS => $criptograma,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_SSL_VERIFYPEER => false
        ));

        $response = curl_exec($ch);

        // Check for errors
        if($response === FALSE){
            die(curl_error($ch));
        }
        else{
            //var_dump(json_decode($response,true));
            //return json_decode($response,true);
            return $response;
        }
    }
}
