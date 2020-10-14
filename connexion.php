<?php

require_once 'inc/init.php';
    $message = ''; // Pour le message de déconnexion.

//2 - Deconnexion
debug ($_GET);

if (isset($_GET['action']) && $_GET['action'] == 'deconnexion'){ // si existe action dans $_GET et que ca valeur correspond a déconnexion c'est que le membre a cliqué sur déconnexion.
    unset ($_SESSION['membre']); // On supprime la partie "membre" de la session (sans toucher à l'éventuel panier).
    $message = '<div class="alert alert-info">Vous êtes déconnecté</div>';
}

// 3 - Internaute deja connecté : on le redirige vers son profil.
if (estConnecte()){
    header('location:profil.php'); // On redirige vers le profil.php
    exit(); // et on quitte le script
}




// 1 - Traitement du formulaire de connexion.
debug($_POST);
if (!empty($_POST)){ // Si le formulaire à été envoyé

    // Controe du formulaire : 
    if (empty($_POST['mdp']) || empty($_POST['pseudo'])) {
        $contenu .= '<div class="alert alert-danger"> Les identifiants sont obligatoires</div>';
    }

    //Si il n'y a pas d'erreur sur le formulaire, on peut vérifier le pseudo et le mdp :
    if (empty($contenu)) {// si vide, il n'y a pas d'erreur.
        $resultat = executeRequete("SELECT * FROM membre WHERE pseudo = :pseudo", 
                                    array(':pseudo' => $_POST['pseudo']));
    


    if ($resultat->rowCount() >= 1) { // Si il y a une ligne en BDD, alors le pseudo existe : on peur vérifier le mot de passe 
        
        $membre = $resultat->fetch(PDO::FETCH_ASSOC); // Pas while car seul résultat dans notre requête.
        if (password_verify($_POST['mdp'], $membre['mdp'])){ // si le hash de mdp du formulaire correspond à celui de la BDD, on peut connecterle membre.
            $_SESSION['membre'] = $membre; // Nous remplissons une session avec les informations du membre contenu dans l'array $membre
            
            header('location:profil.php'); // Le pseudo et le mot de passe etant corrects, on redirige l'internaute vers la page profil.php
            exit();
        }
        else {
            $contenu .= '<div class="alert alert-danger"> Erreur sur les identifiants</div>';
        }
    }
    else {
        $contenu .= '<div class="alert alert-danger"> Erreur sur les identifiants</div>';
    }
} //  Fin du if ($contenu))
} // Fin du if (!empty($_POST))




require_once 'inc/header.php';
?>
<h1 class="mt-4">Connexion</h1>
<?php
echo $message; //Pour la déconnexion.
echo $contenu; //Pour la connexion.
?>

<form method="post" action="">
    <div>
        <div><label for="pseudo">Pseudo</label></div>
        <div><input type="text" name="pseudo" id="pseudo"></div>
    </div>

    <div>
        <div><label for="mdp">Mot de passe</label></div>
        <div><input type="text" name="mdp" id="mdp"></div>
    </div>
        
    <div class="mt-2"><input type="submit" value="Se connecter"></div>
</form>



<?php
require_once 'inc/footer.php';

?>
