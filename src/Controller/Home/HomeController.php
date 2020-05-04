<?php

namespace App\Controller\Home;

use App\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index()
    {
        if (isset($_POST['submit'])) {
            $this->formValidation();
        }

        return $this->twig->render('homepage/homepage.html.twig');
    }
  
    private function sendMail(): void
    {
        $to      = 'mathieu.delclos@gmail.com';
        $subject = $_POST['firstname'] . ' ' . $_POST['lastname'] . ' via formulaire de contact blog-php' ;
        $message = $_POST['message'];
        $headers = 'From: '. $_POST['email'] . "\r\n" .
        'Reply-To: ' . $_POST['email'] . "\r\n" ;

        mail($to, $subject, $message, $headers);
    }

    private function formValidation(): void
    {
        if (isset($_POST['firstname']) and !empty($_POST['firstname']) and
            isset($_POST['lastname']) and !empty($_POST['lastname']) and
            isset($_POST['email']) and !empty($_POST['email']) and
            isset($_POST['message']) and !empty($_POST['message'])) {
                $this->sendMail();
        } else {
            #TODO RENVOYER UNE ERREUR si la validation n'est pas ok
        }
    }
}
