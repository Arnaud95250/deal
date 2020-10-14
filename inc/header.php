<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <title>Deal</title>
    <!-- CDN Bootstrap CSS -->

</head>
<body>
    <!-- navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
        
            <!-- marque -->
            <a class="navbar-brand" href="<?php echo RACINE_SITE . 'index.php'; ?>">Deal</a> <!-- On utilise notre constante pour faire un chemin absolu quelque soit le fichier dans lequel sera inclus ce heaer -->
            
            <!-- burger -->
            <button class="navbar-toggler" type="button" data-toggler="collapse" data-target="#nav1" aria-controls="navbarResponsive" aria-expanted="false" aria-label="Toggle navigation" >
                <span class="navbar-toggler-icon"></span>
                </button>
            <!-- menu -->
            <div class="collapse navbar-collapse" id="#nav1" >
                <ul class="navbar-nav ml-auto">
                <?php
                      
                    if (estConnecte()) { // Membre connecté
                        echo '<li><a class="nav-link" href="'. RACINE_SITE .'deposer_annonce.php">Déposer une annonce</a></li>';
                        echo '<li><a class="nav-link" href="'. RACINE_SITE .'profil.php">Espace Membre</a></li>';
                        echo '<li><a class="nav-link" href="'. RACINE_SITE .'connexion.php?action=deconnexion">Déconnexion</a></li>';
                    }
                    else{ // Membre deconnecté
                        echo '<li><a class="nav-link" href="'. RACINE_SITE .'inscription.php">Inscription</a></li>'; 
                        echo '<li><a class="nav-link" href="'. RACINE_SITE .'connexion.php">Connexion</a></li>'; 
                    } 
                    if (estAdmin()) { // Administrateur du site
                        echo '<li><a class="nav-link" href="'. RACINE_SITE .'admin/gestion_boutique.php">Gestion</a></li>';
                    }
                    
                       
                ?>
                </ul>
            </div><!-- Fin du menu -->
        </div><!-- container -->
    </nav>

    <!-- Contenu de la page -->
    <div class="container" style="min-height: 80vh;">
        <div class="row">
            <div class="col-12">
