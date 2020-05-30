# <p>Projet 5 OpenClassrooms - Créez votre premier blog en PHP</p> <p>Parcours Développeur d'application - PHP / Symfony</p>

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/e35cb77262d74e9386743380006bd8d3)](https://www.codacy.com?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=MerciMathieu/PHP-blog&amp;utm_campaign=Badge_Grade)

## Prérequis / Requirements
*   composer
*   php 7.4.*
*   apache 2.4.41
*   phpmyadmin (SGBD)

## Installation / Install

### Git clone

    git clone https://github.com/MerciMathieu/PHP-blog.git ./

### Lancez composer / Run composer

    composer install

### Base de donnée / Database

*   Importer dans phpmyadmin le fichier **main.sql** situé dans **/dump** pour créer la base de données ainsi que la structure des tables nécessaires au fonctionnement du blog. 
*   Entrez les informations de connexion à votre base de données dans le fichier **/src/classes/ConnectDb.php**

---

*   In phpmyadmin, import file **main.sql** which is in **/dump** folder to create database and tables needed to make the blog working fine. 
*   Enter database informations in **/src/classes/ConnectDb.php** file. 

### Formulaire de contact / Contact form

Dans le fichier  **HomeController.php**, trouvez cette ligne et entrez votre email: 

---

In  **HomeController.php** file, find this line and enter your email:

    $receiver  = 'youremail@example.com';

## Administration

Il y'a un lien pour se connecter à l'administration dans le footer. 
Pour entrer dans l'administration et créer des articles vous devez être administrateur:
*   vous devez d'abord vous enregistrer sur votre blog (mot de passe crypté)
*   dans la base données, changez la colonne 'is_admin' correspondant à vos identifiants de 0 à 1.
*   vous pouvez utiliser pleinement le blog !

---

There is a link to connect to administration in the footer.
To enter in administration and create articles you have to be administrator. 
*   first, register on your blog (encrypted password)
*   in database, change column 'is_admin' corresponding to your username from 0 to 1.
*   you can fully use the blog !
