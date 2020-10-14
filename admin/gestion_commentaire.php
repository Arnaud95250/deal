<?php
require_once '../inc/init.php'; // attention au ../ (pour lier les fichier php ou html...)

// 1 - Vérification administrateur :
if (!estAdmin()){
    header('location:../connexion.php'); // Si le membre n'esyt pas connecté ou n'est pas admi.On le redirige vers la page de connection.
    exit(); // On quitte le script
}

// 7 - Suppression du annonce :
if (isset($_GET['id_commentaire'])){ // Si existe id_commentaire dans l'url, donc dans $_GET, c'est qu'on a demandé la suppression du annonce.
$resultat = executeRequete("DELETE FROM commentaire WHERE id_commentaire = :id_commentaire", array(':id_commentaire' => $_GET['id_commentaire']));

    if ($resultat->rowCount() == 1){
        $contenu .= '<div class="alert alert-succes">Le annonce a bien été supprimé.</div>';
    }
    else {
        $contenu .= '<div class="alert alert-danger">Erreur lors de la suppression</div>';
    }
}

// ("SELECT id_membre, titre, description_courte, description_longue, prix, photo, pays, ville, adresse, code_postal, prenom, id_categorie, date_enregistrement FROM annonce INNER JOIN membre ON annonce.id_membre = membre.id_membre");

// 6 - Affichage des annonces dans le back-office.
$resultat = executeRequete("SELECT c.id_commentaire, c.id_membre, a.id_annonce, c.commentaire, c.date_enregistrement 
                            FROM commentaire c
                            INNER JOIN annonce a
                            ON c.id_annonce = a.id_annonce
                            ");
                            



// $resultat = executeRequete("SELECT * FROM commentaire 
//                             ");



$contenu .= '<div>Nombre de membres : ' . $resultat->rowCount() . '</div>';

$contenu .= '<div class="table-responsive">';
    $contenu .= '<table class="table">';
    //Lignes des entêtes du tableau :
        $contenu .= '<tr>';
            $contenu .= '<th>id_commentaire</th>';
            $contenu .= '<th>id_membre</th>';
            $contenu .= '<th>id_annonce</th>';
            $contenu .= '<th>commentaire</th>';
            $contenu .= '<th>date_enregistrement</th>';
            $contenu .= '<th>action</th>';
        $contenu .= '</tr>';

        //Affichage des lignes du tableau :
        while ($annonce = $resultat->fetch(PDO::FETCH_ASSOC)){ //$annonce est un array avec toutes les informations d'un seul annonce à chaque tour de boucle. On le parcourt donc avec une boucle foreach.
        $contenu .= '<tr>';
            foreach ($annonce as $indice => $valeur){
                            $contenu .= '<td>' . $valeur . '</td>';  
        }

        $contenu .= '<td>
                        <a href="formulaire_modification_categorie.php?id_commentaire='.$annonce['id_commentaire'].'">modifer</a> |
                        <a href="?id_commentaire='.$annonce['id_commentaire'].'" onclick = "return confirm(\'Etes-vous certain de vouloir supprimer ce annonce?\')">supprimer</a>
                     </td>';


        $contenu .= '</tr>';
        }

    $contenu .='</div>';
$contenu .= '</table>';




require_once '../inc/header.php';
// 2 - Navigation entre les pages d'administration :
?>

<ul class="nav nav-tabs">
    <li><a class="nav-link" href="gestion_boutique.php">Affichage des annonces</a></li>
    <li><a class="nav-link" href="gestion_categorie.php">Gestion catégories</a></li>
    <li><a class="nav-link" href="gestion_membre.php">Gestion membre</a></li>
    <li><a class="nav-link" href="gestion_commentaire.php">Gestion commentaire</a></li>
    <li><a class="nav-link" href="gestion_note.php">Gestion note</a></li>
    <li><a class="nav-link active" href="gestion_statistique.php">Gestion statistique</a></li>
</ul>


<?php
echo $contenu;
?>

<?php
// endif;

require_once '../inc/footer.php';


