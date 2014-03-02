<?php

namespace Harcam\TriatlonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Harcam\TriatlonBundle\Entity\Client;

class AdminController extends Controller
{
    public function loginAction()
    {

    }

    public function loginProcessAction()
    {

    }

    public function logout()
    {

    }

    public function viewClientsAction()
    {
        // Query the server for the device's information and parameters
        $clients = $this->getDoctrine()->getRepository('HarcamTriatlonBundle:Client')->findAll();

        return $this->render('HarcamTriatlonBundle:Admin:list.html.twig',
            array('clients' => $clients
            ));
    }

    public function filterClientsAction()
    {

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

}