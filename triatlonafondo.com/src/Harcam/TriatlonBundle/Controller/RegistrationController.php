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
        return $this->render('HarcamTriatlonBundle:Registration:form.html.twig');
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
        if($data['name'] == "") {
            $error = 1;
        } elseif($data['lastName'] == "") {
            $error = 2;
        } elseif($data['email'] == "" || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $error = 3;
        } elseif($data['category'] == "") {
            $error = 4;
        } elseif($data['swimTime'] != "" && !$this->validateSwimTime($data['swimTime'])) {
            $error = 5;
        }

        if($error)
        {
            return $this->render('HarcamTriatlonBundle:Registration:form.html.twig',
                array('data' => $data, 'error' => $error));
        }

        // Form is ok.  Check if the email is unique.
        $client = $doctrine->getRepository('HarcamTriatlonBundle:Client')
            ->findOneBy( array('email' => $data['email']) );

        if($client)
        {
            // Error: client already exists
            // Throw a new page explaining the issue and instruct to contact the admin
            $msg = "El email introducido ya ha sido registrado en el sistema anteriormente.
                    Si necesitas editar tus datos o consultar el status de tu registro, favor
                    de escribir a <a class='email' href='mailto:tritanes@gmail.com'>tritanes@gmail.com</a>.";

            return $this->render('HarcamTriatlonBundle:Registration:error.html.twig',
                array('msg' => $msg));
        }

        // Email ok. Create a new client
        $client = new Client();
        $client->setName($data['name']);
        $client->setLastName($data['lastName']);
        $client->setEmail($data['email']);
        $client->setCategory($data['category']);
        $client->setTeam($data['team']);
        $client->setSwimTime( $this->swimTimeToSeconds($data['swimTime']) );
        $client->setPhoneNumber($data['phoneNumber']);

        // Save to the database
        $em->persist($client);
        $em->flush();

        // Send the email


        // Render as successful
        return $this->render('HarcamTriatlonBundle:Registration:confirm.html.twig',
            array('client' => $client));

    }

    /**
     * Confirm the client's information and send to PayPal
     *
     * @param Request $request
     * @return Response
     */
    public function confirmAction(Request $request)
    {

    }

    private function validateSwimTime($time)
    {
        $timeArray = explode(':', trim($time));

        if(count($timeArray) != 2) return false;

        if(!is_numeric($timeArray[0]) || !is_numeric($timeArray[1])) return false;

        if(intval($timeArray[1]) >= 60) return false;

        return true;
    }

    private function swimTimeToSeconds($time)
    {
        if($this->validateSwimTime($time))
        {
            $timeArray = explode(':', $time);
            return (intval($timeArray[0]) * 60) + intval($timeArray[1]);
        } else {
            return null;
        }
    }

}