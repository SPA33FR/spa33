<?php

namespace Spa\SpaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller {

    public function indexAction() {
        return $this->render('SpaSpaBundle:Admin:index.html.twig', array());
    }

    public function configurateArticlesAction() {

        $article = new \Spa\SpaBundle\Entity\Articles();
        $form = $this->createForm(new \Spa\SpaBundle\Form\ArticlesType(), $article);
        return $this->render('SpaSpaBundle:Admin:articles.html.twig', array("form" => $form->createView()));
    }

    public function addArticlesAction() {
        // On récupère l'objet request via le service container
        $request = $this->get('request');
        
        
        
//        // On créé notre objet Articles vierge
        $article = new \Spa\SpaBundle\Entity\Articles();
//        // On bind l'objet Articles à notre formulaire ArticlesType
//        $form = $this->get('form.factory')->create(new \Spa\SpaBundle\Form\ArticlesType(), $article);
//        
        $form = $this->get('form.factory')->create(new \Spa\SpaBundle\Form\ArticlesType(), $article);
        // Si on a posté le formulaire
        if ('POST' == $request->getMethod()) {
            var_dump($request->request->All());
            
            // On bind les données du form
            $form->handleRequest($request);
            // Si le formulaire est valide
            if ($form->isSubmitted() && $form->isValid()) {
                $article->setPublishdate(new \DateTime());
                $article->setModifdate(new \DateTime());
                $em = $this->getDoctrine()->getManager();
                $article->uploadProfilePicture($em);
                $em->persist($article);
                $em->flush();
            }
        }
        return $this->render('SpaSpaBundle:Admin:articles.html.twig', array("form" => $form->createView()));
    }

}
