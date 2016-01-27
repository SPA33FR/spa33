<?php

namespace Spa\SpaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller {

    public function indexAction() {
        return $this->render('SpaSpaBundle:Admin:index.html.twig', array());
    }

    public function configurateArticlesAction() {
        $em = $this->getDoctrine()->getManager();
        $article = new \Spa\SpaBundle\Entity\Articles();
        $articles = $article->getAllArticles($em);
        return $this->render('SpaSpaBundle:Admin:articles.html.twig', array("articles" => $articles));
    }

}
