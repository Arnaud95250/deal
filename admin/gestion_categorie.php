<?php
require_once '../inc/init.php'; // attention au ../ (pour lier les fichier php ou html...)

// 1 - Vérification administrateur :
if (!estAdmin()){
    header('location:../connexion.php'); // Si le membre n'est pas connecté ou n'est pas admi.On le redirige vers la page de connection.
    exit(); // On quitte le script
}

// 7 - Suppression de la catégorie :
if (isset($_GET['id_categorie'])){ // Si existe id_categorie dans l'url, donc dans $_GET, c'est qu'on a demandé la suppression du annonce.
$resultat = executeRequete("DELETE FROM categorie WHERE id_categorie = :id_categorie", array(':id_categorie' => $_GET['id_categorie']));

    if ($resultat->rowCount() == 1){
        $contenu .= '<div class="alert alert-succes">Le catégorie a bien été supprimé.</div>';
    }
    // else {
    //     $contenu .= '<div class="alert alert-danger">Erreur lors de la suppression</div>';
    // }
}

// ("SELECT id_membre, titre, description_courte, description_longue, prix, photo, pays, ville, adresse, code_postal, prenom, id_categorie, date_enregistrement FROM annonce INNER JOIN membre ON annonce.id_membre = membre.id_membre");

// 6 - Affichage des annonces dans le back-office.
$resultat = executeRequete("SELECT id_categorie, titre, mots_cles FROM categorie"); // On selectionne tous les annonces.

$contenu .= '<div>Les diférentes catégorie : ' . $resultat->rowCount() . '</div>';

