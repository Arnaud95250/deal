<?php
require_once '../inc/init.php'; // attention au ../ (pour lier les fichier php ou html...)

// 1 - Vérification administrateur :
if (!estAdmin()){
    header('location:../connexion.php'); // Si le membre n'esyt pas connecté ou n'est pas admi.On le redirige vers la page de connection.
    exit(); // On quitte le script
}

// 7 - Suppression du annonce :
if (isset($_GET['id_annonce'])){ // Si existe id_annonce dans l'url, donc dans $_GET, c'est qu'on a demandé la suppression du annonce.
$resultat = executeRequete("DELETE FROM annonce WHERE id_annonce = :id_annonce", array(':id_annonce' => $_GET['id_annonce']));

    if ($resultat->rowCount() == 1){
        $contenu .= '<div class="alert alert-success">L\'annonce a bien été supprimé.</div>';
    }
    else {
        $contenu .= '<div class="alert alert-danger">Erreur lors de la suppression</div>';
    }
}

$resultat = executeRequete("SELECT id_annonce, annonce.titre, description_courte, description_longue, prix, photo, pays, ville, adresse, code_postal, membre.prenom, categorie.titre AS titrecat, annonce.date_enregistrement AS date
FROM annonce 
INNER JOIN membre 
ON annonce.id_membre2 = membre.id_membre
INNER JOIN categorie
ON annonce.id_categorie = categorie.id_categorie
");
// // 6 - Affichage des annonces dans le back-office.
// $resultat = executeRequete("SELECT * FROM annonce"); // On selectionne tous les annonces.






$contenu .= '<div>Nombre d\'annonces : ' . $resultat->rowCount() . '</div>';

$contenu .= '<div class="table-responsive">';
    $contenu .= '<table class="table">';
    //Lignes des entêtes du tableau :
        $contenu .= '<tr>';
            $contenu .= '<th>id_annonce</th>';
            $contenu .= '<th>titre</th>';
            $contenu .= '<th>description courte</th>';
            $contenu .= '<th>description longue</th>';
            $contenu .= '<th>prix €</th>';
            $contenu .= '<th>photo</th>';
            $contenu .= '<th>pays</th>';
            $contenu .= '<th>ville</th>';
            $contenu .= '<th>adresse</th>';
            $contenu .= '<th>cp</th>';
            $contenu .= '<th>membre</th>';
            $contenu .= '<th>catégorie</th>';
            $contenu .= '<th>enregistrement</th>';
            $contenu .= '<th>action</th>';
        $contenu .= '</tr>';

        //Affichage des lignes du tableau :
        while ($annonce = $resultat->fetch(PDO::FETCH_ASSOC)){ //$annonce est un array avec toutes les informations d'un seul annonce à chaque tour de boucle. On le parcourt donc avec une boucle foreach.
        $contenu .= '<tr>';
            foreach ($annonce as $indice => $valeur){
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
                        <a href="../deposer_annonce.php?id_annonce='.$annonce['id_annonce'].'">modifer</a> |
                        <a href="?id_annonce='.$annonce['id_annonce'].'" onclick = "return confirm(\'Etes-vous certain de vouloir supprimer ce annonce?\')">supprimer</a>
                     </td>';


        $contenu .= '</tr>';
        }

    $contenu .='</div>';
$contenu .= '</table>';



require_once '../inc/header.php';
// 2 - Navigation entre les pages d'administration :
?>

<ul class="nav nav-tabs">
    <li><a class="nav-link active" href="gestion_boutique.php">Affichage des annonces</a></li>
    <li><a class="nav-link" href="gestion_categorie.php">Gestion catégories</a></li>
    <li><a class="nav-link" href="gestion_membre.php">Gestion membres</a></li>
    <li><a class="nav-link" href="gestion_commentaire.php">Gestion commentaire</a></li>
    <li><a class="nav-link" href="gestion_note.php">Gestion note</a></li>
    <li><a class="nav-link" href="gestion_statistique.php">Gestion statistique</a></li>
</ul>

<h1 class="mt-4">Gestion des annonces</h1>
<?php
$resultat = executeRequete("SELECT  id_annonce, titre, description_courte, description_longue, prix, photo, pays, ville, adresse, code_postal, id_membre2, id_categorie, date_enregistrement FROM annonce");
$valider .= '<select name="id_annonce">';
    while ($annonce = $resultat->fetch(PDO::FETCH_ASSOC)){ 
            $valider .= '<option value="">'. $annonce['titre'] .'</option>';
    }
$valider .= '</select><br>';
echo $valider;

?>

<?php
echo $contenu;
?>



<?php
require_once '../inc/footer.php';
?>