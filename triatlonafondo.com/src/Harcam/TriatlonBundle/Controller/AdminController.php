<?php

namespace Harcam\TriatlonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Harcam\TriatlonBundle\Entity\Client;

class AdminController extends Controller
{
    public function viewClientsAction()
    {
        // Query the server for the device's information and parameters
        $clients = $this->getDoctrine()->getRepository('HarcamTriatlonBundle:Client')->findAll();

        return $this->render('HarcamTriatlonBundle:Admin:list.html.twig',
            array('clients' => $clients
            ));
    }

    public function filterClientsAction($mode)
    {
        $mode = 'payed';

        # TOTAL ACTIVE USERS
        $repository = $this->getDoctrine()->getRepository('HarcamTriatlonBundle:Client');
        $query = $repository->createQueryBuilder('c')
            ->where('c.hasPayed = 1')
            ->getQuery();
        $clients = $query->getResult();

        return $this->render('HarcamTriatlonBundle:Admin:list.html.twig',
            array('clients' => $clients
            ));

    }

    public function deleteAction(Request $request, $id)
    {
        // Override the ID on the URL?
        $id = $request->request->get('clientId');
        // Query the server for the club's information and parameters
        $client = $this->getDoctrine()->getRepository('HarcamTriatlonBundle:Client')->find($id);

        if (!$client) {
            throw $this->createNotFoundException(
                'No client found for id ' . $id
            );
        }

        // Remove from database
        $em = $this->getDoctrine()->getManager();
        $em->remove( $client );
        $em->flush();

        return $this->redirect($this->generateUrl('harcam_triatlon_admin_client_list'));
    }
    
    public function viewAction(Request $request, $id)
    {
        /* @var Client $client */
        $client = $this->getDoctrine()->getRepository('HarcamTriatlonBundle:Client')->find($id);

        if(!$client)
        {
            throw $this->createNotFoundException(
                'No client found for id: ' . $id
            );
        }

        return $this->render('HarcamTriatlonBundle:Admin:detail.html.twig',
            array('client' => $client, 'mode' => 'view'
        ));
    }

    public function editAction($id)
    {
        /* @var Client $client */
        $client = $this->getDoctrine()->getRepository('HarcamTriatlonBundle:Client')->find($id);

        if(!$client)
        {
            throw $this->createNotFoundException(
                'No client found for id: ' . $id
            );
        }

        return $this->render('HarcamTriatlonBundle:Admin:detail.html.twig',
            array('client' => $client, 'mode' => 'edit'
        ));
    }

    public function editProcessAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        /* @var Client $client */
        $client = $this->getDoctrine()->getRepository('HarcamTriatlonBundle:Client')->find($id);

        if(!$client)
        {
            throw $this->createNotFoundException(
                'No client found for id: ' . $id
            );
        }

        $client->setName( $request->request->get('name') );
        $client->setLastName( $request->request->get('lastName') );
        $client->setCategory( $request->request->get('category') );
        $client->setTeam( $request->request->get('team') );
        $client->setPhoneNumber( $request->request->get('phoneNumber') );
        $client->setEmail( $request->request->get('email') );
        $client->setSwimTime( $request->request->get('swimTime') );
        $client->setBirthDate( $request->request->get('birthDate') );

        $em->persist($client);
        $em->flush($client);

        $msg = "Updated succesfully!";

        return $this->render('HarcamTriatlonBundle:Admin:detail.html.twig',
            array('client' => $client, 'mode' => 'view', 'msg' => $msg
            ));

    }

    public function registerPaymentAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        // Override the ID on the URL?
        $id = $request->request->get('clientId');
        // Query the server for the club's information and parameters
        /* @var Client $client */
        $client = $this->getDoctrine()->getRepository('HarcamTriatlonBundle:Client')->find($id);

        if (!$client)
        {
            throw $this->createNotFoundException(
                'No client found for id ' . $id
            );
        }

        $client->setHasPayed(true);
        $client->setPaymentReference( $request->request->get('paymentReference') );

        $em->flush();

        // Send Email
        // Send the email
        $message = \Swift_Message::newInstance()
            ->setSubject('Registro Tritanes')
            ->setFrom( $this->container->getParameter('mailer_user') )
            ->setTo($client->getEmail())
            ->setBody(
                $this->renderView(
                    'HarcamTriatlonBundle:Email:confirmation.html.twig',
                    array('client' => $client)
                ), 'text/html');

        $this->get('mailer')->send($message);


        $msg = "Registered PayPal payment <strong>" . $request->request->get('paymentReference') . "</strong> succesfully!";

        return $this->render('HarcamTriatlonBundle:Admin:detail.html.twig',
            array('client' => $client, 'mode' => 'view', 'msg' => $msg
            ));
    }
}