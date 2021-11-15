<?php

namespace App\Controller;

use App\Model\SongManager;
use App\Model\UserManager;
use DateTime;

class UserController extends AbstractController
{

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
