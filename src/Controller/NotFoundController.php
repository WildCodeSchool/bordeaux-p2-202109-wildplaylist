<?php

namespace App\Controller;

class NotFoundController extends AbstractController
{
    public function notfound()
    {
        return $this->twig->render('Error/notfound.html.twig');
    }
}
