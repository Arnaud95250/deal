<?php
require_once '../inc/init.php'; // attention au ../ (pour lier les fichier php ou html...)

// 1 - Vérification administrateur :
if (!estAdmin()){
    header('location:../connexion.php'); // Si le membre n'esyt pas connecté ou n'est pas admi.On le redirige vers la page de connection.
    exit(); // On quitte le script
}

debug($_POST);
?>
<?php
// 7 - Suppression du annonce :
if (isset($_GET['id_membre'])){ // Si existe id_membre dans l'url, donc dans $_GET, c'est qu'on a demandé la suppression du annonce.
$resultat = executeRequete("DELETE FROM membre WHERE id_membre = :id_membre", array(':id_membre' => $_GET['id_membre']));

    if ($resultat->rowCount() == 1){
        $contenu .= '<div class="alert alert-succes">Le membre a bien été supprimé.</div>';
    }
    else {
        $contenu .= '<div class="alert alert-danger">Erreur lors de la suppression</div>';
    }
}

// ("SELECT id_membre, titre, description_courte, description_longue, prix, photo, pays, ville, adresse, code_postal, prenom, id_categorie, date_enregistrement FROM annonce INNER JOIN membre ON annonce.id_membre = membre.id_membre");

// 6 - Affichage des annonces dans le back-office.
$resultat = executeRequete("SELECT id_membre, pseudo, nom, prenom, email, telephone, civilite, statut, date_enregistrement FROM membre"); // On selectionne tous les annonces.

$contenu .= '<div>Nombre de membres : ' . $resultat->rowCount() . '</div>';

$contenu .= '<div class="table-responsive">';
    $contenu .= '<table class="table">';
    //Lignes des entêtes du tableau :
        $contenu .= '<tr>';
            $contenu .= '<th>id_membre</th>';
            $contenu .= '<th>pseudo</th>';
            $contenu .= '<th>nom</th>';
            $contenu .= '<th>prenom</th>';
            $contenu .= '<th>email</th>';
            $contenu .= '<th>telephone</th>';
            $contenu .= '<th>civilite</th>';
            $contenu .= '<th>statut</th>';
            $contenu .= '<th>date_enregistrement</th>';
            $contenu .= '<th>action</th>';
        $contenu .= '</tr>';

        //Affichage des lignes du tableau :
        while ($membre = $resultat->fetch(PDO::FETCH_ASSOC)){ //$membre est un array avec toutes les informations d'un seul membre à chaque tour de boucle. On le parcourt donc avec une boucle foreach.
        $contenu .= '<tr>';
            foreach ($membre as $indice => $valeur){
                if ($indice == 'photo') { 
                $contenu .= '<td><img src="../'. $valeur .'"style="width:90px"></td>';
            }
            elseif ($indice == 'description_longue'){
                $contenu .= '<td>' . substr($valeur, 0, 10) . '...</td>';
            
            }
            else{
                $contenu .= '<td>' . $valeur . '</td>';
            }
        }
        $contenu .= '<td>
                        <a href="formulaire_modification.php?id_membre='.$membre['id_membre'].'">modifer</a> |
                        <a href="?id_membre='.$membre['id_membre'].'" onclick = "return confirm(\'Etes-vous certain de vouloir supprimer ce membre?\')">supprimer</a>
                     </td>';


        $contenu .= '</tr>';
        }

    $contenu .='</div>';
$contenu .= '</table>';

//*******************************************************************************************************************************
// Debut du formulaire membre
//*******************************************************************************************************************************

$affiche_formulaire = true; // Pour afficher le formulaire tant que le membre n'est pas inscrit

// Traitemet du formulaire.

// debug($_POST); //  Pour les 2 || ca veut dire ou.

