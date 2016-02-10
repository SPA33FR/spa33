<?php

namespace Spa\SpaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller {

    public function indexAction() {
        return $this->render('SpaSpaBundle:Admin:index.html.twig', array());
    }

    public function configurateArticlesAction() {
//        $task = new \Spa\SpaBundle\Entity\Articles();
//        $articlesForm = $this->createForm(\Spa\SpaBundle\Form\ArticlesType::class);
//        $em = $this->getDoctrine()->getManager();
//        $article = new \Spa\SpaBundle\Entity\Articles();
//        $articles = $article->getAllArticles($em);
//        return $this->render('SpaSpaBundle:Admin:articles.html.twig', array("articles" => $articles));
//        $em = $this->getDoctrine()->getManager();
//        $tag = new \Spa\SpaBundle\Entity\Tags();
//        $tags = $tag->getAllTags($em);
//        return $this->render('SpaSpaBundle:Admin:articles.html.twig', array("tags" => $tags));
//        $request = $this->get('request');
        $article = new \Spa\SpaBundle\Entity\Articles();

        $form = $this->createForm(new \Spa\SpaBundle\Form\ArticlesType(), $article);
        return $this->render('SpaSpaBundle:Admin:articles.html.twig', array("form" => $form->createView(), "blibli" => ''));
    }

    public function addArticlesAction() {
        $request = $this->get('request');
        $article = new \Spa\SpaBundle\Entity\Articles();

        $form = $this->createForm(new \Spa\SpaBundle\Form\ArticlesType(), $article);
        if ('POST' == $request->getMethod()) {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $post = $form->getData();
                
            }
        }
        return $this->render('SpaSpaBundle:Admin:articles.html.twig', array("blibli" => $post));
    }

}
