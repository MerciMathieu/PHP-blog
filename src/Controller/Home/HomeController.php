<?php

namespace App\Controller\Home;

use App\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index()
    {
        $post = null;
        $errors = [];

        if (isset($_POST['submit'])) {

            $post = $_POST;

            if (!isset($_POST['firstname']) or empty($_POST['firstname']) or strlen($_POST['firstname']) < 3 ) {
                $errors['firstname'] = "Le prénom doit contenir au moins 3 caractères";
            }
            if (!isset($_POST['lastname']) or empty($_POST['lastname']) or strlen($_POST['lastname']) < 3 ) {
                $errors['lastname'] = "Le nom doit contenir au moins 3 caractères";
            }
            if (!isset($_POST['email']) or empty($_POST['email']) or !preg_match ( " /^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/ " , $_POST['email'])) {
                $errors['email'] = "L'email doit être valide";
            }
            if (!isset($_POST['message']) or empty($_POST['message']) or strlen($_POST['message']) < 3 ) {
                $errors['message'] = "Le message doit contenir au moins 3 caractères";
            } 

            if (empty($errors)) {

                $sendMail =  $this->sendMail();
                
                if ($sendMail === false) {
                    return $this->displayError(500); 
                }

            } 
            
        }

        return $this->twig->render('homepage/homepage.html.twig', [
            'postVariables' => $post,
            'errors' => $errors
        ]);
    }

    private function sendMail(): void
    {

        $firstName = htmlspecialchars($_POST['firstname']);
        $lastName = htmlspecialchars($_POST['lastname']);
        $email = htmlspecialchars($_POST['email']);
        $message = htmlspecialchars($_POST['message']);

        $to      = 'mathieu.delclos@gmail.com';
        $subject = $firstName . ' ' . $lastName . ' via formulaire de contact blog-php' ;
        $text = $message;
        $headers = 'From: '. $email . "\r\n" .
        'Reply-To: ' . $email . "\r\n" ;

        mail($to, $subject, $text, $headers);

        header('Location: /');
    }

}
