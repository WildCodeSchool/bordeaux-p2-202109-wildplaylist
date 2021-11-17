<?php

namespace App\Controller;

use App\Model\RatingManager;
use App\Model\SongManager;
use App\Service\SongUrlRefactor;
use App\Service\SongTitleCollector;
use DateTime;

class SongController extends AbstractController
{
    public function add(): string
    {
        $songManager = new SongManager();
        $songTitleCollector = new SongTitleCollector();
        $songUrlRefactor = new SongUrlRefactor();
        $now = new DateTime();
        $date = $now->format('Y-m-d');
        $count = $songManager->countSongsOfByDay($date);
        $urlError = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ((int)$count < 10) {
                if (isset($_POST['adding'])) {
                    $song = array_map('trim', $_POST);
                    $songUrl = $song['url'];
                    $songUrlRefactor->songUrlRefactor($songUrl);
                    $song['url'] = $songUrlRefactor->songUrlRefactor($songUrl);
                    if ($song['url'] === 'false') {
                        $urlError = 'Ce lien Youtube n\'est pas valide';
                    } else {
                        $videoId = $song['url'];
                        $songTitleCollector->songTitleCollector($videoId);
                        $song['title'] = $songTitleCollector->songTitleCollector($videoId);
                        $songManager->insert($song, $_SESSION['user']['id']);
                    }
                }
            }
            header('Location: /');
        }
        return $this->twig->render('Home/index.html.twig');
    }


    public function vote($songId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_SESSION) {
                $songManager = new SongManager();
                $songManager->voteFor($songId);
                $ratingManager = new RatingManager();
                $ratingManager->insertVote($songId, $_SESSION['user']['id']);
            }
        }
        header('Location:/');
    }
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['delete'])) {
                $id = trim($_POST['id']);
                $songManager = new SongManager();
                $songManager->delete((int)$id);
                header('Location:/');
            }
        }
    }
}
