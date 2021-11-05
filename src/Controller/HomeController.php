<?php

/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

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
    public function index()
    {
        $songManager = new SongManager();
        $date = new DateTime();
        $searchDate = $date->format('Y-m-d');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $searchDate = $_POST['date'];
        }
        $songs = $songManager->showSongsByDate($searchDate);
        return $this->twig->render('Home/index.html.twig', ['songs' => $songs]);
    }
}
