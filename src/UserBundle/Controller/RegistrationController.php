<?php
// src/UserBundle/Controller/RegistrationController.php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\RegistrationController as BaseController;

class RegistrationController extends BaseController
{
    public function registerAction()
    {
        $form = $this->container->get('fos_user.registration.form');
        $formHandler = $this->container->get('fos_user.registration.form.handler');
        $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');

        $process = $formHandler->process($confirmationEnabled);
        if ($process) {
            $user = $form->getData();

            /*****************************************************
             * Add new functionality (e.g. log the registration) *
             *****************************************************/

            $authUser = false;
            if ($confirmationEnabled) {
                $this->container->get('session')->set('fos_user_send_confirmation_email/email', $user->getEmail());
                $route = 'fos_user_registration_check_email';
            } else {
                $authUser = true;
                $route = 'fos_user_registration_confirmed';
            }

            $logger = $this->container->get('logger');
            $logger->info('Nuevo registro');

            // Email confirmation
            $mailer = $this->container->get('mailer');

//            $transport = $mailer->getTransport();
//            if($transport instanceof \Swift_Transport_EsmtpTransport){
//                //$transport->setStreamOptions(['ssl' => ['allow_self_signed' => true, 'verify_peer' => false, 'verify_peer_name' => false]]);
//                $transport->setStreamOptions(array('ssl' => array('allow_self_signed' => true, 'verify_peer' => false)));
//                $transport->setEncryption()
//            }

            //$transport = $this->container->get("swiftmailer.mailer.default.transport");
            // disable SSL certificate validation
            //$transport->setStreamOptions(array('ssl' => array('allow_self_signed' => true, 'verify_peer' => false)));

            $message = \Swift_Message::newInstance()
                ->setSubject('SIGMAP 2018 Registry')
                ->setFrom('sigmap2018@matmor.unam.mx')
                ->setTo(array($user->getEmail()))
                ->setBcc(array('rudos@matmor.unam.mx'))
                ->setBody($this->container->get('templating')->render('user/registry-confirmation.txt.twig', array('user' => $user)))
            ;
            $mailer->send($message);

            $this->setFlash('fos_user_success', 'registration.flash.user_created');

            $url = $this->container->get('router')->generate($route);
            $response = new RedirectResponse($url);

            if ($authUser) {
                $this->authenticateUser($user, $response);
            }

            return $response;
        }

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:register.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}













