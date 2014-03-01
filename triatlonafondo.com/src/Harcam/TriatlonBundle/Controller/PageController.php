<?php

namespace Harcam\TriatlonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageController extends Controller
{
    public function homeAction()
    {
        return $this->render('HarcamTriatlonBundle:Page:home.html.twig');
    }

    public function pageAction($page)
    {
        switch($page)
        {
            case 'contact':
                $layout = 'HarcamTriatlonBundle:Page:contact.html.twig';
                break;
            case 'gallery':
                $layout = 'HarcamTriatlonBundle:Page:gallery.html.twig';
                break;
            default:
                $layout = 'HarcamTriatlonBundle:Page:home.html.twig';
                break;
        }

        return $this->render($layout);
    }

}