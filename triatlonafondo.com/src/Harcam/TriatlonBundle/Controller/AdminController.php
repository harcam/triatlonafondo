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

    public function registerPaymentAction(Request $request, $id, $hasPayed)
    {
        // Override the ID on the URL?
        $id = $request->request->get('clientId');
        // Query the server for the club's information and parameters
        $client = $this->getDoctrine()->getRepository('HarcamTriatlonBundle:Client')->find($id);
        
        $hasPayed = $this->getDoctrine()->getRepository('HarcamTriatlonBundle:Client')->find($hasPayed);
        
        if ($hasPayed == "false"){
            $paymentOverride = "true";
        }
        else {
            $paymentOverride = "false";
        }
        if (!$client) {
            throw $this->createNotFoundException(
                'No client found for id ' . $id
            );
        }

        // Change Payment Status
        $paymentOverride = $this->set('hasPayed');
        $em->flush();

        return $this->redirect($this->generateUrl('harcam_triatlon_admin_client_list'));
    }
}