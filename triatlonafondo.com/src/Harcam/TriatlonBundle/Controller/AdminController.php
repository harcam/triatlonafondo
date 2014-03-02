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
        $device = $this->getDoctrine()->getRepository('HarcamTriatlonBundle:Client')->find($id);

        if (!$device) {
            throw $this->createNotFoundException(
                'No device found for id ' . $id
            );
        }

        // Remove from database
        $em = $this->getDoctrine()->getManager();
        $em->remove( $device );
        $em->flush();

        return $this->redirect($this->generateUrl('slambo_device_list'));
    }


}