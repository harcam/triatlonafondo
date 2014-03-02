<?php

namespace Harcam\TriatlonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Harcam\TriatlonBundle\Entity\Client;

class AdminClientController extends Controller
{
    public function viewAction($id)
    {
        // Query the server for the client's information and parameters
        $client = $this->getDoctrine()->getRepository('HarcamTriatlonBundle:Client')->find($id);

        if (!$client) {
            throw $this->createNotFoundException(
                'No client found for id ' . $id
            );
        }

        return $this->render('NivaSLamboBundle:Device:detail.html.twig',
            array('client' => $client
            ));
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

        return $this->redirect($this->generateUrl('harcam_triatlon_admin_client_list'));
    }
}