if (!empty($_POST)) { // Si on a cliqué sur s'inscrire
// Ici on valide tous les champs du formulaire :
if (!isset($_POST['pseudo']) || strlen($_POST['pseudo']) < 4 ||strlen($_POST['pseudo']) > 20) { // Si le champ pseudo n'existe pas ou que la taille est trop longue ou trop courte on met un msg à l'internaute.
    $valider .= '<div class="alert alert-danger"> Le pseudo doit contenir entre 4 et 20 caractères</div>';
}

if (!isset($_POST['mdp']) || strlen($_POST['mdp']) < 8 ||strlen($_POST['mdp']) > 20) { // Si le champ pseudo n'existe pas ou que la taille est trop longue ou trop courte on met un msg à l'internaute.
    $valider .= '<div class="alert alert-danger"> Le Mot de passe doit contenir entre 8 et 20 caractères</div>';
}

if (!isset($_POST['nom']) || strlen($_POST['nom']) < 2 ||strlen($_POST['nom']) > 20) { // Si le champ pseudo n'existe pas ou que la taille est trop longue ou trop courte on met un msg à l'internaute.
    $valider .= '<div class="alert alert-danger"> Le Nom doit contenir entre 2 et 20 caractères</div>';
}

if (!isset($_POST['prenom']) || strlen($_POST['prenom']) < 2 ||strlen($_POST['prenom']) > 20) { // Si le champ pseudo n'existe pas ou que la taille est trop longue ou trop courte on met un msg à l'internaute.
    $valider .= '<div class="alert alert-danger"> Le Prénom doit contenir entre 2 et 20 caractères</div>';
}

if (!isset($_POST['telephone']) || strlen($_POST['telephone']) < 10 ||strlen($_POST['telephone']) > 10) { // Si le champ pseudo n'existe pas ou que la taille est trop longue ou trop courte on met un msg à l'internaute.
    $valider .= '<div class="alert alert-danger"> Le numéro de téléphone doit contenir 10 chiffres</div>';
}

if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {// La fonction prédéfinie filter_var() avec le paramètre FILTER_VALIDATE_EMAIL valide si le string fourni est bien un email.
    $valider .= '<div class="alert alert-danger">L\'email est invalide.</div>';
}

if (!isset($_POST['civilite']) || ($_POST['civilite'] != 'm' && $_POST['civilite'] != 'f')) {// Si la civilité est différente de 'm' et de 'f' en même temps
    $contenu .= '<div class="alert alert-danger">La civilité est invalide.</div>';
}

if (!isset($_POST['statut']) || ($_POST['statut'] != '1' && $_POST['statut'] != '0')) {// Si la civilité est différente de 'm' et de 'f' en même temps
    $valider .= '<div class="alert alert-danger">La civilité est invalide.</div>';
}


// S'il n'y a aps d'erreur sur le formulaire, on vérifie l'unicité du pseudo avant d'nscrire le membre.
if (empty($valider)) { // si la variable est vide c'est qu'il n'y a pas de message d'erreur.
    // On vérifie l'unicité du pseudo.
    $membre = executeRequete("SELECT * FROM membre WHERE pseudo = :pseudo", array(':pseudo' => $_POST['pseudo']));

    if ($membre->rowcount() > 0) { // Si la requête contient 1 ou plusieurs lignes, c'est que le pseudo est déja en BDD
        $valider .= '<div class="alert alert-danger"> Le pseudo est indisponible. Veuillez en choisir un autre.</div>';
    }
    else { // Sinon on peut inscrire le membre.
        $mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT); // Nous hashons le mdp avec cette fonction qui utilise à l'heure actuelle l'algoritheme scrypt. Lors de la connexion de l'internaute, il faudra comparer le hash de connexion avec celui de la BDD.

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
        $contenu .= '<div class="alert alert-success"> Vous avez inscrit un nouveau membre.</div>';
        $affiche_formulaire = false; // pour ne plus afficher le formulaire.
    }
    else {
        $contenu .= '<div class="alert alert-danger"> Oups, erreur lors de l\'enregistrement... Veuillez essayer plus tard.</div>';
            } 
        }
    }
} //fin du if (!empty($_POST))

//*******************************************************************************************************************************
// Fin du formulaire membre
//*******************************************************************************************************************************





require_once '../inc/header.php';
// 2 - Navigation entre les pages d'administration :
?>

<ul class="nav nav-tabs">
    <li><a class="nav-link" href="gestion_boutique.php">Affichage des annonces</a></li>
    <li><a class="nav-link" href="gestion_categorie.php">Gestion catégories</a></li>
    <li><a class="nav-link active" href="gestion_membre.php">Gestion membre</a></li>
    <li><a class="nav-link" href="gestion_commentaire.php">Gestion commentaire</a></li>
    <li><a class="nav-link" href="gestion_note.php">Gestion note</a></li>
    <li><a class="nav-link" href="gestion_statistique.php">Gestion statistique</a></li>
</ul>

<h1 class="mt-4">Ajouter un membre</h1>

<?php
echo $contenu;
?>

<?php
if ($affiche_formulaire) : // Si membre pas inscrit, on affiche le formulaire. syntaxe en if(): ....  endif;
?>
    <form method="post" action="" style="padding-left: 20%; padding-right: 20%;">
        <div>
            <div><label for="pseudo">Votre pseudo *</label></div>
            <div><input type="text" name="pseudo" id="pseudo" style="width: 100%;" value="<?php echo $_POST['pseudo'] ?? ''; ?>"></div>
        </div>

        <div>
            <div><label for="mdp">Votre mot de passe *</label></div>
            <div><input type="password" name="mdp" id="mdp" style="width: 100%;" value="<?php echo $_POST['mdp'] ?? ''; ?>"></div>
        </div>

        <div>
            <div><label for="nom">Votre Nom *</label></div>
            <div><input type="text" name="nom" id="nom" style="width: 100%;" value="<?php echo $_POST['nom'] ?? ''; ?>"></div>
        </div>

        <div>
            <div><label for="prenom">Votre prénom *</label></div>
            <div><input type="text" name="prenom" id="prenom"style="width: 100%;" value="<?php echo $_POST['prenom'] ?? ''; ?>"></div>
        </div>

        <div>
            <div><label for="telephone" >Votre téléphone *</label></div>
            <div><input type="text" name="telephone"  id="telephone" style="width: 100%;" value="<?php echo $_POST['telephone'] ?? ''; ?>"></div>
        </div>

        <div>
            <div><label for="email">Votre email *</label></div>
            <div><input type="text" name="email" id="email" style="width: 100%;" value="<?php echo $_POST['email'] ?? ''; ?>"></div>
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

        <div>
        <div><label for="statut">Statut</label></div>
        <div class="input-group mb-3" >
            <select name="statut" class="custom" id="inputGroupSelect02">
                <option value="0"<?php if (isset($_POST['statut']) && $_POST['statut'] == '0') echo 'selected';?>>User</option>
                <option value="1"<?php if (isset($_POST['statut']) && $_POST['statut'] == '1') echo 'selected';?>>Admin</option>
            </select>
        </div>
    </div>

        <div><input type="submit" value="S'inscrire" ></div>
    </form><br>
<?php

endif;

require_once '../inc/footer.php';


