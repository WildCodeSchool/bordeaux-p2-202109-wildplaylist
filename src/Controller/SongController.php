<?php

namespace App\Controller;

use App\Model\SongManager;

class SongController extends AbstractController
{
    public function add(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $song = array_map('trim', $_POST);
            $songManager = new SongManager();
            $songManager->insert($song, $_SESSION['user']['id']);
        }

        return $this->twig->render('Home/index.html.twig');
    }
}
