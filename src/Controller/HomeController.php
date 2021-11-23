<?php

/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Model\RatingManager;
use App\Model\UserManager;
use DateTime;
use App\Model\SongManager;

class HomeController extends AbstractController
{
    /**
     * Display home page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function form(): string
    {
        $isFromDate = false;
        $songManager = new SongManager();
        $userManager = new UserManager();
        $ratingManager = new RatingManager();
        $date = new DateTime();
        $searchDate = $date->format('Y-m-d');
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['login'])) {
                $userData = $userManager->selectOneByEmail($_POST['mail']);
                if ($userData) {
                    if (password_verify($_POST['password'], $userData['password'])) {
                        $_SESSION['user'] = $userData;
                        header('Location: /');
                    } else {
                        $errors['wrongPass'] = 'Ce n\'est pas le bon mot de passe';
                    }
                } else {
                    $errors['wrongMail'] = 'Ce mail n\'existe pas';
                }
            } elseif (isset($_POST['register'])) {
                $pseudo   = trim($_POST['pseudo']);
                $mail     = trim($_POST['mail']);
                $password = trim($_POST['password']);
                $github   = trim($_POST['github-name']);
                if (empty($pseudo)) {
                    $errors['errorPseudo'] =  'Choisis un pseudo';
                }
                if (strlen($pseudo) <= 2) {
                    $errors['errorPseudo2'] = 'Plus de 1 charactère quand même !';
                }
                if (empty($password)) {
                    $errors['errorPass'] = 'Choisis un mot de passe';
                }
                if (empty($mail)) {
                    $errors['errorMail'] = 'Entre ton adresse mail';
                }
                if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                    $errors['formatEmail'] = "Le format de l'email est invalide";
                }
                if (empty($github)) {
                    $errors['errorGithub'] = 'Merci d\'entrer ton nom github (Si tu veux voir afficher ta photo :))';
                }
                if ($userManager->selectOneByEmail($mail)) {
                    $errors['errorMail2'] = 'Cette adresse est déjà utilisée, merci d\'en choisir une autre';
                }
                if (count($errors) === 0) {
                    $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $userId = $userManager->create($_POST);
                    $_SESSION['user'] = $userManager->selectUserById($userId);

                    header('Location: /');
                }
            } elseif ($_POST['date'] !== '') {
                $isFromDate = true;
                $searchDate = $_POST['date'];
            }
        }
        $songs = $songManager->showSongsByDate($searchDate);
        $hasAlreadyPost = false;
        $ratings = 0;
        $dislike = 0;

        if (isset($_SESSION['user']['id'])) {
            $hasAlreadyPost = $userManager->hasAlreadyPost($_SESSION['user']['id']);
            $ratings = $ratingManager->selectVote($_SESSION['user']['id']);
        }
        return $this->twig->render('Home/index.html.twig', [
            'songs'            => $songs,
            'count'            => $songManager->countSongsOfByDay($searchDate),
            'errors'           => $errors,
            'is_from_date'     => $isFromDate,
            'ratings'          => $ratings,
            'dislike'          => $dislike,
            'search_date'      => $searchDate,
            'has_already_post' => $hasAlreadyPost,
        ]);
    }
}
