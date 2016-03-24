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
                $fileName = str_replace(' ', '_', $files[$i]);


                while (file_exists($old_article->getUploadRootDir() . '/' . $fileName)) {
                    $match = '';
                    if ($fileName == str_replace(' ', '_', $files[$i])) {
                        $fileName = preg_replace('/(.+)\./', "$1(1).", $fileName);
                    } else {
                        preg_match("/\((\d+)\)\.\w+/", $fileName, $match);
                        $nextNumber = intval($match[1]) + 1;
                        $fileName = preg_replace("/((.+)\()\d+(\)\.\w+)/", '${1}' . $nextNumber . '$3', $fileName);
                    }
                }
                $querySelectImages .= '\'' . $fileName . '\'';
                $queryImages .= '(\'' . $fileName . '\')';
                if ($i != count($files) - 1) {
                    $queryImages .= ',';
                    $querySelectImages .= ',';
                }
                move_uploaded_file($_FILES["Articles"]["tmp_name"]["file"][$i], __DIR__ . "/../../../../web/uploads/articles/pictures/" . $fileName);
            }

            $em->getConnection()->executeUpdate($queryImages);
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

        return $this->render('SpaSpaBundle:Admin:modifRaces.html.twig', array("race" => $race));
    }

    public function savemodifRacesAction() {
        $request = $this->get('request');
        if ($request->getMethod() == "POST") {
            $race = $_POST["Races"];
            $em = $this->getDoctrine()->getManager();

            $em->getConnection()->executeUpdate("UPDATE races "
                    . "SET name = ? "
                    . "WHERE idraces = ?", array($race["name"], $race["idraces"]));
            
            return $this->redirectToRoute("spa_spa_admin_allraces");
        }
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
    
    public function allTagsAction () {
        $em = $this->getDoctrine()->getManager();
        $tags = $em->getRepository('SpaSpaBundle:Tags')->findAll();
        return $this->render('SpaSpaBundle:Admin:allTags.html.twig', array("tags" => $tags));
    }
    
    public function modifTagsAction($id) {
        $em = $this->getDoctrine()->getManager();
        $tag = $em->getRepository('SpaSpaBundle:Tags')->find($id);
        
        return $this->render('SpaSpaBundle:Admin:modifTags.html.twig', array("tag" => $tag));
    }
    
    public function savemodifTagsAction() {
        $request = $this->get('request');
        if($request->getMethod() == "POST") {
            $tag = $_POST["Tags"];
            $em = $this->getDoctrine()->getManager();
            $em->getConnection()->executeUpdate("UPDATE tags "
                    . "SET name = ? "
                    . "WHERE idTags = ?", array($tag["name"], $tag["idTags"]));
            
            return $this->redirectToRoute("spa_spa_admin_alltags");
        }
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
                return $this->redirectToRoute('spa_spa_admin_allstaff');
            }
        }
        return $this->render('SpaSpaBundle:Admin:staff.html.twig', array("form" => $form->createView()));
    }

    public function allStaffAction() {
        $em = $this->getDoctrine()->getManager();
        $staffs = $em->getRepository('SpaSpaBundle:Staff')->findAll();
        return $this->render('SpaSpaBundle:Admin:allStaff.html.twig', array("staffs" => $staffs));
    }

    public function modifStaffAction($id) {
        $em = $this->getDoctrine()->getManager();
        $staff = $em->getRepository('SpaSpaBundle:Staff')->find($id);
        $img = null;
        if ($staff->getImagesimages() != null) {
            $img = "../../../../../web/uploads/staff/pictures/" . $staff->getImagesimages()->getUrl();
        }

        return $this->render('SpaSpaBundle:Admin:modifStaff.html.twig', array("staff" => $staff, 'img' => $img));
    }

    public function savemodifStaffAction() {
        $request = $this->get('request');
        if ($request->getMethod() == "POST") {
            $staff = $_POST["Staff"];

            $em = $this->getDoctrine()->getManager();

            $old_staff = $em->getRepository('SpaSpaBundle:Staff')->find($staff["idstaff"]);

            $files = $_FILES["Staff"]["name"]["file"];

            $fileName = str_replace(' ', '_', $files);

            while (file_exists($old_staff->getUploadRootDir() . '/' . $fileName)) {
                $match = '';
                if ($fileName == str_replace(' ', '_', $files)) {
                    $fileName = preg_replace('/(.+)\./', "$1(1).", $fileName);
                } else {
                    preg_match("/\((\d+)\)\.\w+/", $fileName, $match);
                    $nextNumber = intval($match[1]) + 1;
                    $fileName = preg_replace("/((.+)\()\d+(\)\.\w+)/", '${1}' . $nextNumber . '$3', $fileName);
                }
            }
            unlink($old_staff->getUploadRootDir() . '/' . $old_staff->getImagesimages()->getUrl());
            move_uploaded_file($_FILES["Staff"]["tmp_name"]["file"], __DIR__ . "/../../../../web/uploads/staff/pictures/" . $fileName);

            $em->getConnection()->executeUpdate('INSERT INTO images (url) VALUES (\'' . $fileName . '\')');

            $sql_img = $em->getConnection()->prepare("SELECT idImages FROM images WHERE url LIKE '" . $fileName . "'");
            $sql_img->execute();
            $idImage = $sql_img->fetchAll();
            $em->getConnection()->executeUpdate('UPDATE staff '
                    . 'SET firstName = ?, '
                    . 'lastName = ?, '
                    . 'sex = ?, '
                    . 'role = ?, '
                    . 'Images_idImages = ?'
                    . 'WHERE idStaff = ?', array($staff["firstname"], $staff["lastname"], $staff["sex"],
                $staff["role"], $idImage[0]["idImages"], $staff["idstaff"]));

            return $this->redirectToRoute('spa_spa_admin_allstaff');
        }
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
