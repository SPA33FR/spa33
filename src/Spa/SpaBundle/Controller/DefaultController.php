<?php

namespace Spa\SpaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

    public function indexAction() {
        
        return $this->render('SpaSpaBundle:Default:index.html.twig', array());
    }

}
