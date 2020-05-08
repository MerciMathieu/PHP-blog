<?php

namespace App\Controller\Error;

use App\Controller\AbstractController;

class ErrorController extends AbstractController
{
    public function display404()
    {
        return $this->twig->render('error/error404.html.twig');
    }
}