$contenu .= '<div class="table-responsive">';
    $contenu .= '<table class="table">';
    //Lignes des entêtes du tableau :
        $contenu .= '<tr>';
            $contenu .= '<th>id_categorie</th>';
            $contenu .= '<th>titre</th>';
            $contenu .= '<th>mots_cles</th>';
            $contenu .= '<th>action</th>';
        $contenu .= '</tr>';

        //Affichage des lignes du tableau :
        while ($categorie = $resultat->fetch(PDO::FETCH_ASSOC)){ //$categorie est un array avec toutes les informations d'un seul categorie à chaque tour de boucle. On le parcourt donc avec une boucle foreach.
        $contenu .= '<tr>';
            foreach ($categorie as $indice => $valeur){
                if ($indice == 'photo') { 
                $contenu .= '<td><img src="../'. $valeur .'"style="width:90px"></td>';
            }
            elseif ($indice == 'mots_cles'){
                $contenu .= '<td>' . substr($valeur, 0, 10) . '...</td>';
            
            }
            else{
                $contenu .= '<td>' . $valeur . '</td>';
            }
        }
        $contenu .= '<td>
                        <a href="formulaire_modification_categorie.php?id_categorie='.$categorie['id_categorie'].'">modifer</a> |
                        <a href="?id_categorie='.$categorie['id_categorie'].'" onclick = "return confirm(\'Etes-vous certain de vouloir supprimer ce annonce?\')">supprimer</a>
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

debug($_POST); //  Pour les 2 || ca veut dire ou.

if (!empty($_POST)) { // Si on a cliqué sur s'inscrire
// Ici on valide tous les champs du formulaire :
if (!isset($_POST['titre']) || strlen($_POST['titre']) < 1 ||strlen($_POST['titre']) > 20) { // Si le champ titre n'existe pas ou que la taille est trop longue ou trop courte on met un msg à l'internaute.
    $valider .= '<div class="alert alert-danger"> Le titre doit contenir entre 1 et 20 caractères</div>';
}

if (!isset($_POST['mots_cles']) || strlen($_POST['mots_cles']) < 0 ||strlen($_POST['mots_cles']) > 255) { // Si le champ pseudo n'existe pas ou que la taille est trop longue ou trop courte on met un msg à l'internaute.
    $valider .= '<div class="alert alert-danger"> La description courte doit contenir entre 0 et 255 caractères</div>';
}

// // S'il n'y a aps d'erreur sur le formulaire, on vérifie l'unicité du pseudo avant d'nscrire le membre.
// if (empty($valider)) { // si la variable est vide c'est qu'il n'y a pas de message d'erreur.
//     // On vérifie l'unicité du pseudo.
//     $membre = executeRequete("SELECT * FROM categorie WHERE categorie = :categorie", array(':categorie' => $_POST['categorie']));

//     if ($categorie->rowcount() > 0) { // Si la requête contient 1 ou plusieurs lignes, c'est que le categorie est déja en BDD
//         $valider .= '<div class="alert alert-danger"> Le categorie est indisponible. Veuillez en choisir un autre.</div>';
//     }
//     else { // Sinon on peut inscrire le categorie.
//         $mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT); // Nous hashons le mdp avec cette fonction qui utilise à l'heure actuelle l'algoritheme scrypt. Lors de la connexion de l'internaute, il faudra comparer le hash de connexion avec celui de la BDD.



        $succes = executeRequete("INSERT INTO categorie (id_categorie, titre, mots_cles) VALUES (:id_categorie, :titre, :mots_cles)", array(
            ':id_categorie'             => $_POST['id_categorie'],
            ':titre'                    => $_POST['titre'],
            ':mots_cles'                => $_POST['mots_cles'],
         ));
       
    if ($succes) {
        $contenu .= '<div class="alert alert-success"> Catégorie Ajouté</div>';
    }
    else {
        $contenu .= '<div class="alert alert-danger"> Oups, erreur lors de l\'enregistrement... Veuillez essayer plus tard.</div>';
            } 


} //fin du if (!empty($_POST))
// 8 - Remplissage du formulaire de modification de produit :
    if (isset($_GET['id_categorie'])) { //  Si on a reçu l'id_produit dans l'url, c'est qu'on a ddemandé la modification du produit.
        // On séléctionne les infos du produit en BDD pour remplir le formulaire :
        $resultat = executeRequete("SELECT id_categorie, titre, mots_cles FROM categorie WHERE id_categorie = :id_categorie", array(':id_categorie' => $_GET['id_categorie']));
        $_POST = $resultat->fetch(PDO::FETCH_ASSOC); // Pas de while car nous n'avons qu'un seul produit par id.
    }
//*******************************************************************************************************************************
// Fin du formulaire membre
//*******************************************************************************************************************************



require_once '../inc/header.php';
// 2 - Navigation entre les pages d'administration :
?>

<ul class="nav nav-tabs">
    <li><a class="nav-link" href="gestion_boutique.php">Affichage des annonces</a></li>
    <li><a class="nav-link active" href="gestion_categorie.php">Gestion catégories</a></li>
    <li><a class="nav-link" href="gestion_membre.php">Gestion membre</a></li>
    <li><a class="nav-link" href="gestion_commentaire.php">Gestion commentaire</a></li>
    <li><a class="nav-link" href="gestion_note.php">Gestion note</a></li>
    <li><a class="nav-link" href="gestion_statistique.php">Gestion statistique</a></li>
</ul>

<h1 class="mt-4">Catégorie</h1>

<?php
echo $contenu;
?>

<?php
if ($affiche_formulaire) : // Si membre pas inscrit, on affiche le formulaire. syntaxe en if(): ....  endif;
?>
    <form method="post" action="" enctype="multipart/form-data" style="padding-left: 20%; padding-right: 20%;">

    <div>
            <div><input type="hidden" name="id_categorie" id="id_categorie" style="width: 100%;" value="<?php echo $_POST['id_categorie'] ?? 0; ?>"></div>
        </div>

        <div>
            <div><label for="titre">Titre</label></div>
            <div><input type="text" name="titre" id="titre" style="width: 100%;" value="<?php echo $_POST['titre'] ?? ''; ?>"></div>
        </div>

        <div>
            <div><label for="mots_cles">Déscription courte</label></div>
            <div><textarea name="mots_cles" id="mots_cles" style="width: 100%; " value="<?php echo $_POST['mots_cles'] ?? ''; ?>"></textarea></div>
        </div>

        <div><input type="submit" value="Enregistrer" ></div>
    </form>
<?php

endif;

require_once '../inc/footer.php';

