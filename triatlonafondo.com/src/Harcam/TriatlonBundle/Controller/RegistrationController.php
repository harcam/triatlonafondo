<?php

namespace Harcam\TriatlonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Harcam\TriatlonBundle\Entity\Client;

class RegistrationController extends Controller
{
    /**
     * Display the signup form
     *
     * @param Request $request
     * @return Response
     */
    public function signupAction(Request $request)
    {
        return $this->render('HarcamTriatlonBundle:Registration:form.html.twig',
            array('mode' => 'edit'));
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
        // Initialize doctrine
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        // Load Data from the form

        // Check for errors

        if($error)
        {
            return $this->render('HarcamTriatlonBundle:Registration:form.html.twig',
                array('data' => $data, 'error' => $error, 'mode' => 'edit'));
        }

        // Form is ok.  Check if the email is unique.
        $client = $doctrine->getRepository('HarcarmTriatlonBundle:Client')
            ->findOneBy( array('email' => $data['email']) );

        if($client)
        {
            // Error: client already exists
            // Throw a new page explaining the issue and instruct to contact the admin

            // TODO
        }


        // Email ok. Create a new client
        $client = new Client();


        // Save to the database

        // Render as successful
        return $this->render('HarcamTriatlonBundle:Registration:form.html.twig',
            array('client' => $client, 'mode' => 'view'));

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