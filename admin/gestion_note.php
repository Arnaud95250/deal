<?php
require_once '../inc/init.php'; // attention au ../ (pour lier les fichier php ou html...)

// 1 - Vérification administrateur :
if (!estAdmin()){
    header('location:../connexion.php'); // Si le membre n'esyt pas connecté ou n'est pas admi.On le redirige vers la page de connection.
    exit(); // On quitte le script
}

// 7 - Suppression du annonce :
if (isset($_GET['id_note'])){ // Si existe id_note dans l'url, donc dans $_GET, c'est qu'on a demandé la suppression du annonce.
$resultat = executeRequete("DELETE FROM note WHERE id_note = :id_note", array(':id_note' => $_GET['id_note']));

    if ($resultat->rowCount() == 1){
        $contenu .= '<div class="alert alert-succes">Le annonce a bien été supprimé.</div>';
    }
    else {
        $contenu .= '<div class="alert alert-danger">Erreur lors de la suppression</div>';
    }
}






// ("SELECT id_membre, titre, description_courte, description_longue, prix, photo, pays, ville, adresse, code_postal, prenom, id_note, date_enregistrement FROM annonce INNER JOIN membre ON annonce.id_membre = membre.id_membre");

// 6 - Affichage des annonces dans le back-office.
// $resultat = executeRequete("SELECT id_note, id_membre1, id_membre2, note, avis, date_enregistrement FROM note"); // On selectionne tous les annonces.

$resultat = executeRequete("SELECT n.id_note, m.email, a.id_annonce, n.note, n.avis, n.date_enregistrement 
                            FROM note n
                            INNER JOIN membre m
                            ON n.id_membre1 = m.id_membre
                            INNER JOIN annonce a
                            ON m.id_membre = a.id_membre2
                            ");


// $resultat = executeRequete("SELECT c.id_commentaire, m.id_membre, a.id_annonce, c.commentaire, c.date_enregistrement 
//                             FROM commentaire c
//                             INNER JOIN annonce a
//                             ON c.id_annonce = a.id_annonce
//                             INNER JOIN membre m
//                             ON a.id_membre2 = m.id_membre
//                             ");
                            


$contenu .= '<div>Les diférentes catégorie : ' . $resultat->rowCount() . '</div>';

$contenu .= '<div class="table-responsive">';
    $contenu .= '<table class="table">';
    //Lignes des entêtes du tableau :
        $contenu .= '<tr>';
            $contenu .= '<th>id_note</th>';
            $contenu .= '<th>id_membre_1</th>';
            $contenu .= '<th>id_membre_2</th>';
            $contenu .= '<th>note</th>';
            $contenu .= '<th>avis</th>';
            $contenu .= '<th>date_enregistrement</th>';
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
                        <a href="formulaire_modification_categorie.php?id_note='.$annonce['id_note'].'">modifer</a> |
                        <a href="?id_note='.$annonce['id_note'].'" onclick = "return confirm(\'Etes-vous certain de vouloir supprimer ce annonce?\')">supprimer</a>
                     </td>';


        // $contenu .= '<td>
        //                 <button type="button" class="#btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">modifer</button> | 
        //                 <a href="?id_membre='.$annonce['id_membre'].'" onclick = "return confirm(\'Etes-vous certain de vouloir supprimer ce annonce?\')">supprimer</a>
        //             </td>';



        $contenu .= '</tr>';
        }

    $contenu .='</div>';
$contenu .= '</table>';



?>

    

<?php
require_once '../inc/header.php';
// 2 - Navigation entre les pages d'administration :
?>

<ul class="nav nav-tabs">
    <li><a class="nav-link" href="gestion_boutique.php">Affichage des annonces</a></li>
    <li><a class="nav-link" href="gestion_categorie.php">Gestion catégories</a></li>
    <li><a class="nav-link" href="gestion_membre.php">Gestion membre</a></li>
    <li><a class="nav-link" href="gestion_commentaire.php">Gestion commentaire</a></li>
    <li><a class="nav-link active" href="gestion_note.php">Gestion note</a></li>
    <li><a class="nav-link" href="gestion_statistique.php">Gestion statistique</a></li>
</ul>

<h1 class="mt-4">Note</h1>

<?php
echo $contenu;
?>

<?php
// endif;

require_once '../inc/footer.php';


