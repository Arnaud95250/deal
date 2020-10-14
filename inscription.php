<?php
require_once 'inc/init.php';
$affiche_formulaire = true; // Pour afficher le formulaire tant que le membre n'est pas inscrit

// Traitemet du formulaire.

debug($_POST); //  Pour les 2 || ca veut dire ou.

if (!empty($_POST)) { // Si on a cliqué sur s'inscrire
// Ici on valide tous les champs du formulaire :
if (!isset($_POST['pseudo']) || strlen($_POST['pseudo']) < 4 ||strlen($_POST['pseudo']) > 20) { // Si le champ pseudo n'existe pas ou que la taille est trop longue ou trop courte on met un msg à l'internaute.
    $contenu .= '<div class="alert alert-danger"> Le pseudo doit contenir entre 4 et 20 caractères</div>';
}

if (!isset($_POST['mdp']) || strlen($_POST['mdp']) < 8 ||strlen($_POST['mdp']) > 20) { // Si le champ pseudo n'existe pas ou que la taille est trop longue ou trop courte on met un msg à l'internaute.
    $contenu .= '<div class="alert alert-danger"> Le Mot de passe doit contenir entre 8 et 20 caractères</div>';
}

if (!isset($_POST['nom']) || strlen($_POST['nom']) < 2 ||strlen($_POST['nom']) > 20) { // Si le champ pseudo n'existe pas ou que la taille est trop longue ou trop courte on met un msg à l'internaute.
    $contenu .= '<div class="alert alert-danger"> Le Nom doit contenir entre 2 et 20 caractères</div>';
}

if (!isset($_POST['prenom']) || strlen($_POST['prenom']) < 2 ||strlen($_POST['prenom']) > 20) { // Si le champ pseudo n'existe pas ou que la taille est trop longue ou trop courte on met un msg à l'internaute.
    $contenu .= '<div class="alert alert-danger"> Le Prénom doit contenir entre 2 et 20 caractères</div>';
}

if (!isset($_POST['telephone']) || strlen($_POST['telephone']) < 10 ||strlen($_POST['telephone']) > 10) { // Si le champ pseudo n'existe pas ou que la taille est trop longue ou trop courte on met un msg à l'internaute.
    $contenu .= '<div class="alert alert-danger"> Le numéro de téléphone doit contenir 10 chiffres</div>';
}

if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {// La fonction prédéfinie filter_var() avec le paramètre FILTER_VALIDATE_EMAIL valide si le string fourni est bien un email.
    $contenu .= '<div class="alert alert-danger">L\'email est invalide.</div>';
}

if (!isset($_POST['civilite']) || ($_POST['civilite'] != 'm' && $_POST['civilite'] != 'f')) {// Si la civilité est différente de 'm' et de 'f' en même temps
    $contenu .= '<div class="alert alert-danger">La civilité est invalide.</div>';
}


// S'il n'y a aps d'erreur sur le formulaire, on vérifie l'unicité du pseudo avant d'nscrire le membre.
if (empty($contenu)) { // si la variable est vide c'est qu'il n'y a pas de message d'erreur.
    // On vérifie l'unicité du pseudo.
    $membre = executeRequete("SELECT * FROM membre WHERE pseudo = :pseudo", array(':pseudo' => $_POST['pseudo']));

    if ($membre->rowcount() > 0) { // Si la requête contient 1 ou plusieurs lignes, c'est que le pseudo est déja en BDD
        $contenu .= '<div class="alert alert-danger"> Le pseudo est indisponible. Veuillez en choisir un autre.</div>';
    }
    else { // Sinon on peut inscrire le membre.
         // Nous hashons le mdp avec cette fonction qui utilise à l'heure actuelle l'algoritheme scrypt. Lors de la connexion de l'internaute, il faudra comparer le hash de connexion avec celui de la BDD.

        $succes = executeRequete("INSERT INTO membre (pseudo, mdp, nom, prenom, telephone, email, civilite, statut, date_enregistrement) VALUES (:pseudo, :mdp, :nom, :prenom, :telephone, :email, :civilite, '0', NOW())",
        array(':pseudo'     => $_POST['pseudo'],
            ':mdp'          => $mdp,
            ':nom'          => $_POST['nom'],
            ':prenom'       => $_POST['prenom'],
            ':telephone'    => $_POST['telephone'],
            ':email'        => $_POST['email'],
            ':civilite'     => $_POST['civilite'],
         ));

    if ($succes) {
        $contenu .= '<div class="alert alert-success"> Vous êtes inscrit.<a href="connexion.php">Cliquer ici pour vous connecter.</a></div>';
        $affiche_formulaire = false; // pour ne plus afficher le formulaire.
    }
    else {
        $contenu .= '<div class="alert alert-danger"> Oups, erreur lors de l\'enregistrement... Veuillez essayer plus tard.</div>';
            } 
        }
    }
} //fin du if (!empty($_POST))









require_once 'inc/header.php';
?>
<h1 class="mt-4">S'inscrire</h1>
<?php
echo $contenu; //Pour affichez les messages ($contenu se trouve dans le fichier "init.php").
if ($affiche_formulaire) : // Si memebre pas inscrit, on affiche le formulaire. syntaxe en if(): ....  endif;
?>

    <form method="post" action="">
        <div>
            <div><label for="pseudo">Votre pseudo *</label></div>
            <div><input type="text" name="pseudo" id="pseudo" value="<?php echo $_POST['pseudo'] ?? ''; ?>"></div>
        </div>

        <div>
            <div><label for="mdp">Votre mot de passe *</label></div>
            <div><input type="password" name="mdp" id="mdp" value="<?php echo $_POST['mdp'] ?? ''; ?>"></div>
        </div>

        <div>
            <div><label for="nom">Votre Nom *</label></div>
            <div><input type="text" name="nom" id="nom" value="<?php echo $_POST['nom'] ?? ''; ?>"></div>
        </div>

        <div>
            <div><label for="prenom">Votre prénom *</label></div>
            <div><input type="text" name="prenom" id="prenom" value="<?php echo $_POST['prenom'] ?? ''; ?>"></div>
        </div>

        <div>
            <div><label for="telephone" >Votre téléphone *</label></div>
            <div><input type="text" name="telephone"  id="telephone" value="<?php echo $_POST['telephone'] ?? ''; ?>"></div>
        </div>

        <div>
            <div><label for="email">Votre email *</label></div>
            <div><input type="text" name="email" id="email" value="<?php echo $_POST['email'] ?? ''; ?>"></div>
        </div>

        <div>
        <div><label for="civilite">Civilité</label></div>
        <div class="input-group mb-3">
            <select name="civilite" class="custom" id="inputGroupSelect02">
                <option value="m"<?php if (isset($_POST['civilite']) && $_POST['civilite'] == 'm') echo 'selected';?>>M.</option>
                <option value="f"<?php if (isset($_POST['civilite']) && $_POST['civilite'] == 'f') echo 'selected';?>>Mme.</option>
            </select>
        </div>
    </div>

        <div><input type="submit" value="S'inscrire" ></div>
    </form>


<?php

endif;

require_once 'inc/footer.php';


?>
