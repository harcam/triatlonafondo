<?php

namespace Harcam\TriatlonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageController extends Controller
{
    public function homeAction()
    {
        return $this->render();
    }

    public function pageAction($page)
    {
        switch($page)
        {
            case 'contact':
                $layout = "";
                break;
            case 'gallery':
                $layout = "";
                break;
            default:
                $layout = "";
                break;
        }

        return $this->render($layout);
    }

}