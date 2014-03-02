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
        // Initialize Client object to generate a form
        $client = new Client();

        $form = $this->createFormBuilder($client)
            ->add('name', 'text', array('label' => 'Nombre(s)'))
            ->add('lastName', 'text', array('label' => 'Apellidos'))
            ->add('email', 'email', array('label' => 'Email'))
            ->add('save', 'submit', array('label' => 'Enviar'))
            ->getForm();

        return $this->render('HarcamTriatlonBundle:Registration:form.html.twig',
                             array('form' => $form->createView())
        );
    }

    public function signupProcessAction(Request $request)
    {
        // Initialize Client object to handle the form
        $client = new Client();

        $form = $this->createFormBuilder($client)
            ->add('name', 'text', array('label' => 'Nombre(s)'))
            ->add('lastName', 'text', array('label' => 'Apellidos'))
            ->add('email', 'email', array('label' => 'Email'))
            ->add('save', 'submit', array('label' => 'Enviar'))
            ->getForm();

        $form->handleRequest($request);

        // Check that the required fields are filled in
        if ($form->isValid()) {
            // perform some action, such as saving the task to the database

            return $this->redirect($this->generateUrl('task_success'));
        } else {
            // If invalid, render the same form again..
            return $this->render('HarcamTriatlonBundle:Registration:form.html.twig',
                array('form' => $form->createView())
            );
        }
    }

    public function signupSuccessAction(Request $request)
    {

    }

}