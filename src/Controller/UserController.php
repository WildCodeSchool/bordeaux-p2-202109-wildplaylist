<?php

namespace App\Controller;

use App\Model\SongManager;
use App\Model\UserManager;

class UserController extends AbstractController
{
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
}
