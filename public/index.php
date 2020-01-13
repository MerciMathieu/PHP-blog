<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controller\HomeController;
use App\Controller\ContactController;

$homeController = new HomeController(); 
$contactController = new ContactController();

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case "home":
            $homeController->home();
            break;
        case "contact":
            $contactController->contact();
            break;
        default:
        $homeController->home();
            break;
    }
} else { 
    $homeController->home();
}