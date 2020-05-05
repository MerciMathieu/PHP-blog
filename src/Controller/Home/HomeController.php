<?php

namespace App\Controller\Home;

use App\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index()
    {
        $post = null;

        if (isset($_POST['submit'])) {
            $post = $_POST;
            $this->formValidation();
        }

        return $this->twig->render('homepage/homepage.html.twig', [
            'postVariables' => $post
        ]);
    }

    private function sendMail(): void
    {
        $to      = 'mathieu.delclos@gmail.com';
        $subject = $_POST['firstname'] . ' ' . $_POST['lastname'] . ' via formulaire de contact blog-php' ;
        $message = $_POST['message'];
        $headers = 'From: '. $_POST['email'] . "\r\n" .
        'Reply-To: ' . $_POST['email'] . "\r\n" ;

        mail($to, $subject, $message, $headers);

        header('Location: /');
    }

    private function formValidation(): void
    {
        $success = true;
        $errors = [];

        if (!isset($_POST['firstname']) or empty($_POST['firstname']) or strlen($_POST['firstname']) < 3 ) {
            $success = false;
            $errors['firstname'] = "Le prénom doit contenir au moins 3 caractères";
        }

        if (!isset($_POST['lastname']) or empty($_POST['lastname']) or strlen($_POST['lastname']) < 3 ) {
            $success = false;
            $errors['lastname'] = "Le nom doit contenir au moins 3 caractères";
        }

        if (!isset($_POST['email']) or empty($_POST['email']) or !preg_match ( " /^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/ " , $_POST['email'])) {
            $success = false;
            $errors['email'] = "L'email doit être valide";
        }

        if (!isset($_POST['message']) or empty($_POST['message']) or strlen($_POST['message']) < 3 ) {
            $success = false;
            $errors['message'] = "Le message doit contenir au moins 3 caractères";
        } 

        var_dump($errors);
        var_dump($success);

        if ($success === true) {
            $this->sendMail();
        }      
    }
}
