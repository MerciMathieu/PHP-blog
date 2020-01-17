<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controller\HomeController;
use App\Controller\ContactController;

$homeController = new HomeController(); 
$contactController = new ContactController();

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case "home":
            return $homeController->index();
        break;
        case "contact":
            return $contactController->index();
        break;
        default:
            return $homeController->index();
    break;
}
} else { 
    return $homeController->index();
}