<?php

namespace Spa\SpaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\Query\ResultSetMapping;

class AdminController extends Controller {

    public function indexAction() {
        return $this->render('SpaSpaBundle:Admin:index.html.twig', array());
    }

    public function configurateArticlesAction() {

        $article = new \Spa\SpaBundle\Entity\Articles();
        $form = $this->createForm(new \Spa\SpaBundle\Form\ArticlesType(), $article, array('action' => $this->generateUrl('spa_spa_admin_articles')));

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
                return $this->redirectToRoute('spa_spa_admin_allarticles');
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
        $tags = $em->getRepository('SpaSpaBundle:Tags')->findAll();
        $imgs = null;
        if ($article->getImagesimages() != null) {
            for ($i = 0; $i < count($article->getImagesimages()); $i++) {
                $imgs[$i] = "../../../../../web/uploads/articles/pictures/" . $article->getImagesimages()[$i]->getUrl();
            }
        }

        return $this->render('SpaSpaBundle:Admin:modifArticles.html.twig', array("article" => $article, 'imgs' => $imgs, 'tags' => $tags));
    }

    public function savemodifArticlesAction() {
        $request = $this->get('request');
        if ($request->getMethod() == "POST") {
            $article = $_POST["Articles"];

            $em = $this->getDoctrine()->getManager();

            $old_article = $em->getRepository('SpaSpaBundle:Articles')->find($article["idarticles"]);

            $new_date = new \DateTime();
            $em->getConnection()->executeUpdate('UPDATE articles '
                    . 'SET title = ?, '
                    . 'subtitle = ?, '
                    . 'content = ?, '
                    . 'modifdate = ?, '
                    . 'investigation = ? '
                    . 'WHERE idarticles = ?', array($article["title"], $article["subtitle"], $article["content"],
                $new_date->format('Y-m-d'), array_key_exists("investigation", $article), $article["idarticles"]));

            //Suppression du link Articles -> Tags
            $em->getConnection()->executeUpdate('DELETE FROM articles_has_tags WHERE articles_idarticles = ' . $article["idarticles"]);

            // Insertion des nouveaux link Articles -> Tags
            if (count($article["tagstags"]) > 0) {
                $queryArticles_tags = 'INSERT INTO articles_has_tags VALUES ';
                for ($i = 0; $i < count($article["tagstags"]); $i++) {
                    $queryArticles_tags .= '(' . $article["idarticles"] . ', ' . $article["tagstags"][$i] . ')';
                    if ($i != count($article["tagstags"]) - 1) {
                        $queryArticles_tags .= ',';
                    }
                }
            }
            $em->getConnection()->executeUpdate($queryArticles_tags);

            // Suppression du link Articles -> Images
            $em->getConnection()->executeUpdate('DELETE FROM articles_has_images WHERE articles_idarticles = ' . $article["idarticles"]);
            $old_images = $old_article->getImagesimages();
            // Suppression des images stockées
            for ($i = 0; $i < count($old_images); $i++) {
                unlink($old_article->getUploadRootDir() . $old_images[$i]->getUrl());
            }

            // Insertion des nouveaux link Articles -> Images
            $queryArticles_images = 'INSERT INTO articles_has_images VALUES ';
            $queryImages = 'INSERT INTO images (url) VALUES ';
            $files = $_FILES["Articles"]["name"]["file"];
            // Sauvegarde des nouveaux fichiers images

            $querySelectImages = '';
            for ($i = 0; $i < count($files); $i++) {
                $fileName = $files[$i];


                while (file_exists($old_article->getUploadRootDir() . '/' . $fileName)) {
                    $match = '';
                    if ($fileName == $files[$i]) {
                        $fileName = preg_replace('/(.+)\./', "$1(1).", $fileName);
                    } else {
                        preg_match("/\((\d+)\)\.\w+/", $fileName, $match);
                        $nextNumber = intval($match[1]) + 1;
                        $fileName = preg_replace("/((.+)\()\d+(\)\.\w+)/", '${1}' . $nextNumber . '$3', $fileName);
                    }
                }
                echo $fileName . '<br>';
                $querySelectImages .= '\'' . $fileName . '\'';
                $queryImages .= '(\'' . $fileName . '\')';
                if ($i != count($files) - 1) {
                    $queryImages .= ',';
                    $querySelectImages .= ',';
                }
                move_uploaded_file($_FILES["Articles"]["tmp_name"]["file"][$i], __DIR__ . "/../../../../web/uploads/articles/pictures/" . $fileName);
            }

            $em->getConnection()->executeUpdate($queryImages);
            echo "SELECT idImages FROM images WHERE url in (" . $querySelectImages . ")";
            $sql_imgs = $em->getConnection()->prepare("SELECT idImages FROM images WHERE url IN (" . $querySelectImages . ")");
            $sql_imgs->execute();
            $idImages = $sql_imgs->fetchAll();

            for ($i = 0; $i < count($idImages); $i++) {
                $queryArticles_images .= '(' . $article["idarticles"] . ',' . $idImages[$i]["idImages"] . ')';
                if ($i != count($idImages) - 1) {
                    $queryArticles_images .= ',';
                }
            }
            $em->getConnection()->executeUpdate($queryArticles_images);

            return $this->redirectToRoute('spa_spa_admin_allarticles');
        }
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
