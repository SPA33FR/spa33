<?php

namespace Spa\SpaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

    public function deleteArticlesAction($id) {
        $em = $this->getDoctrine()->getmanager();
        $article = $em->getRepository('SpaSpaBundle:Articles')->find($id);
        $em->remove($article);
        $em->flush();
        $sql_imgs = $em->getConnection()->prepare("SELECT Images_idImages FROM articles_has_images WHERE articles_idarticles LIKE '" . $id . "'");
        $sql_imgs->execute();
        $idImages = $sql_imgs->fetchAll();
        if (count($idImages) != 0) {
            $sql_del_imgs = $em->getConnection()->prepare("DELETE FROM images WHERE idImages IN (" . implode(',', $idImages) . ")");
            $sql_del_imgs->execute();
        }
        $sql_has_imgs = $em->getConnection()->prepare("DELETE FROM articles_has_images WHERE articles_idarticles LIKE '" . $id . "'");
        $sql_has_imgs->execute();
        for ($i = 0; $i < count($article->getImagesimages()); $i++) {
            unlink(__DIR__ . "/../../../../web/uploads/articles/pictures/" . $article->getImagesimages()[$i]->getUrl());
        }

        return $this->redirectToRoute('spa_spa_admin_allarticles');
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

    public function deleteRacesAction($id) {
        $em = $this->getDoctrine()->getManager();
        $race = $em->getRepository('SpaSpaBundle:Races')->find($id);
        $em->remove($race);
        $em->flush();
        return $this->redirectToRoute('spa_spa_admin_allraces');
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

    public function allTagsAction() {
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
        if ($request->getMethod() == "POST") {
            $tag = $_POST["Tags"];
            $em = $this->getDoctrine()->getManager();
            $em->getConnection()->executeUpdate("UPDATE tags "
                    . "SET name = ? "
                    . "WHERE idTags = ?", array($tag["name"], $tag["idTags"]));

            return $this->redirectToRoute("spa_spa_admin_alltags");
        }
    }

    public function deleteTagsAction($id) {
        $em = $this->getDoctrine()->getManager();
        $tag = $em->getRepository('SpaSpaBundle:Tags')->find($id);
        $em->remove($tag);
        $em->flush();

        return $this->redirectToRoute('spa_spa_admin_alltags');
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

    public function deleteStaffAction($id) {
        $em = $this->getDoctrine()->getManager();
        $staff = $em->getRepository('SpaSpaBundle:Staff')->find($id);
        $img = $em->getRepository('SpaSpaBundle:Images')->find($staff->getImagesimages()->getIdimages());
        unlink(__DIR__ . "/../../../../web/uploads/staff/pictures/" . $staff->getImagesimages()->getUrl());
        $em->remove($img);
        $em->remove($staff);
        $em->flush();

        return $this->redirectToRoute('spa_spa_admin_allstaff');
    }

    public function configuratePetsAction() {
        $pets = new \Spa\SpaBundle\Entity\Pets();
        $form = $this->createForm(new \Spa\SpaBundle\Form\PetsType(), $pets);
        $request = $this->get('request');
        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            // TODO Prendre en compte le fait d'être vétéran
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $date = new \DateTime();
                $pets->setArrivaldate($date->setTimestamp($pets->getArrivaldate()));
                $datebirth = new \DateTime();
                $pets->setBirthdate($datebirth->setTimestamp($pets->getBirthdate()));
                $pets->uploadPicture($em);
                $pets->uploadVideo($em);
                $em->persist($pets);
                $em->flush();
                return $this->redirectToRoute('spa_spa_admin_allpets');
            }
        }
        return $this->render('SpaSpaBundle:Admin:pets.html.twig', array("form" => $form->createView()));
    }

    public function allPetsAction() {
        $em = $this->getDoctrine()->getManager();
        $pets = $em->getRepository('SpaSpaBundle:Pets')->findAll();
        return $this->render('SpaSpaBundle:Admin:allPets.html.twig', array("pets" => $pets));
    }

    public function modifPetsAction($id) {
        $em = $this->getDoctrine()->getManager();
        $pet = $em->getRepository('SpaSpaBundle:Pets')->find($id);
        $races = $em->getRepository('SpaSpaBundle:Races')->findAll();
        $imgs = null;
        if ($pet->getImagesimages() != null) {
            for ($i = 0; $i < count($pet->getImagesimages()); $i++) {
                $imgs[$i] = "../../../../../web/uploads/pets/pictures/" . $pet->getImagesimages()[$i]->getUrl();
            }
        }
        $videos = null;
        if ($pet->getVideosvideos() != null) {
            for ($i = 0; $i < count($pet->getVideosvideos()); $i++) {
                $videos[$i] = "../../../../../web/uploads/pets/videos/" . $pet->getVideosVideos()[$i]->getUrl();
            }
        }

        return $this->render('SpaSpaBundle:Admin:modifPets.html.twig', array("pet" => $pet, 'imgs' => $imgs, 'videos' => $videos, 'races' => $races));
    }

    public function savemodifPetsAction() {
        $request = $this->get('request');
        if ($request->getMethod() == "POST") {
            $pet = $_POST["pets"];
            $filesPicture = $_FILES["pets"]["name"]["filePicture"];
            $filesVideo = $_FILES["pets"]["name"]["fileVideo"];

            $em = $this->getDoctrine()->getManager();

            $old_pet = $em->getRepository('SpaSpaBundle:Pets')->find($pet["idpets"]);

            // Suppression des anciennes images
            $sql_delete_pets_has_imgs = $em->getConnection()->prepare('DELETE FROM pets_has_images WHERE Pets_idPets LIKE \'' . $pet["idpets"] . '\'');
            $sql_delete_pets_has_imgs->execute();
            for ($i = 0; $i < count($old_pet->getImagesimages()); $i++) {
                unlink($old_pet->getUploadRootPictureDir() . '/' . $old_pet->getImagesimages()[$i]->getUrl());
                $sql_delete_imgs = $em->getConnection()->prepare('DELETE FROM images WHERE url LIKE \'' . $old_pet->getImagesimages()[$i]->getUrl() . '\'');
                $sql_delete_imgs->execute();
            }

            // Suppression des anciennes vidéos
            $sql_delete_pets_has_videos = $em->getConnection()->prepare('DELETE FROM pets_has_videos WHERE Pets_idPets LIKE \'' . $pet["idpets"] . '\'');
            $sql_delete_pets_has_videos->execute();
            for ($i = 0; $i < count($old_pet->getVideosvideos()); $i++) {
                unlink($old_pet->getUploadRootVideoDir() . '/' . $old_pet->getVideosvideos()[$i]->getUrl());
                $sql_delete_videos = $em->getConnection()->prepare('DELETE FROM videos WHERE url LIKE \'' . $old_pet->getVideosvideos()[$i]->getUrl() . '\'');
                $sql_delete_videos->execute();
            }


            // Sauvegarde des nouvelles images
            $querySelectImages = '';
            for ($i = 0; $i < count($filesPicture); $i++) {
                $fileName = str_replace(' ', '_', $filesPicture[$i]);
                while (file_exists($old_pet->getUploadRootPictureDir() . '/' . $fileName)) {
                    $match = '';
                    if ($fileName == str_replace(' ', '_', $filesPicture[$i])) {
                        $fileName = preg_replace('/(.+)\./', "$1(1).", $fileName);
                    } else {
                        preg_match("/\((\d+)\)\.\w+/", $fileName, $match);
                        $nextNumber = intval($match[1]) + 1;
                        $fileName = preg_replace("/((.+)\()\d+(\)\.\w+)/", '${1}' . $nextNumber . '$3', $fileName);
                    }
                }
                $querySelectImages .= '\'' . $fileName . '\'';
                if ($i != count($filesPicture) - 1) {
                    $querySelectImages .= ',';
                }
                move_uploaded_file($_FILES["pets"]["tmp_name"]["filePicture"][$i], __DIR__ . "/../../../../web/uploads/pets/pictures/" . $fileName);
                $em->getConnection()->executeUpdate('INSERT INTO images (url) VALUES (\'' . $fileName . '\')');
            }

            // Sauvegarde des nouvelles vidéos
            $querySelectVideos = '';
            for ($i = 0; $i < count($filesVideo); $i++) {
                $fileName = str_replace(' ', '_', $filesVideo[$i]);
                while (file_exists($old_pet->getUploadRootVideoDir() . '/' . $fileName)) {
                    $match = '';
                    if ($fileName == str_replace(' ', '_', $filesVideo[$i])) {
                        $fileName = preg_replace('/(.+)\./', "$1(1).", $fileName);
                    } else {
                        preg_match("/\((\d+)\)\.\w+/", $fileName, $match);
                        $nextNumber = intval($match[1]) + 1;
                        $fileName = preg_replace("/((.+)\()\d+(\)\.\w+)/", '${1}' . $nextNumber . '$3', $fileName);
                    }
                }
                $querySelectVideos .= '\'' . $fileName . '\'';
                if ($i != count($filesVideo) - 1) {
                    $querySelectVideos .= ',';
                }
                move_uploaded_file($_FILES["pets"]["tmp_name"]["fileVideo"][$i], __DIR__ . "/../../../../web/uploads/pets/videos/" . $fileName);
                $em->getConnection()->executeUpdate('INSERT INTO videos (url) VALUES (\'' . $fileName . '\')');
            }
            if ($querySelectImages !== '') {
                $sql_imgs = $em->getConnection()->prepare("SELECT DISTINCT url, idImages FROM images WHERE url IN (" . $querySelectImages . ") ORDER BY idImages DESC");
                $sql_imgs->execute();
                $idImages = $sql_imgs->fetchAll();
            } else {
                $idImages = [];
            }

            if ($querySelectVideos != '') {
                $sql_videos = $em->getConnection()->prepare("SELECT DISTINCT url, idVideos FROM videos WHERE url IN (" . $querySelectVideos . ") ORDER BY idVideos DESC");
                $sql_videos->execute();
                $idVideos = $sql_videos->fetchAll();
            } else {
                $idVideos = [];
            }

            for ($i = 0; $i < count($filesPicture); $i++) {
                $sql_new_imgs = $em->getConnection()->prepare('INSERT INTO pets_has_images (Pets_idPets, Images_idImages) VALUES (' . $pet["idpets"] . ',' . $idImages[$i]["idImages"] . ')');
                $sql_new_imgs->execute();
            }

            for ($i = 0; $i < count($filesVideo); $i++) {
                $sql_new_videos = $em->getConnection()->prepare('INSERT INTO pets_has_videos (Pets_idPets, Videos_idVideos) VALUES (' . $pet["idpets"] . ', ' . $idVideos[$i]["idVideos"] . ')');
                $sql_new_videos->execute();
            }

            $pet_of_month = isset($pet["petofmonth"]) ? 1 : 0;


            // TODO Ajouter la prise en compte du Veteran
            $sql_pet = $em->getConnection()->prepare('UPDATE pets SET '
                    . 'reference = \'?\', '
                    . 'sex = \'?\', '
                    . 'description = \'?\','
                    . 'arrivaldate = \'?\','
                    . 'birthdate = \'?\','
                    . 'petofmonth = \'?\','
                    . 'size = \'?\','
                    . 'type = \'?\','
                    . 'Races_idRaces = \'?\' '
                    . 'WHERE idPets = \'?\'', array($pet["reference"], $pet["sex"], $pet["description"], new \DateTime(str_replace('/', '-', $pet["arrivaldate"])), new \DateTime(str_replace('/', '-', $pet["birthdate"])), $pet_of_month, $pet["size"], $pet["type"], $pet["racesraces"], $pet["idpets"]));
            $sql_pet->execute();

            return $this->redirectToRoute('spa_spa_admin_allpets');
        }
    }
    
    public function deletePetsAction($id) {
        $em = $this->getDoctrine()->getmanager();
        $pet = $em->getRepository('SpaSpaBundle:Pets')->find($id);
        $em->remove($pet);
        $em->flush();
        $sql_imgs = $em->getConnection()->prepare("SELECT Images_idImages FROM pets_has_images WHERE Pets_idPets LIKE '" . $id . "'");
        $sql_imgs->execute();
        $idImages = $sql_imgs->fetchAll();
        
        $sql_videos = $em->getConnection()->prepare("SELECT Videos_idVideos FROM pets_has_videos WHERE Pets_idPets LIKE '" . $id . "'");
        $sql_videos->execute();
        $idVideos = $sql_videos->fetchAll();
        
        if (count($idImages) != 0) {
            $sql_del_imgs = $em->getConnection()->prepare("DELETE FROM images WHERE idImages IN (" . implode(',', $idImages) . ")");
            $sql_del_imgs->execute();
        }
        if (count($idVideos) != 0) {
            $sql_del_videos = $em->getConnection()->prepare("DELETE FROM videos WHERE idVideos IN (" . implode(',', $idVideos) . ")");
            $sql_del_videos->execute();
        }
        
        for ($i = 0; $i < count($pet->getImagesimages()); $i++) {
            unlink(__DIR__ . "/../../../../web/uploads/pets/pictures/" . $pet->getImagesimages()[$i]->getUrl());
        }
        for ($i = 0; $i < count($pet->getVideosvideos()); $i++) {
            unlink(__DIR__ . "/../../../../web/uploads/pets/videos/" . $pet->getVideosvideos()[$i]->getUrl());
        }
        
        return $this->redirectToRoute('spa_spa_admin_allpets');
    }
}
