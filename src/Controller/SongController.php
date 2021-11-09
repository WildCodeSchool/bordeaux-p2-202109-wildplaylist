<?php

namespace App\Controller;

use App\Model\SongManager;
use DateTime;

class SongController extends AbstractController
{
    public function add(): string
    {
        $songManager = new SongManager();
        $now = new DateTime();
        $date = $now->format('Y-m-d');
        $count = $songManager->countSongsOfByDay($date);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ((int)$count < 10) {
                if (isset($_POST['adding'])) {
                    $song = array_map('trim', $_POST);
                    $songManager->insert($song, $_SESSION['user']['id']);
                }
            }
            header('Location: /');
        }
        return $this->twig->render('Home/index.html.twig');
    }
}
