<?php

namespace App\Controller;

use App\Model\SongManager;
use App\Model\UserManager;
use DateTime;

class UserController extends AbstractController
{
    public function connect(): string
    {
        $songManager = new SongManager();
        $date = new DateTime();
        $searchDate = $date->format('Y-m-d');
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['login'])) {
                $userManager = new UserManager();
                $userData = $userManager->selectOneByEmail($_POST['mail']);
                if (password_verify($_POST['password'], $userData['password'])) {
                    $_SESSION['user'] = $userData;
                    header('Location: /');
                } else {
                    $errors['wrongPass'] = 'Ce n\'est pas le bon mot de passe';
                }
            } elseif (isset($_POST['register'])) {
                $userManager = new UserManager();
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
                if (empty($github)) {
                    $errors['errorGithub'] = 'Merci d\'entrer ton nom github (Si tu veux voir afficher ta photo :))';
                }
                if ($mail === $userManager->selectOneByEmail($mail)['mail']) {
                    $errors['errorMail2'] = 'Cette adresse est déjà utilisée, merci d\'en choisir une autre';
                }
                if ($github === $userManager->selectOneByEmail($mail)['github_name']) {
                    $errors['errorGithub2'] = 'Ce nom Github est déjà utilisé, merci d\'en choisir un autre';
                }
                if (count($errors) === 0) {
                    $userManager = new UserManager();
                    $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $userId = $userManager->create($_POST);
                    $_SESSION['user'] = $userManager->selectUserById($userId);

                    header('Location: /?register=true');
                }
            }
        }

        $hasAlreadyPost = false;

        if (isset($_SESSION['user']['id'])) {
            $userManager = new UserManager();
            $hasAlreadyPost = $userManager->hasAlreadyPost($_SESSION['user']['id']);
        }
        return $this->twig->render('Home/index.html.twig', [
            'register_success' => $_GET['register'] ?? null,
            'songs'            => $songManager->showSongsByDate($searchDate),
            'has_already_post' => $hasAlreadyPost,
            'count'            => $songManager->countSongsOfByDay($searchDate),
            'errors'           => $errors,
        ]);
    }

    public function logout()
    {
        session_destroy();
        header('Location: /');
    }
    public function show($id)
    {
        $userManager = new UserManager();
        $user = $userManager->selectUserById($id);
        $songManager = new SongManager();
        $songs = $songManager->selectAllByUserId($id);

        return $this->twig->render('User/user.html.twig', [
            'user'  => $user,
            'songs' => $songs,
        ]);
    }

    public function count(): string
    {
        $songManager = new SongManager();
        $count = $songManager->countSongsOfByDay(DATE(NOW));
        return $this->twig->render('Home/index.html.twig', [
            'count' => $count,
        ]);
    }

    public function edit(string $id): string
    {
        $userManager = new UserManager();
        $_SESSION['user'] = $userManager->selectUserById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['update'])) {
                $_SESSION['pseudo']   = trim($_POST['pseudo']);
                $errors = [];
                if (empty($_SESSION['pseudo'])) {
                    $errors['errorPseudo'] =  'Choisis un pseudo';
                }
                if (count($errors) === 0) {
                    $userManager = new UserManager();
                    $userManager->update($_POST, $id);

                    header('Location: /user/edit?id=' . $id);
                }
            }
        }

        return $this->twig->render('User/edit.html.twig', [
            'user' => $_SESSION['user'],
        ]);
    }
}
