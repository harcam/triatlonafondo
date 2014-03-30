<?php

namespace Harcam\TriatlonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Harcam\TriatlonBundle\Entity\Client;

class RegistrationController extends Controller
{
    public function signupAction(Request $request)
    {
        // Initialize Client object to handle the form
        $client = new Client();

        $categories = array(
            'JF' => 'Femenil 16 a 19 años',
            'N' => 'Femenil 20 a 24 años',
            'O' => 'Femenil 25 a 29 años',
            'P' => 'Femenil 30 a 34 años',
            'Q' => 'Femenil 35 a 39 años',
            'R' => 'Femenil 40 a 44 años',
            'S' => 'Femenil 45 a 49 años',
            'T' => 'Femenil 50 a 54 años',
            'V' => 'Femenil 55 a 59 años',
            'IC' => 'Varonil 14 a 15 años',
            'JV' => 'Varonil 16 a 19 años',
            'A' => 'Varonil 20 a 24 años',
            'B' => 'Varonil 25 a 29 años',
            'C' => 'Varonil 30 a 34 años',
            'D' => 'Varonil 35 a 39 años',
            'E' => 'Varonil 40 a 44 años',
            'F' => 'Varonil 45 a 49 años',
            'G' => 'Varonil 50 a 54 años',
            'H' => 'Varonil 55 a 59 años',
        );

        $form = $this->createFormBuilder($client)
            ->add('category',   'choice',   array('label' => 'Categoría', 'choices' => $categories))
            ->add('name',       'text',     array('label' => 'Nombre(s)'))
            ->add('lastName',   'text',     array('label' => 'Apellidos'))
            ->add('affiliationNumber', 'text', array('label' => 'Número de Afiliación (FMTRI)'))
            ->add('team',       'text',     array('label' => 'Equipo'))
            ->add('phoneNumber', 'number',  array('label' => 'Teléfono', 'required' => false))
            ->add('email',      'email',    array('label' => 'Email'))
            ->add('save',       'submit',   array('label' => 'Enviar'))
            ->getForm();

        $form->handleRequest($request);

        // Check that the required fields are filled in
        if ($form->isValid()) {
            // Generate a unique token for the client
            $sToken = md5(uniqid(mt_rand(), true));
            $client->setToken($sToken);

            // Save the client to the database
            $em = $this->getDoctrine()->getManager();

            $em->persist($client);
            $em->flush();

            // Prepare the confirmation link
            $link = $request->getHost() . $this->generateUrl('harcam_triatlon_signup_payment',
                    array('email' => $client->getEmail(), 'token' => $sToken));

            ///// Send an email to the client /////
            // Build mail body
            $mailTitle = "Inscripción Triatlón Tritanes";
            $mailBody = $this->get('templating')->render('HarcamTriatlonBundle:Email:signup.html.twig',
                array('client' => $client,
                      'link' => $link));

            // Get To and From
            $sender = $this->container->getParameter('mailer_user');
            $mailFrom = array($sender => 'Triatlón Tritanes');
            $mailTo = array($client->getEmail() => $client->getFullName());

            // Build message object
            $message = \Swift_Message::newInstance()
                ->setEncoder(\Swift_Encoding::get8BitEncoding()) // Disable Quoted-Printable headers (they mess up HTML)
                ->setSubject($mailTitle)
                ->setFrom($mailFrom)
                ->setBcc($mailTo)
                ->setBody($mailBody, 'text/html')
            ;

            // send the email
            $mailer = $this->get('mailer');
            $mailer->send($message);

            ///// Mail sent /////

            return $this->redirect($this->generateUrl('harcam_triatlon_signup_success'));
        } else {
            // If invalid, render the same form again..
            return $this->render('HarcamTriatlonBundle:Registration:form.html.twig',
                array('form' => $form->createView())
            );
        }
    }

    public function signupSuccessAction(Request $request)
    {
        return $this->render('HarcamTriatlonBundle:Registration:success.html.twig');
    }

    public function paymentAction($token)
    {
        // Validate the token
    }

    /**
     * Display the signup form
     *
     * @param Request $request
     * @return Response
     */
    public function signupAction(Request $request)
    {
        // Load the static page for signup
    }

    /**
     * Process the signup request
     *
     * First check if the email exists in the database, ask
     * for the password before continuing.
     *
     * @param Request $request
     * @return Response
     */
    public function signupProcessAction(Request $request)
    {

    }

    /**
     * Ask for a password in case the client already exists
     *
     * @param Request $request
     * @return Response
     */
    public function clientVerificationAction(Request $request)
    {

    }

    /**
     * Process the cient verification password
     *
     * @param Request $request
     * @return Response
     */
    public function clientVerificationProcessAction(Request $request)
    {

    }

    /**
     * Display a verification page and the PayPal checkout button
     *
     * Using PayPal's SetExpressCheckout generate the token and
     * prepare the link
     *
     * @param Request $request
     */
    public function verificationAction(Request $request)
    {

    }

    public function verificationProcessAction(Request $request)
    {
        
    }

}