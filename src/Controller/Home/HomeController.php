<?php

namespace App\Controller\Home;

use App\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index()
    {
        $errors = [];
        $postVariables = $this->getPostVariables();

        if (isset($postVariables['submit'])) {
            $firstName = htmlspecialchars($postVariables['firstname']);
            $lastName = htmlspecialchars($postVariables['lastname']);
            $email = htmlspecialchars($postVariables['email']);
            $message = htmlspecialchars($postVariables['message']);

            $this->checkFirstName($firstName, $errors);
            $this->checkLastName($lastName, $errors);
            $this->checkEmail($email, $errors);
            $this->checkMessage($message, $errors);

            if (!$errors) {
                $mail = $this->sendMail();

                if ($mail === false) {
                    $this->displayError(500);
                }

                header("Location: /email/confirmation");
            }
        }

        $this->render('homepage/homepage.html.twig', [
            'postVariables' => $postVariables,
            'errors' => $errors
        ]);
    }

    private function checkFirstName(string $firstName, array &$errors)
    {
        if (!isset($firstName) or empty($firstName) or strlen($firstName) < 3) {
            $errors['firstname'] = "Le prénom doit contenir au moins 3 caractères";
        }
    }

    private function checkLastName(string $lastName, array &$errors)
    {
        if (!isset($lastName) or empty($lastName) or strlen($lastName) < 3) {
            $errors['lastname'] = "Le nom doit contenir au moins 3 caractères";
        }
    }

    private function checkEmail(string $email, array &$errors)
    {
        if (!isset($email) or empty($email) or !preg_match(" /^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/ ", $email)) {
            $errors['email'] = "L'email doit être valide";
        }
    }

    private function checkMessage(string $message, array &$errors)
    {
        if (!isset($message) or empty($message) or strlen($message) < 3) {
            $errors['message'] = "Le message doit contenir au moins 3 caractères";
        }
    }

    private function sendMail(): void
    {
        $postVariables = $this->getPostVariables();
        $firstName = htmlspecialchars($postVariables['firstname']);
        $lastName = htmlspecialchars($postVariables['lastname']);
        $email = htmlspecialchars($postVariables['email']);
        $message = htmlspecialchars($postVariables['message']);

        $receiver      = 'mathieu.delclos@gmail.com';
        $subject = $firstName . ' ' . $lastName . ' via formulaire de contact blog-php' ;
        $text = $message;
        $headers = 'From: '. $email . "\r\n" .
        'Reply-To: ' . $email . "\r\n" ;

        mail($receiver, $subject, $text, $headers);
    }

    public function displayConfirmationSendMail()
    {
        return  $this->render('confirm/confirmEmail.html.twig');
    }

}
