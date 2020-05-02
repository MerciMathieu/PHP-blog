<?php

namespace App\Controller\Home;

use App\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index()
    {
        if (isset($_POST['submit'])) {
            $this->sendMail();
        }

        return $this->twig->render('homepage/homepage.html.twig');
    }
  
    private function sendMail(): void
    {
        $to      = 'mathieu.delclos@gmail.com';
        $subject = 'Contact via formulaire: ' . $_POST['firstName'] . ' ' . $_POST['lastName'] ;
        $message = $_POST['message'];
        $headers = 'From: '. $_POST['email'] . "\r\n" .
        'Reply-To: ' . $_POST['email'] . "\r\n" ;

        mail($to, $subject, $message, $headers);
    }
}
