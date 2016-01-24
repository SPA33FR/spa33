<?php

namespace Spa\SpaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $article = new \Spa\SpaBundle\Entity\Articles();
        $articles = $article->getAllArticles($em);
        return $this->render('SpaSpaBundle:Default:index.html.twig', array("articles" => $articles));
    }

}
