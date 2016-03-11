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

        $request = $this->get('request');

        if ('POST' == $request->getMethod()) {
// On bind les données du form
            $form->handleRequest($request);
// Si le formulaire est valide
            if ($form->isSubmitted() && $form->isValid()) {
                $article->setPublishdate(new \DateTime());
                $article->setModifdate(new \DateTime());
                $em = $this->getDoctrine()->getManager();
                $article->uploadPicture($em);
                $em->persist($article);
                $em->flush();
            }
        }
        return $this->render('SpaSpaBundle:Admin:articles.html.twig', array("form" => $form->createView()));
    }

    public function allArticlesAction() {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('SpaSpaBundle:Articles')->findAll();
        return $this->render('SpaSpaBundle:Admin:allArticles.html.twig', array("articles" => $articles));
    }
    
    public function modifArticlesAction($id) {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('SpaSpaBundle:Articles')->find($id);
        $form = $this->createForm(new \Spa\SpaBundle\Form\ArticlesType(), $article);
        
         $request = $this->get('request');

        if ('POST' == $request->getMethod()) {
             $form->handleRequest($request);
             $article->setModifdate(new \DateTime());
             $em = $this->getDoctrine()->getManager();
             // TODO Supprimer l'ancienne image si elle a été modifiée
             $em->merge($article);
             $em->flush();
        }
        return $this->render('SpaSpaBundle:Admin:articles.html.twig', array("form" => $form->createView()));
    }

    public function configurateRacesAction() {

        $race = new \Spa\SpaBundle\Entity\Races();
        $form = $this->createForm(new \Spa\SpaBundle\Form\RacesType(), $race);

        $request = $this->get('request');

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($race);
                $em->flush();
            }
        }
        return $this->render('SpaSpaBundle:Admin:races.html.twig', array("form" => $form->createView()));
    }
    
        public function allRacesAction() {
        $em = $this->getDoctrine()->getManager();
        $races = $em->getRepository('SpaSpaBundle:Races')->findAll();
        return $this->render('SpaSpaBundle:Admin:allRaces.html.twig', array("races" => $races));
    }
    
    public function modifRacesAction($id) {
        $em = $this->getDoctrine()->getManager();
        $race = $em->getRepository('SpaSpaBundle:Races')->find($id);
        $form = $this->createForm(new \Spa\SpaBundle\Form\RacesType(), $race);
        
         $request = $this->get('request');

        if ('POST' == $request->getMethod()) {
             $form->handleRequest($request);
             // TODO Modifier l'entité en DB
             $em->flush();
        }
        return $this->render('SpaSpaBundle:Admin:races.html.twig', array("form" => $form->createView()));
    }

    public function configurateTagsAction() {

        $tag = new \Spa\SpaBundle\Entity\Tags();
        $form = $this->createForm(new \Spa\SpaBundle\Form\TagsType(), $tag);

        $request = $this->get('request');

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($tag);
                $em->flush();
            }
        }
        return $this->render('SpaSpaBundle:Admin:tags.html.twig', array("form" => $form->createView()));
    }

    public function configurateStaffAction() {
        $staff = new \Spa\SpaBundle\Entity\Staff();
        $form = $this->createForm(new \Spa\SpaBundle\Form\StaffType(), $staff);

        $request = $this->get('request');
        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $staff->uploadPicture($em);
                $em->persist($staff);
                $em->flush();
            }
        }
        return $this->render('SpaSpaBundle:Admin:staff.html.twig', array("form" => $form->createView()));
    }

    public function configuratePetsAction() {
        $pets = new \Spa\SpaBundle\Entity\Pets();
        $form = $this->createForm(new \Spa\SpaBundle\Form\PetsType(), $pets);
        $request = $this->get('request');
        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $pets->uploadPicture($em);
                $pets->uploadVideo($em);
                $em->persist($pets);
                $em->flush();
            }
        }
        return $this->render('SpaSpaBundle:Admin:pets.html.twig', array("form" => $form->createView()));
    }

}
