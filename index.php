<?php

/* Call controller */
require('controller/controller.php');

/* If a get parameter was set */
if (isset($_GET['action'])) {
    /* Call the attempted route */
    switch ($_GET['action']) {
        case "home":
            home();
            break;
        case "contact":
            contact();
            break;
        /* case 'post':
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                showPost();
            }
            else {
                echo 'Erreur : aucun identifiant de billet envoyé';
            }
            break;
         */

        /* In case of bad get parameter, we require homepage */
        default:
            home();
            break;
    }
} else { /* If URI is '/' we call homepage */
    home();
}