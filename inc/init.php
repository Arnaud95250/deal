<?php
// Connection à la BDD : 
$pdo = new PDO();

// Créer une séssion ou y accéder si elle existe : 

session_start();

// Définition du chemin du site :
 define('RACINE_SITE', '/'); // Indiquer ici le dossier dans lequel se situe votre site sans "localhost". Permet de créer des chemins absolus (avec le / au debut) nécessaire dans les inclusions (header-php, footer-php).

 //VAriables pour afficher : 
$contenu = '';
$valider = '';
$contenu_gauche = '';
$contenu_droite = '';

// Inclusion des fonctions :
require_once 'functions.php';