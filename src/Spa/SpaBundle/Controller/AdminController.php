<?php

namespace Spa\SpaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
     public function indexAction() {
         return $this->render('SpaSpaBundle:Admin:index.html.twig', array());
     } 
}
