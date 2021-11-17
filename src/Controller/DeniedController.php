<?php

namespace App\Controller;

class DeniedController extends AbstractController
{
    public function denied()
    {
        return $this->twig->render('Error/denied.html.twig');
    }
}
