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
        $data = array();
        $data['name'] = $request->request->get('name');
        $data['lastName'] = $request->request->get('lastName');
        $data['email'] = $request->request->get('email');
        $data['category'] = $request->request->get('category');
        $data['team'] = $request->request->get('team');
        $data['swimTime'] = $request->request->get('swimTime');
        $data['phoneNumber'] = $request->request->get('phoneNumber');

        // Check for errors
        $error = false;
        if(empty($data['name'])) {
            $error = 1;
        } elseif(empty($data['lastName'])) {
            $error = 2;
        } elseif(empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $error = 3;
        } elseif(empty($data['category'])) {
            $error = 4;
        } elseif(!$this->validateSwimTime($data['swimTime'])) {
            $error = 5;
        }

        if($error)
        {
            return $this->render('HarcamTriatlonBundle:Registration:form.html.twig',
                array('data' => $data, 'error' => $error, 'mode' => 'edit'));
        }

        // Form is ok.  Check if the email is unique.
        $client = $doctrine->getRepository('HarcarmTriatlon:Client')
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

    private function validateSwimTime($time)
    {
        $timeArray = explode(':', $time);

        if(count($timeArray) != 2) return false;

        if(!is_integer($timeArray[0]) || !is_integer($timeArray[1])) return false;

        if(intval($timeArray[1]) >= 60) return false;

        return true;
    }

    private function swimTimeToSeconds($time)
    {
        $timeArray = explode(':', $time);

        return (intval($timeArray[0]) * 60) + intval($timeArray[1]);
    }

}