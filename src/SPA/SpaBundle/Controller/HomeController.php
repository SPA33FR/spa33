<?php

namespace SPA\SpaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $article = new \SPA\SpaBundle\Entity\Articles();
        $articles = $article->getAllArticles($em);
        return $this->render('SPASpaBundle:Home:index.html.twig', array("articles" => $articles));
    }
}
