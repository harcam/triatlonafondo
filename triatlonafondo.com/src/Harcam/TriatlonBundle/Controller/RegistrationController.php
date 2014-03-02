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

        $distances = array(
            'SS' => 'Super Sprint'
        );

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
            ->add('distance', 'choice', array('label' => 'Distancia', 'choices' => $distances))
            ->add('category', 'choice', array('label' => 'Categoría', 'choices' => $categories))
            ->add('name', 'text', array('label' => 'Nombre(s)'))
            ->add('lastName', 'text', array('label' => 'Apellidos'))
            ->add('phoneNumber', 'number', array('label' => 'Teléfono', 'required' => false))
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

        $distances = array(
            'SS' => 'Super Sprint'
        );

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
            ->add('distance', 'choice', array('label' => 'Distancia', 'choices' => $distances))
            ->add('category', 'choice', array('label' => 'Categoría', 'choices' => $categories))
            ->add('name', 'text', array('label' => 'Nombre(s)'))
            ->add('lastName', 'text', array('label' => 'Apellidos'))
            ->add('phoneNumber', 'number', array('label' => 'Teléfono', 'required' => false))
            ->add('email', 'email', array('label' => 'Email'))
            ->add('save', 'submit', array('label' => 'Enviar'))
            ->getForm();

        $form->handleRequest($request);

        // Check that the required fields are filled in
        if ($form->isValid()) {
            // perform some action, such as saving the task to the database

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

}