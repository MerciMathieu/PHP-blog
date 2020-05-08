<?php

namespace App\Controller\Home;

use App\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index()
    {
        $post = null;
        $errors = [];
        $class = null;
        $textColorError = [];

        if (isset($_POST['submit'])) {

            $post = $_POST;
            $success = true;
            $class = 'success';
            
            if (!isset($_POST['firstname']) or empty($_POST['firstname']) or strlen($_POST['firstname']) < 3 ) {
                $success = false;
                $errors['firstname'] = "Le prénom doit contenir au moins 3 caractères";
                $class= 'bg-danger';
                $textColorError['firstname'] = 'text-danger';
            }
            if (!isset($_POST['lastname']) or empty($_POST['lastname']) or strlen($_POST['lastname']) < 3 ) {
                $success = false;
                $errors['lastname'] = "Le nom doit contenir au moins 3 caractères";
                $class= 'bg-danger';
                $textColorError['lastname'] = 'text-danger';
            }
            if (!isset($_POST['email']) or empty($_POST['email']) or !preg_match ( " /^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/ " , $_POST['email'])) {
                $success = false;
                $errors['email'] = "L'email doit être valide";
                $class= 'bg-danger';
                $textColorError['email'] = 'text-danger';
            }
            if (!isset($_POST['message']) or empty($_POST['message']) or strlen($_POST['message']) < 3 ) {
                $success = false;
                $errors['message'] = "Le message doit contenir au moins 3 caractères";
                $class= 'bg-danger';
                $textColorError['message'] = 'text-danger';
            } 

            if ($success === true) {
                $this->sendMail();
            }   
        }

        return $this->twig->render('homepage/homepage.html.twig', [
            'postVariables' => $post,
            'errors' => $errors,
            'class' => $class,
            'textColorError' => $textColorError
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
}
