<?php 

namespace App\Controller\Home;

use App\Controller\AbstractController;

Class HomeController extends AbstractController
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
        $subject = 'Contact via formulaire';
        $message = $_POST['message'];

        mail($to, $subject, $message);
    }

}