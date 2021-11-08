<?php

namespace App\Controller;

use App\Model\SongManager;

class SongController extends AbstractController
{
    public function add(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['adding'])) {
                $song = array_map('trim', $_POST);
                $songManager = new SongManager();
                $songManager->insert($song, $_SESSION['user']['id']);
            }
            header('Location: /');
        }

        return $this->twig->render('Home/index.html.twig');
    }
}
