<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Payment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use singpolyma\lib\opengp_crypt_rsa as opengp_crypt_rsa.php;
use phpseclib\Math\BigInteger as Math_BigInteger;

require_once dirname(__FILE__).'/../lib/openpgp.php';
require_once dirname(__FILE__).'/../lib/openpgp_crypt_rsa.php';
require_once dirname(__FILE__).'/../lib/openpgp_crypt_symmetric.php';

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
        $data = $request->request->all();

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
            $payment->setIdproducto('simap0001');
            $payment->setIsocode('en');

            // Esperar respuesta de la tienda
            $payment->setResponse('200');

//            $em = $this->getDoctrine()->getManager();
//            $em->persist($payment);
//            $em->flush();
//
//            $this->addFlash(
//                'notice',
//                'Your payment information was sent! ' . $response
//            );

            $data_json = $this->sendToPrometeo($payment);
            $response = new Response($data_json);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
            //return $this->redirectToRoute('user_index');

            // return $this->redirectToRoute('payment_show', array('id' => $payment->getId()));
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

        $pubkey = "-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: GnuPG v1.4.10 (GNU/Linux)

mQENBFinfTEBCADBXzOPHZ2urCHw8mh6wDyywMPgcIhB4yhvBrbLTNPDwypmxW1n
3k0QVIoRoaUFEx2D2PMd7EHVdjXjVptMAazLdCTb4yHfAGZ6Ze7bgRFZNnSNu9xM
symE105jtpSCquHwORJocJShZFxrGvXeJmzgW4iq9TNgNX1IzEFNHkn/JeDN7LPm
r/7zby1N9wVsCAU/su82KAm37CiYmh19Pj0+INp44eeZ0lmHRxVzQUMSwZO3n0Ew
1x5djLWqa9lAKChdoex2xjhLJQv6uXIf4+QMbyWFaltS8HEIv58hKiGsRsoOk1P3
ttAK4+MiyOfwqIkfg9OiRN1My2MrtseIMSAvABEBAAG0Y1RpZW5kYSBlbiBMaW5l
YSBQbGF6YSBQcm9tZXRlbyAtIEZhY3VsdGFkIGRlIENpZW5jaWFzIC0gVU5BTSAo
UHJvZHVjY2lvbikgPHRpZW5kYUBjaWVuY2lhcy51bmFtLm14PokBOAQTAQIAIgUC
WKd9MQIbAwYLCQgHAwIGFQgCCQoLBBYCAwECHgECF4AACgkQ1Da/Cuc2OQQsgggA
mqpdY90+3J/o5pL5lNrSYQDfhspn7wmIRHXGfvNiU58bkPWZ+Tl54OMQ2Wyxe7I6
XjHGFuxqWM1EHI+cbzsrt8MI4wk9r4RiRgJdZ8nzJOV+3bRf0q4ESK1udveBdI4P
AMfIPbV5pr2M3pjboqugeu91GDRDTQGsmxMtuY7dQdy9PvVfVoJj5F5i6batMaUT
7Z+pV8cJv9NtMTbaGsmpY0KB7pwQHbk92Eu5Ml0iiucMvGWNPgov4T83s+Uqn5Nu
sOgWRTZskKf0PVAeNr80CZ7McrK4wY4sVbiwaFBFi3RbPqlse55ZGjTsYVhMGWZC
jtlb5kR1tPUXdLWx2KjEcLkBDQRYp30xAQgAvCeqkHk/QD3RLYjp+4a3q7/lBz73
T/J+5YDiKSiPCaTKhLHKgRaK8gzmX66G5QfljFMHRInMkbxFL6MJkhakqi+eMVgD
BaOc27zWiAPytxb5kfiZhR+nu8YWrmCzbeKKgnzUFo2APGmrfmsdofjvtzk0av/p
ivM7w2gfYdIgWBUfA6d3FAlID3RSNWYbVw1LcPkBPhwEW6CZJtfyT/kWZvXk9Xlm
jMuIQFFmXjJ73M0QeYs8tMwGYNxC6Egj82jGQ79eaPCuA12xuM/aMeP42/oU0okB
62Z50pNTOJbq9cHJ533+3CmgRtMGlPrg3ufV6HN3JWzB6ws3nbMmU2TCawARAQAB
iQEfBBgBAgAJBQJYp30xAhsMAAoJENQ2vwrnNjkEGhAH/jLyTTJRjKTt28JPZFBQ
IX88nydYPilgTfwAPHeoL29qrZQ2ZNzM/twVNQM3D8Xp9hd27spu9M92GfRhsNC8
6fHUy/tWi9UUk7cGE8aVO5VIibhRCPD1Yjxv1tsPj4YSGkIwBghO5aUS8zuiC3dD
3RsQHx6yKIC4UdDmJftuWHk/hhFVdzHNFmyp8FrL2NPdWFZR6+PGBFL5YijbWxvr
iROc25/nUbpqX9KKiEQNIoUsn/BBTrStFjKkcYY3udtc/mqCoRVdPBCjcgBxv8gx
9LIAYnXKoZ+XFf4a0PQNmMu4RsoGFGz1a2uYWeWVyiX1W7pgSFFa5BL+y2VRI5JR
WQc=
=REGy
-----END PGP PUBLIC KEY BLOCK-----";

//        require_once dirname(__FILE__).'/../lib/openpgp.php';
//        require_once dirname(__FILE__).'/../lib/openpgp_crypt_rsa.php';
//        require_once dirname(__FILE__).'/../lib/openpgp_crypt_symmetric.php';
//        $key = OpenPGP_Message::parse(file_get_contents(dirname(__FILE__) . '/../tests/data/helloKey.gpg'));
//        $data = new OpenPGP_LiteralDataPacket('This is text.', array('format' => 'u', 'filename' => 'stuff.txt'));
//        $encrypted = OpenPGP_Crypt_Symmetric::encrypt($key, new OpenPGP_Message(array($data)));


        $criptograma = OpenPGP_Crypt_Symmetric::encrypt($pubkey, new OpenPGP_Message(array($json_data)));

        // Envío de información
        $ch = curl_init($this->generateUrl('payment_data'));

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
//            var_dump(json_decode($response,true));
            return $response;
        }
    }

}
